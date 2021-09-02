<?php

declare (strict_types=1);
namespace App\Model\Account;

use App\Helpers\AppHelper;
use App\Helpers\JWTHelper;
use App\Model\Model;
use App\Swoole\Table\IMTable;
use Carbon\Carbon;

/**
 * @property int $id 
 * @property int $account_id 
 * @property int $login_platform 
 * @property string $token 
 * @property int $ttl 
 * @property string $expired_at 
 * @property string $refresh_at 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property int $deleted_at 
 */
class AccountAuthorization extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'account_authorization';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'account_id' => 'integer', 'authorization_id' => 'integer', 'ttl' => 'integer', 'created_at' => 'datetime', 'expired_at' => 'datetime', 'onlined_at' => 'datetime', 'updated_at' => 'datetime'];
    /**
     * @var string[]
     */
    protected $hidden = ['deleted_at'];

    /**
     * 是否正常账号授权值
     * @param      $account_id
     * @param      $authorization_id
     * @param null $token
     * @return bool
     */
    protected function isNormalAccountAuthorization($account_id, $authorization_id, $token = null): bool
    {
        $builder = $this->where(['account_id' => $account_id, 'authorization_id' => $authorization_id]);

        if ($token) {
            $builder->where('token', $token);
        }

        return $builder->where('expired_at', '>', Carbon::now())
            ->whereNull('deleted_at')
            ->exists();
    }

    /**
     * 获取用户账号授权信息
     * @param $account_id
     * @param $authorization_id
     * @return \Hyperf\Database\Model\Builder|\Hyperf\Database\Model\Model|object|null
     */
    protected function getAccountAuthorization($account_id, $authorization_id)
    {
        return $this->where(['account_id' => $account_id, 'authorization_id' => $authorization_id])->first();
    }

    /**
     * @param $account_id
     * @param $authorization_id
     * @return int
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    protected function createData($account_id, $authorization_id)
    {
        $token = JWTHelper::generateToken(
            AppHelper::getAccountToken()
                ->withAccountId($account_id)
                ->withAuthorizationId($authorization_id)
                ->toArray()
        );

        $token_ttl = JWTHelper::get()->getTTL($token);

        return $this->insertGetId([
            'account_id' => $account_id,
            'authorization_id' => $authorization_id,
            'token' => $token,
            'ttl' => $token_ttl,
            'expired_at' => Carbon::createFromTimestamp($token_ttl + time()),
            'created_at' => Carbon::now(),
        ]);
    }

    /**
     * @param $account_id
     * @param $authorization_id
     * @return int
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    protected function updateTokenData($account_id, $authorization_id)
    {
        $token = JWTHelper::generateToken(
            AppHelper::getAccountToken()
                ->withAccountId($account_id)
                ->withAuthorizationId($authorization_id)
                ->toArray()
        );

        $token_ttl = JWTHelper::get()->getTTL($token);

        return $this->where(['account_id' => $account_id, 'authorization_id' => $authorization_id])
            ->update([
                'token' => $token,
                'ttl' => $token_ttl,
                'expired_at' => Carbon::createFromTimestamp($token_ttl + time()),
                'refresh_at' => Carbon::now(),
                'deleted_at' => null,
            ]);
    }

    /**
     * @param AccountAuthorization $accountAuthorization
     * @return array
     */
    protected function builderAccountAuthorization(AccountAuthorization $accountAuthorization)
    {
        return [
            'id' => $accountAuthorization['id'],
            'account_id' => $accountAuthorization['account_id'],
            'authorization_id' => $accountAuthorization['authorization_id'],
            'token' => $accountAuthorization['token'],
            'expired_at' => $accountAuthorization['expired_at'],
            'refresh_at' => $accountAuthorization['refresh_at'],
        ];
    }

    /**
     * 设置用户账号授权为在线
     * @param $account_id
     * @param $authorization_id
     * @return bool
     */
    protected function setOnline($account_id, $authorization_id): bool
    {
        $this->where(['account_id' => $account_id, 'authorization_id' => $authorization_id])
            ->update([
                'is_online' => 1,
                'onlined_at' => Carbon::now(),
            ]);

        return true;
    }

    /**
     * 设置用户账号授权为离线
     * @param $account_id
     * @param $authorization_id
     * @return bool
     */
    protected function setOffline($account_id, $authorization_id): bool
    {
        $this->where(['account_id' => $account_id, 'authorization_id' => $authorization_id])
            ->update([
                'is_online' => 0,
                'offlined_at' => Carbon::now(),
            ]);

        return true;
    }

    /**
     * 用户账号授权是否在线
     * @param $account_id
     * @param $authorization_id
     * @return bool
     */
    protected function isOnline($account_id, $authorization_id): bool
    {
        foreach (AppHelper::getIMTable()->get() as $row) {
            if (($row[IMTable::CONLUMN_ACCOUNT_ID] == $account_id) && ($row[IMTable::COLUMN_AUTHORIZATION_ID] == $authorization_id)) {
                return true;
            }
        }

        return false;
    }
}