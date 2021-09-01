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

use App\Contract\AccountTokenInterface;

class AccountToken implements AccountTokenInterface
{
    const ACCOUNT_ID = 'account_id';
    const AUTHORIZATION_ID = 'authorization_id';

    protected $account_id;

    protected $authorization_id;

    public function withAccountId($account_id): AccountTokenInterface
    {
        // TODO: Implement withAccountId() method.

        $this->account_id = intval($account_id);
        return $this;
    }

    public function withAuthorizationId($authorization_id): AccountTokenInterface
    {
        // TODO: Implement withAuthorizationId() method.

        $this->authorization_id = intval($authorization_id);
        return $this;
    }

    public function getAccountId(): int
    {
        // TODO: Implement getAccountId() method.

        return intval($this->account_id);
    }

    public function getAuthorizationId(): int
    {
        // TODO: Implement getAuthorizationId() method.

        return intval($this->authorization_id);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        // TODO: Implement toArray() method.

        return [
            self::ACCOUNT_ID => $this->account_id,
            self::AUTHORIZATION_ID => $this->authorization_id,
        ];
    }
}