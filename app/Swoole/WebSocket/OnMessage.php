<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace App\Swoole\Websocket;

use App\Constants\MessageDefinition\Message;
use App\Constants\MessageDefinition\TextMessage;
use App\Constants\WebSocketErrorCode;
use App\Contract\RoomTypeInterface;
use App\Helpers\AppHelper;
use App\Helpers\WebsocketHelper;
use App\Model\Account\AccountFriend;
use Hyperf\Contract\OnMessageInterface;
use Swoole\WebSocket\Frame;

class OnMessage implements OnMessageInterface
{
    /**
     * @param \Swoole\Http\Response|\Swoole\WebSocket\Server $server
     * @param Frame                                          $frame
     */
    public function onMessage($server, Frame $frame): void
    {
        // TODO: Implement onMessage() method.

        $message  = WebsocketHelper::resolveMessage($frame->fd, $frame->data);

        if (! $message instanceof Message) return;

        switch (get_class($message)) {
            // 文本消息
            case TextMessage::class:
                break;
        }

        $push_message = function ($account_id, $authorization_id, $message) {
            $fds = WebsocketHelper::getAccountAuthorizationFd($account_id, $authorization_id);
            foreach ($fds as $fd) {
                AppHelper::getGo(function ($fd, $message) {
                    WebsocketHelper::pushMessage($fd, $message);
                }, $fd, $message);
            }
        };

        // 发送消息给当前登录用户
        $account_id = AppHelper::getAccountToken()->getAccountId();
        $authorization_id = AppHelper::getAccountToken()->getAuthorizationId();
        $push_message($account_id, $authorization_id, $message);

        $message_struct = $message->getMessageStruct();

        // 发送消息给对方(单聊/群聊)
        switch ($message_struct->getRoomType()) {
            case RoomTypeInterface::ROOM_TYPE_C2C:
                $to_account_id = $message_struct->getToAccountId();
                // 判断是否好友关系
                if (! AccountFriend::isBetoFriendRelation($account_id, $to_account_id)) {
                    WebsocketHelper::pushMessage($frame->fd, null, WebSocketErrorCode::WS_TO_ACCOUNT_NOT_FRIEND);
                    return;
                }

                // 发送消息
                $push_message($to_account_id, $authorization_id, $message);
                break;
            case RoomTypeInterface::ROOM_TYPE_GROUP:
                // 判断是否群成员

                // 获取群所有在线成员 fd

                // 发送消息

                break;
        }
    }
}