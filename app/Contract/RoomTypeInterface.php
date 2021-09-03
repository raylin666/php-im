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
 * 聊天/房间类型
 */
interface RoomTypeInterface
{
    // 单聊
    const ROOM_TYPE_C2C = 'C2C';
    // 群聊
    const ROOM_TYPE_GROUP = 'GROUP';

    /**
     * @return mixed
     */
    public function getC2C();

    /**
     * @return mixed
     */
    public function getGroup();

    /**
     * @return array
     */
    public function toArray(): array;
}