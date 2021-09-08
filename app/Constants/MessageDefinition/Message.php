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

use App\Contract\MessageDefinitionInterface;
use App\Contract\MessageInterface;
use App\Helpers\WebsocketHelper;
use Hyperf\Utils\ApplicationContext;

abstract class Message implements MessageDefinitionInterface
{
    /**
     * @var MessageInterface
     */
    protected $messageStruct;

    /**
     * @return MessageInterface
     */
    public function getMessageStruct(): MessageInterface
    {
        if (! $this->messageStruct instanceof MessageInterface) {
            $this->messageStruct = WebsocketHelper::getMessageStruct();
        }

        return $this->messageStruct;
    }

    /**
     * @param int    $messageId
     * @param string $roomType
     * @param int    $roomId
     * @param int    $fromAccountId
     * @param int    $toAccountId
     * @return MessageInterface
     */
    public function withBasicMessage(
        int $messageId,
        string $roomType,
        int $roomId = 0,
        int $fromAccountId = 0,
        int $toAccountId = 0
    ): self
    {
        $this->getMessageStruct()
            ->withRoomType($roomType)
            ->withRoomId($roomId)
            ->withFromAccountId($fromAccountId)
            ->withToAccountId($toAccountId)
            ->withMessageId($messageId);

        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        // TODO: Implement toArray() method.

        $message = $this->toMessage();

        return [
            MessageStruct::ROOM_TYPE => $message->getRoomType(),
            MessageStruct::ROOM_ID => $message->getRoomId(),
            MessageStruct::FROM_ACCOUNT_ID => $message->getFromAccountId(),
            MessageStruct::TO_ACCOUNT_ID => $message->getToAccountId(),
            MessageStruct::MESSAGE_TYPE => $message->getMessageType(),
            MessageStruct::MESSAGE_ID => $message->getMessageId(),
            MessageStruct::MESSAGE_DATA => $message->getMessageData(),
        ];
    }

    /**
     * @return MessageInterface
     */
    abstract protected function toMessage(): MessageInterface;

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        return ApplicationContext::getContainer()->get(get_called_class())->$name(...$arguments);
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return ApplicationContext::getContainer()->get(get_called_class())->$name(...$arguments);
    }
}