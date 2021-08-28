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

class HeadersHelper extends Helper
{
    /**
     * 获取账号ID
     * @return int
     */
    protected function getAccountId(): int
    {
        return defined('ACCOUNT_ID') ? ACCOUNT_ID : 0;
    }

    /**
     * 获取授权ID
     * @return int
     */
    protected function getAuthorizationId(): int
    {
        return defined('AUTHORIZATION_ID') ? AUTHORIZATION_ID : 0;
    }
}
