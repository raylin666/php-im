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