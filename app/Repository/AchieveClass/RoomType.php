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

use App\Contract\RoomTypeInterface;

class RoomType implements RoomTypeInterface
{
    public function getC2C()
    {
        // TODO: Implement getC2C() method.

        return RoomTypeInterface::ROOM_TYPE_C2C;
    }

    public function getGroup()
    {
        // TODO: Implement getGroup() method.

        return RoomTypeInterface::ROOM_TYPE_GROUP;
    }

    public function toArray(): array
    {
        // TODO: Implement toArray() method.

        return [
            $this->getC2C(),
            $this->getGroup(),
        ];
    }
}