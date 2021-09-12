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

use App\Constants\MessageDefinition\EnterGroupMessage;
use App\Contract\BuilderMessageInterface;
use App\Contract\MessageDefinitionInterface;

class BuilderEnterGroupMessage implements BuilderMessageInterface
{
    protected $enterAccountId;

    public function __construct(int $enterAccountId)
    {
        $this->enterAccountId = $enterAccountId;
    }

    public function get(): MessageDefinitionInterface
    {
        // TODO: Implement get() method.

        return EnterGroupMessage::withEnterAccountId($this->enterAccountId);
    }
}