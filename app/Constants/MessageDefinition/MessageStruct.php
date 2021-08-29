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
    protected $messageType;

    protected $messageData;

    public function withMessageType(string $messageType): MessageInterface
    {
        // TODO: Implement withMessageType() method.

        $this->messageType = $messageType;
        return $this;
    }

    public function getMessageType(): string
    {
        // TODO: Implement getMessageType() method.

        return $this->messageType;
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

        return $this->messageData;
    }
}