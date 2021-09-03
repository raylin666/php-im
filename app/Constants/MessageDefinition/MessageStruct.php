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
    // 文本消息
    const MESSAGE_TYPE_TEXT = 'text';
    // 图片消息
    const MESSAGE_TYPE_IMAGE = 'image';
    // 语音消息
    const MESSAGE_TYPE_VOICE = 'voice';

    protected $messageType;

    protected $messageData;

    protected $roomType;

    protected $roomId;

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

    public function withMessageData(array $messageData): MessageInterface
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