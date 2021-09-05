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
namespace App\Helpers;

class CommonHelper extends Helper
{
    /**
     * 生成唯一 ID 标识
     * @return string
     */
    protected function generateUniqid()
    {
        list($usec, $sec) = explode(' ', microtime());
        $usec = strval($usec * 1000 * 1000);
        return $sec . intval($usec . rand(1000, 9999));
    }
}
