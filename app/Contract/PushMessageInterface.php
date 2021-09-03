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

use Carbon\Carbon;

/**
 * 发送 WebSocket 消息结构体
 */
interface PushMessageInterface
{
    /**
     * @param Carbon $carbon
     * @return $this
     */
    public function withResponseTime(Carbon $carbon): self;

    /**
     * @param int $code
     * @return $this
     */
    public function withCode(int $code): self;

    /**
     * @param string $message
     * @return $this
     */
    public function withMessage(string $message): self;

    /**
     * @param MessageDefinitionInterface|null $definition
     * @return $this
     */
    public function withData(?MessageDefinitionInterface $definition): self;

    /**
     * @return Carbon
     */
    public function getResponseTime(): Carbon;

    /**
     * @return int
     */
    public function getCode(): int;

    /**
     * @return string
     */
    public function getMessage(): string;

    /**
     * @return MessageDefinitionInterface|null
     */
    public function getData(): ?MessageDefinitionInterface;
}