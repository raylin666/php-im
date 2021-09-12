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

class BuilderQuitGroupMessage implements BuilderMessageInterface
{
    protected $quitAccountId;

    public function __construct(int $quitAccountId)
    {
        $this->quitAccountId = $quitAccountId;
    }

    public function get(): MessageDefinitionInterface
    {
        // TODO: Implement get() method.

        return QuitGroupMessage::withQuitAccountId($this->quitAccountId);
    }
}