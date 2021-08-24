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
namespace App\Services;

/**
 * Class Service
 * @package App\Services
 */
abstract class Service
{
    /**
     * @var array
     */
    private static $instance = [];

    /**
     * @return object
     */
    public static function getInstance()
    {
        $className = get_called_class();
        if (!isset(self::$instance[$className]) || !self::$instance[$className] instanceof self) {
            self::$instance[$className] = new static();
        }

        return self::$instance[$className];
    }
}
