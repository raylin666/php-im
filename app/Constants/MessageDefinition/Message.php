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
    const MESSAGE_TYPE = 'message_type';
    const MESSAGE_DATA = 'message_data';
    const ROOM_TYPE = 'room_type';
    const ROOM_ID = 'room_id';
    const TO_ACCOUNT_ID = 'to_account_id';

    /**
     * @var MessageInterface
     */
    protected $messageStruct;

    /**
     * @return MessageInterface
     */
    protected function getMessageStruct(): MessageInterface
    {
        if (! $this->messageStruct instanceof MessageInterface) {
            $this->messageStruct = WebsocketHelper::getMessageStruct();
        }

        return $this->messageStruct;
    }

    /**
     * @param string $roomType
     * @param string $roomId
     * @param int    $toAccountId
     * @return MessageInterface
     */
    public function withBasicMessage(string $roomType, string $roomId = '', int $toAccountId = 0): self
    {
        $this->getMessageStruct()
            ->withRoomType($roomType)
            ->withRoomId($roomId)
            ->withToAccountId($toAccountId);

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
            self::ROOM_TYPE => $message->getRoomType(),
            self::ROOM_ID => $message->getRoomId(),
            self::TO_ACCOUNT_ID => $message->getToAccountId(),
            self::MESSAGE_TYPE => $message->getMessageType(),
            self::MESSAGE_DATA => $message->getMessageData(),
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
}