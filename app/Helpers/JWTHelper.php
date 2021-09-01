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

use Phper666\JWTAuth\JWT;

class JWTHelper extends Helper
{
    /**
     * @return false|mixed|null|JWT
     */
    protected function get()
    {
        return AppHelper::getRequest()->JWT;
    }

    /**
     * 生成 Token
     * @param $data
     * @return \Lcobucci\JWT\Token|string
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    protected function generateToken($data)
    {
        return $this->get()->getToken($data);
    }
}
