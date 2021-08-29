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
 * 消息协议定义
 */
interface MessageDefinitionInterface
{
    /**
     * 获取消息类型
     * @return string
     */
    public function getMessageType(): string;

    /**
     * 消息数组转换
     * @return array
     */
    public function toArray(): array;
}