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
     * 设置房间类型
     * @param string $roomType
     * @return $this
     */
    public function withRoomType(string $roomType): self;

    /**
     * 设置房间 ID
     * @param string $roomId
     * @return $this
     */
    public function withRoomId(string $roomId): self;

    /**
     * 设置发送者用户账号 ID
     * @param int $fromAccountId
     * @return $this
     */
    public function withFromAccountId(int $fromAccountId): self;

    /**
     * 设置接收者用户账号 ID
     * @param int $toAccountId
     * @return $this
     */
    public function withToAccountId(int $toAccountId): self;

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

    /**
     * 获取房间类型
     * @return string
     */
    public function getRoomType(): string;

    /**
     * 获取房间 ID
     * @return string
     */
    public function getRoomId(): string;

    /**
     * 获取发送者用户账号 ID
     * @return int
     */
    public function getFromAccountId(): int;

    /**
     * 获取接收者用户账号 ID
     * @return int
     */
    public function getToAccountId(): int;
}