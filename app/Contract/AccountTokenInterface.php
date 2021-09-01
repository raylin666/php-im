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
 * 用户账号 TOken 解析
 */
interface AccountTokenInterface
{
    /**
     * @param $account_id
     * @return $this
     */
    public function withAccountId($account_id): self;

    /**
     * @param $authorization_id
     * @return $this
     */
    public function withAuthorizationId($authorization_id): self;

    /**
     * @return int
     */
    public function getAccountId(): int;

    /**
     * @return int
     */
    public function getAuthorizationId(): int;

    /**
     * @return array
     */
    public function toArray(): array;
}