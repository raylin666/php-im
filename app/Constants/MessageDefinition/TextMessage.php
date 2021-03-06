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

/**
 * 文本消息类型
 *
 * 请求；
 * {"message_type": "text", "message_data": {"content": "hello world"}, "room_type": "C2C", "to_account_id": "5"}
 *
 * {"message_type": "text", "message_data": {"content": "在ya"}, "room_type": "GROUP", "room_id": "12"}
 *
 * 响应：
 * {
        "room_type": "C2C",
        "room_id": "",
        "to_account_id": 5,
        "message_type": "text",
        "message_id": 1,
        "message_data": {
            "content": "hello world"
        }
    }
 */
class TextMessage extends Message
{
    const MESSAGE_DATA_CONTENT = 'content';

    /**
     * 消息内容
     * @var string
     */
    protected $messageContent = '';

    /**
     * @return string
     */
    public function getMessageType(): string
    {
        // TODO: Implement getMessageType() method.

        return MessageStruct::MESSAGE_TYPE_TEXT;
    }

    /**
     * 设置消息内容
     * @param string $messageContent
     * @return $this
     */
    protected function withMessageContent(string $messageContent = ''): self
    {
        $this->messageContent = $messageContent;
        return $this;
    }

    /**
     * 获取消息内容
     * @return string
     */
    public function getMessageContent(): string
    {
        return $this->messageContent;
    }

    /**
     * @return MessageInterface
     */
    protected function toMessage(): MessageInterface
    {
        // TODO: Implement toMessage() method.

        return $this->getMessageStruct()
            ->withMessageType($this->getMessageType())
            ->withMessageData([
                self::MESSAGE_DATA_CONTENT => $this->getMessageContent(),
            ]);
    }
}