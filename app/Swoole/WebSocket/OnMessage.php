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
use App\Helpers\CommonHelper;
use App\Helpers\WebsocketHelper;
use App\Model\Friend\AccountFriend;
use App\Model\Group\Group;
use App\Model\Group\GroupAccount;
use App\Model\Message\C2cMessage;
use Carbon\Carbon;
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
        $is_send_from_account = true;
        // 是否需要发送消息给接收方
        $is_send_to_account = true;
        // 是否需要将消息保存为消息历史
        $is_save_message = false;
        // 是否系统消息
        $is_system_message = false;
        // 消息发送时间
        $message_send_at = Carbon::now();

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
            if (! AccountFriend::isBetoFriendRelation($account_id, $to_account_id)) {
                WebsocketHelper::pushMessage($frame->fd, null, WebSocketErrorCode::WS_TO_ACCOUNT_IS_FRIEND);
                return false;
            }

            return true;
        };

        // 单聊消息构建
        $builder_c2c_message = function ($account_id, $to_account_id, $authorization_id) use (
            $message,
            $frame,
            $builder_push_message,
            $builder_beto_friend_relation,
            &$is_send_from_account,
            &$is_send_to_account,
            &$is_save_message,
            &$is_system_message,
            &$call_message
        ) {
            switch (get_class($message)) {
                case TextMessage::class:
                    $is_save_message = true;
                    if (! $builder_beto_friend_relation($account_id, $to_account_id)) return;
                    break;
            }

            // 是否需要发送消息给当前发送方
            if ($is_send_from_account) {
                $call_message = array_merge($call_message, $builder_push_message($account_id, $authorization_id, $message));
            }

            // 是否需要发送消息给接收方
            if ($is_send_to_account) {
                $call_message = array_merge($call_message, $builder_push_message($to_account_id, $authorization_id, $message));
            }

            return [];
        };

        // 群聊消息构建
        $builder_group_message = function ($account_id, $to_account_id, $authorization_id) use (
            $message,
            $frame,
            $builder_push_message,
            &$is_save_message,
            &$is_system_message,
            &$call_message
        ) {
            $is_save_message = true;

            $group_id = $message->getMessageStruct()->getRoomId();
            // 判断群是否有效
            $group_id = Group::getGroupId($group_id);
            if (! $group_id) {
                WebsocketHelper::pushMessage($frame->fd, null, WebSocketErrorCode::WS_GROUP_NOT_VALID);
                return;
            }

            // 判断是否群成员
            if (! GroupAccount::isGroupAccount($account_id, $group_id)) {
                WebsocketHelper::pushMessage($frame->fd, null, WebSocketErrorCode::WS_ACCOUNT_NOT_GROUP_MEMBER);
                return;
            }

            switch (get_class($message)) {
                case TextMessage::class:
                    break;
            }

            // 获取群所有在线成员 fd 并构建发送消息

            return [];
        };

        $message_struct = $message->getMessageStruct();
        $account_id = AppHelper::getAccountToken()->getAccountId();
        $authorization_id = AppHelper::getAccountToken()->getAuthorizationId();
        $to_account_id = $message_struct->getToAccountId();

        // 发送消息给对方(单聊/群聊)
        switch ($message_struct->getRoomType()) {
            case RoomTypeInterface::ROOM_TYPE_C2C:

                $message_builder_result = $builder_c2c_message($account_id, $to_account_id, $authorization_id);

                if (! is_array($message_builder_result)) return;

                break;
            case RoomTypeInterface::ROOM_TYPE_GROUP:

                $message_builder_result = $builder_group_message($account_id, $to_account_id, $authorization_id);

                if (! is_array($message_builder_result)) return;

                break;
        }

        foreach ($call_message as $item_message) {
            AppHelper::getGo(function ($fd, $message) {
                WebsocketHelper::pushMessage($fd, $message);
            }, $item_message['fd'], $item_message['message']);
        }

        if ($is_save_message) {
            AppHelper::getGo(function () use (
                $message_struct,
                $account_id,
                $to_account_id,
                $message_send_at,
                $is_system_message
            ) {
                // 发送消息给对方(单聊/群聊)
                switch ($message_struct->getRoomType()) {
                    case RoomTypeInterface::ROOM_TYPE_C2C:
                        C2cMessage::addMessage(
                            $account_id,
                            $to_account_id,
                            $message_struct->getMessageType(),
                            json_encode($message_struct->getMessageData()),
                            $message_send_at,
                            $is_system_message
                        );
                        break;
                    case RoomTypeInterface::ROOM_TYPE_GROUP:
                        break;
                }
            });
        }
    }
}