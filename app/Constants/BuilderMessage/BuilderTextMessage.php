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
namespace App\Constants\BuilderMessage;

use App\Constants\MessageDefinition\TextMessage;
use App\Contract\BuilderMessageInterface;
use App\Contract\MessageDefinitionInterface;

class BuilderTextMessage implements BuilderMessageInterface
{
    protected $messageContent;

    public function __construct(string $messageContent = '')
    {
        $this->messageContent = $messageContent;
    }

    public function get(): MessageDefinitionInterface
    {
        // TODO: Implement get() method.

        return TextMessage::withMessageContent($this->messageContent);
    }
}