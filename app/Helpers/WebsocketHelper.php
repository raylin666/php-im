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
namespace App\Helpers;

use App\Constants\BuilderMessage\BuilderEnterGroupMessage;
use App\Constants\BuilderMessage\BuilderExitGroupMessage;
use App\Constants\BuilderMessage\BuilderJoinGroupMessage;
use App\Constants\BuilderMessage\BuilderQuitGroupMessage;
use App\Constants\BuilderMessage\BuilderTextMessage;
use App\Constants\MessageDefinition\EnterGroupMessage;
use App\Constants\MessageDefinition\ExitGroupMessage;
use App\Constants\MessageDefinition\JoinGroupMessage;
use App\Constants\MessageDefinition\Message;
use App\Constants\MessageDefinition\MessageStruct;
use App\Constants\MessageDefinition\QuitGroupMessage;
use App\Constants\MessageDefinition\TextMessage;
use App\Constants\WebSocketErrorCode;
use App\Contract\BuilderMessageInterface;
use App\Contract\BuilderPushMessageInterface;
use App\Contract\MessageDefinitionInterface;
use App\Contract\MessageInterface;
use App\Contract\RoomTypeInterface;
use App\Model\Friend\AccountFriend;
use App\Model\Group\Group;
use App\Model\Group\GroupAccount;
use App\Model\Message\C2cMessage;
use App\Repository\AchieveClass\BuilderPushMessage;
use App\Repository\AchieveClass\PushMessage;
use App\Repository\AchieveClass\RoomType;
use App\Swoole\Table\IMTable;
use Carbon\Carbon;

class WebsocketHelper extends Helper
{
    /**
     * 获取/设置 消息结构体
     * @return MessageInterface
     */
    protected function getMessageStruct(): MessageInterface
    {
        return AppHelper::getContainer()->get(MessageStruct::class);
    }

    /**
     * 获取用户账号授权的所有 fd
     * @param $account_id
     * @return array
     */
    protected function getAccountAuthorizationFd($account_id): array
    {
        $fds = [];
        foreach (AppHelper::getIMTable()->get() as $row) {
            if (($row[IMTable::CONLUMN_ACCOUNT_ID] == $account_id) && ($row[IMTable::COLUMN_AUTHORIZATION_ID] == AppHelper::getAccountToken()->getAuthorizationId())) {
                $fds[] = $row[IMTable::COLUMN_FD];
            }
        }

        return $fds;
    }

    /**
     * 构造消息体
     * @param string $messageType
     * @param array  $messageData
     * @return MessageDefinitionInterface|null
     */
    protected function builderMessage(string $messageType, array $messageData): ?MessageDefinitionInterface
    {
        /** @var BuilderMessageInterface $builderMessage */
        switch ($messageType) {
            case MessageStruct::MESSAGE_TYPE_TEXT:
                $builderMessage = AppHelper::getContainer()->make(
                    BuilderTextMessage::class,
                    [
                        $messageData[TextMessage::MESSAGE_DATA_CONTENT] ?? ''
                    ]
                );
                break;
            case MessageStruct::MESSAGE_ENTER_GROUP:
                $builderMessage = AppHelper::getContainer()->make(
                    BuilderEnterGroupMessage::class,
                    [
                        intval($messageData[EnterGroupMessage::MESSAGE_ENTER_ACCOUNT_ID] ?? 0)
                    ]
                );
                break;
            case MessageStruct::MESSAGE_QUIT_GROUP:
                $builderMessage = AppHelper::getContainer()->make(
                    BuilderQuitGroupMessage::class,
                    [
                        intval($messageData[QuitGroupMessage::MESSAGE_QUIT_ACCOUNT_ID] ?? 0)
                    ]
                );
                break;
            case MessageStruct::MESSAGE_JOIN_GROUP:
                $builderMessage = AppHelper::getContainer()->make(
                    BuilderJoinGroupMessage::class,
                    [
                        intval($messageData[JoinGroupMessage::MESSAGE_JOIN_ACCOUNT_ID] ?? 0)
                    ]
                );
                break;
            case MessageStruct::MESSAGE_EXIT_GROUP:
                $builderMessage = AppHelper::getContainer()->make(
                    BuilderExitGroupMessage::class,
                    [
                        intval($messageData[ExitGroupMessage::MESSAGE_EXIT_ACCOUNT_ID] ?? 0)
                    ]
                );
                break;
            default:
                return null;
        }

        return $builderMessage->get();
    }

    /**
     * 构建发送消息内容, 单个用户账号对应多个 fd
     * @param                            $account_id
     * @param MessageDefinitionInterface $message
     * @return array
     */
    protected function builderPushMessage($account_id, MessageDefinitionInterface $message): array
    {
        $call_message = [];
        $fds = $this->getAccountAuthorizationFd($account_id);
        foreach ($fds as $fd) {
            $builder_push_message = AppHelper::getContainer()->get(BuilderPushMessage::class);
            $call_message[] = $builder_push_message
                ->withFd($fd)
                ->withMessage($message);
        }

        return $call_message;
    }

    /**
     * 消息处理
     * @param $fd
     * @param $data
     */
    protected function handlerMessage($fd, $data)
    {
        /** @var BuilderPushMessageInterface[] $call_message  */
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

        $message  = $this->resolveMessage($fd, $data);

        if (! $message instanceof Message) return;

        // 判断是否好友关系
        $builder_beto_friend_relation = function ($account_id, $to_account_id) use ($fd) {
            if (! AccountFriend::isBetoFriendRelation($account_id, $to_account_id)) {
                $this->pushMessage($fd, null, WebSocketErrorCode::WS_TO_ACCOUNT_NOT_IS_FRIEND);
                return false;
            }

            return true;
        };

        // 单聊消息构建
        $builder_c2c_message = function ($account_id, $to_account_id) use (
            $message,
            $fd,
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
                $call_message = array_merge($call_message, $this->builderPushMessage($account_id, $message));
            }

            // 是否需要发送消息给接收方
            if ($is_send_to_account) {
                $call_message = array_merge($call_message, $this->builderPushMessage($to_account_id, $message));
            }

            return [];
        };

        // 群聊消息构建
        $builder_group_message = function ($account_id, $to_account_id) use (
            $message,
            $fd,
            &$is_save_message,
            &$is_system_message,
            &$call_message
        ) {
            $is_save_message = true;
            $group_id = $message->getMessageStruct()->getRoomId();

            // 判断群是否有效
            $group_id = Group::getGroupId($group_id);
            if (! $group_id) {
                $this->pushMessage($fd, null, WebSocketErrorCode::WS_GROUP_NOT_VALID);
                return;
            }

            // 判断是否群成员
            if (! GroupAccount::isGroupAccount($account_id, $group_id)) {
                $this->pushMessage($fd, null, WebSocketErrorCode::WS_ACCOUNT_NOT_GROUP_MEMBER);
                return;
            }

            switch (get_class($message)) {
                case JoinGroupMessage::class:
                    break;
                case ExitGroupMessage::class:
                    break;
                case EnterGroupMessage::class:
                    break;
                case QuitGroupMessage::class:
                    break;
                case TextMessage::class:
                    break;
            }

            // 获取群所有在线成员 fd 并构建发送消息

            return [];
        };

        $message_struct = $message->getMessageStruct();
        $account_id = AppHelper::getAccountToken()->getAccountId();
        $to_account_id = $message_struct->getToAccountId();

        // 发送消息给对方(单聊/群聊)
        switch ($message_struct->getRoomType()) {
            case RoomTypeInterface::ROOM_TYPE_C2C:

                $message_builder_result = $builder_c2c_message($account_id, $to_account_id);

                if (! is_array($message_builder_result)) return;

                break;
            case RoomTypeInterface::ROOM_TYPE_GROUP:

                $message_builder_result = $builder_group_message($account_id, $to_account_id);

                if (! is_array($message_builder_result)) return;

                break;
        }

        // 发送消息
        /** @var BuilderPushMessageInterface $item_message */
        foreach ($call_message as $item_message) {
            $this->pushMessage($item_message->getFd(), $item_message->getMessage());
        }

        // 保存消息
        if ($is_save_message) {
            $this->saveMessage($message, $account_id, $to_account_id, $message_send_at, $is_system_message);
        }
    }

    /**
     * 消息内容解析
     * @param        $fd
     * @param string $data
     * @return \App\Constants\MessageDefinition\Message|TextMessage|void
     */
    protected function resolveMessage($fd, string $data)
    {
        $data = json_decode($data, true);
        if (json_last_error() || empty($data)) {
            $this->pushMessage($fd, null, WebSocketErrorCode::WS_MESSAGE_RESOLVE_ERROR);
            return;
        }

        $roomTypeInstance = AppHelper::getContainer()->get(RoomType::class);
        $accountId = AppHelper::getAccountToken()->getAccountId();
        $messageType = $data[MessageStruct::MESSAGE_TYPE] ?? '';
        $messageData = $data[MessageStruct::MESSAGE_DATA] ?? [];
        $roomType = $data[MessageStruct::ROOM_TYPE] ?? '';
        $roomId = intval($data[MessageStruct::ROOM_ID] ?? 0);
        $toAccountId = intval($data[MessageStruct::TO_ACCOUNT_ID] ?? 0);
        if (empty($messageType)
            || empty($messageData)
            || empty($roomType)
            || !in_array($roomType, $roomTypeInstance->toArray())
            || (($roomType == $roomTypeInstance->getC2C()) && ($toAccountId <= 0))
            || (($roomType == $roomTypeInstance->getGroup()) && empty($roomId))
        ) {
            $this->pushMessage($fd, null, WebSocketErrorCode::WS_MESSAGE_FORMAT_ERROR);
            return;
        }

        if (! ($messageDefinition = $this->builderMessage($messageType, $messageData))) {
            $this->pushMessage($fd, null, WebSocketErrorCode::WS_UNSUPPORTED_MESSAGE_TYPE);
            return;
        }

        // 设置基础消息信息
        /** @var Message $messageDefinition */
        $messageDefinition->getMessageStruct()
            ->withRoomType($roomType)
            ->withRoomId($roomId)
            ->withFromAccountId($accountId)
            ->withToAccountId($toAccountId)
            ->withMessageId(0);

        return $messageDefinition;
    }

    /**
     * 保存消息
     * @param MessageDefinitionInterface $messageDefinition
     * @param                            $accountId
     * @param                            $toAccountId
     * @param Carbon|null                $messageSendAt
     * @param bool                       $isSystemMessage
     */
    protected function saveMessage(
        MessageDefinitionInterface $messageDefinition,
        $accountId,
        $toAccountId,
        ?Carbon $messageSendAt,
        bool $isSystemMessage = false
    )
    {
        $messageDefinition->toArray();
        /** @var Message $messageDefinition */
        AppHelper::getGo(function () use (
            $messageDefinition,
            $accountId,
            $toAccountId,
            $messageSendAt,
            $isSystemMessage
        ) {
            // 发送消息给对方(单聊/群聊)
            switch ($messageDefinition->getMessageStruct()->getRoomType()) {
                case RoomTypeInterface::ROOM_TYPE_C2C:
                    C2cMessage::addMessage(
                        $accountId,
                        $toAccountId,
                        $messageDefinition->getMessageStruct()->getMessageType(),
                        json_encode($messageDefinition->getMessageStruct()->getMessageData()),
                        $messageSendAt ?: Carbon::now(),
                        $isSystemMessage
                    );
                    break;
                case RoomTypeInterface::ROOM_TYPE_GROUP:
                    break;
            }
        });
    }

    /**
     * 消息协议发送
     * @param                                 $fd
     * @param MessageDefinitionInterface|null $definition
     * @param int                             $code
     * @param null                            $message
     * @param bool                            $isClose
     */
    protected function pushMessage(
        $fd,
        ?MessageDefinitionInterface $definition,
        $code = WebSocketErrorCode::WS_SUCCESS,
        $message = null,
        bool $isClose = false)
    {
        $fd = intval($fd);
        AppHelper::getGo(function ($fd, $definition, $code, $message, $isClose) {
            /** @var PushMessage $push_message */
            $push_message = AppHelper::getContainer()->get(PushMessage::class)
                ->withCode(intval($code))
                ->withMessage(strval($message))
                ->withData($definition);

            $server = AppHelper::getServer();
            $server->push($fd, $push_message->toJson());
            if ($isClose) $server->close($fd);
        }, $fd, $definition, $code, $message, $isClose);
    }
}
