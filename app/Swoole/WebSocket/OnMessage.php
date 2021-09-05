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

use App\Constants\MessageDefinition\FriendApplyMessage;
use App\Constants\MessageDefinition\Message;
use App\Constants\MessageDefinition\TextMessage;
use App\Constants\WebSocketErrorCode;
use App\Contract\RoomTypeInterface;
use App\Helpers\AppHelper;
use App\Helpers\CommonHelper;
use App\Helpers\WebsocketHelper;
use App\Model\Account\AccountFriend;
use App\Model\Account\AccountFriendApply;
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

        // 发送消息体集合
        $call_message = [];
        // 是否需要发送消息给当前发送方
        $is_send_current_account = true;

        $message  = WebsocketHelper::resolveMessage($frame->fd, $frame->data);

        if (! $message instanceof Message) return;

        // 构造发送消息体给用户
        $builder_push_message = function ($account_id, $authorization_id, $message) {
            $call_message = [];
            $fds = WebsocketHelper::getAccountAuthorizationFd($account_id, $authorization_id);
            foreach ($fds as $fd) {
                $call_message[CommonHelper::generateUniqid()] = [
                    'fd' => $fd,
                    'message' => $message,
                ];
            }

            return $call_message;
        };

        // 判断是否好友关系
        $builder_beto_friend_relation = function ($account_id, $to_account_id) use ($frame) {
            if (AccountFriend::isBetoFriendRelation($account_id, $to_account_id)) {
                WebsocketHelper::pushMessage($frame->fd, null, WebSocketErrorCode::WS_TO_ACCOUNT_IS_FRIEND);
                return false;
            }

            return true;
        };

        $message_struct = $message->getMessageStruct();
        $account_id = AppHelper::getAccountToken()->getAccountId();
        $authorization_id = AppHelper::getAccountToken()->getAuthorizationId();
        $to_account_id = $message_struct->getToAccountId();

        switch (get_class($message)) {
            case TextMessage::class:
                break;
            case FriendApplyMessage::class:
                $is_send_current_account = false;
                // 判断是否好友关系
                if (! $builder_beto_friend_relation($account_id, $to_account_id)) return;
                // 判断是否已有未确认的消息
                if (AccountFriendApply::isExistBeConfirm($account_id, $to_account_id)) {
                    WebsocketHelper::pushMessage($frame->fd, null, WebSocketErrorCode::WS_TO_ACCOUNT_JOIN_FRIEND_BE_CONFIRM);
                    return;
                }
                // 写入申请好友消息
                AccountFriendApply::addAccountFriendApply($account_id, $to_account_id, $message->getApplyRemark());
                break;
        }

        // 发送消息给当前登录用户
        if ($is_send_current_account) {
            $call_message = array_merge($call_message, $builder_push_message($account_id, $authorization_id, $message));
        }

        // 发送消息给对方(单聊/群聊)
        switch ($message_struct->getRoomType()) {
            case RoomTypeInterface::ROOM_TYPE_C2C:


                // 发送消息
                $call_message = array_merge($call_message, $builder_push_message($to_account_id, $authorization_id, $message));
                break;
            case RoomTypeInterface::ROOM_TYPE_GROUP:
                // 判断是否群成员

                // 获取群所有在线成员 fd

                // 发送消息

                break;
        }

        foreach ($call_message as $item_message) {
            AppHelper::getGo(function ($fd, $message) {
                WebsocketHelper::pushMessage($fd, $message);
            }, $item_message['fd'], $item_message['message']);
        }
    }
}