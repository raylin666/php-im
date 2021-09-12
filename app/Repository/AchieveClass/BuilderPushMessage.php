<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace App\Repository\AchieveClass;

use App\Contract\BuilderPushMessageInterface;
use App\Contract\MessageDefinitionInterface;

class BuilderPushMessage implements BuilderPushMessageInterface
{
    protected $fd;
    protected $message;

    public function withFd(int $fd): BuilderPushMessageInterface
    {
        // TODO: Implement withFd() method.

        $this->fd = $fd;
        return $this;
    }

    public function getFd(): int
    {
        // TODO: Implement getFd() method.

        return $this->fd;
    }

    public function withMessage(MessageDefinitionInterface $message): BuilderPushMessageInterface
    {
        // TODO: Implement withMessage() method.

        $this->message = $message;
        return $this;
    }

    public function getMessage(): MessageDefinitionInterface
    {
        // TODO: Implement getMessage() method.

        return $this->message;
    }
}