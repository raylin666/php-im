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

use App\Constants\MessageDefinition\QuitGroupMessage;
use App\Contract\BuilderMessageInterface;
use App\Contract\MessageDefinitionInterface;

class BuilderExitGroupMessage implements BuilderMessageInterface
{
    protected $exitAccountId;

    public function __construct(int $exitAccountId)
    {
        $this->exitAccountId = $exitAccountId;
    }

    public function get(): MessageDefinitionInterface
    {
        // TODO: Implement get() method.

        return QuitGroupMessage::withQuitAccountId($this->exitAccountId);
    }
}