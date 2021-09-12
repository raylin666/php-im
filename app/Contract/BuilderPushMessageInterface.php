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
namespace App\Contract;

/**
 * 构造 WebSocket 消息发送内容
 */
interface BuilderPushMessageInterface
{
    public function withFd(int $fd): self;

    public function getFd(): int;

    public function withMessage(MessageDefinitionInterface $message): self;

    public function getMessage(): MessageDefinitionInterface;
}