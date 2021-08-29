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
use Hyperf\Utils\ApplicationContext;

abstract class Message implements MessageDefinitionInterface
{
    /**
     * @return array
     */
    public function toArray(): array
    {
        // TODO: Implement toArray() method.

        $message = $this->toMessage();

        return [
            'message_type' => $message->getMessageType(),
            'message_data' => $message->getMessageData(),
        ];
    }

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