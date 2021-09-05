<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace App\Constants\MessageDefinition;

use App\Contract\MessageInterface;

class MessageStruct implements MessageInterface
{
    /**
     * 消息格式
     */
    // 消息类型
    const MESSAGE_TYPE = 'message_type';
    // 消息 ID
    const MESSAGE_ID = 'message_id';
    // 消息数据结构体
    const MESSAGE_DATA = 'message_data';
    // 房间类型
    const ROOM_TYPE = 'room_type';
    // 房间 ID
    const ROOM_ID = 'room_id';
    // 发送者账号 ID
    const FROM_ACCOUNT_ID = 'from_account_id';
    // 接收者账号 ID
    const TO_ACCOUNT_ID = 'to_account_id';

    /**
     * 消息类型
     */
    // 文本消息
    const MESSAGE_TYPE_TEXT = 'text';
    // 图片消息
    const MESSAGE_TYPE_IMAGE = 'image';
    // 语音消息
    const MESSAGE_TYPE_VOICE = 'voice';
    // 用户账号加入群消息
    const MESSAGE_JOIN_GROUP = 'join_group';
    // 用户账号退出群消息
    const MESSAGE_EXIT_GROUP = 'exit_group';
    // 用户账号进入群消息
    const MESSAGE_ENTER_GROUP = 'enter_group';
    // 用户账号离开群消息
    const MESSAGE_QUIT_GROUP = 'quit_group';
    // 用户账号申请加好友消息
    const MESSAGE_FRIEND_APPLY = 'friend_apply';
    // 通过用户账号加好友申请消息
    const MESSAGE_PASSED_FRIEND_APPLY = 'passed_friend_apply';

    protected $messageType;

    protected $messageId;

    protected $messageData;

    protected $roomType;

    protected $roomId;

    protected $fromAccountId;

    protected $toAccountId;

    public function withMessageType(string $messageType): MessageInterface
    {
        // TODO: Implement withMessageType() method.

        $this->messageType = $messageType;
        return $this;
    }

    public function getMessageType(): string
    {
        // TODO: Implement getMessageType() method.

        return strval($this->messageType);
    }

    public function withMessageId(int $messageId): MessageInterface
    {
        $this->messageId = $messageId;
        return $this;
    }

    /**
     * @return int
     */
    public function getMessageId(): int
    {
        return intval($this->messageId);
    }

    public function withMessageData(array $messageData = []): MessageInterface
    {
        // TODO: Implement withMessageData() method.

        $this->messageData = $messageData;
        return $this;
    }

    public function getMessageData(): array
    {
        // TODO: Implement getMessageData() method.

        return is_array($this->messageData) ? $this->messageData : [];
    }

    public function withRoomType(string $roomType): MessageInterface
    {
        // TODO: Implement withRoomType() method.

        $this->roomType = $roomType;
        return $this;
    }

    public function getRoomType(): string
    {
        // TODO: Implement getRoomType() method.

        return strval($this->roomType);
    }

    public function withRoomId(string $roomId): MessageInterface
    {
        // TODO: Implement withRoomId() method.

        $this->roomId = $roomId;
        return $this;
    }

    public function getRoomId(): string
    {
        // TODO: Implement getRoomId() method.

        return strval($this->roomId);
    }

    public function withFromAccountId(int $fromAccountId): MessageInterface
    {
        // TODO: Implement withFromAccountId() method.

        $this->fromAccountId = $fromAccountId;
        return $this;
    }

    public function getFromAccountId(): int
    {
        // TODO: Implement getFromAccountId() method.

        return $this->fromAccountId;
    }

    public function withToAccountId(int $toAccountId): MessageInterface
    {
        // TODO: Implement withToAccountId() method.

        $this->toAccountId = $toAccountId;
        return $this;
    }

    public function getToAccountId(): int
    {
        // TODO: Implement getToAccountId() method.

        return intval($this->toAccountId);
    }
}