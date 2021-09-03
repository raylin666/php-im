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
namespace App\Constants;

class Common
{
    /**
     * 请求头相关
     */
    // 用户业务账号登录 Token 标识
    const REQUEST_HEADERS_ACCOUNT_TOKEN = 'account-token';
    // 用户账号 APP-Key
    const REQUEST_HEADERS_AUTHORIZATION_KEY = 'authorization-key';
    // 用户账号 APP-Secret
    const REQUEST_HEADERS_AUTHORIZATION_SECRET = 'authorization-secret';
}
