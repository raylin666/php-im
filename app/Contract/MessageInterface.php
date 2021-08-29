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
 * 消息发送规则定义
 */
interface MessageInterface
{
    /**
     * 设置消息类型
     * @param string $messageType
     * @return $this
     */
    public function withMessageType(string $messageType): self;

    /**
     * 设置消息内容
     * @param array $messageData
     * @return $this
     */
    public function withMessageData(array $messageData): self;

    /**
     * 获取消息类型
     * @return string
     */
    public function getMessageType(): string;

    /**
     * 获取消息内容
     * @return array
     */
    public function getMessageData(): array;
}