<?php

declare (strict_types=1);
namespace App\Model\Account;

use App\Helpers\AppHelper;
use App\Model\Model;
use Carbon\Carbon;

/**
 * @property int $id
 * @property int $authorization_id
 * @property string $username
 * @property string $uid
 * @property string $avatar
 * @property int $state 
 * @property \Carbon\Carbon $created_at 
 * @property int $deleted_at 
 */
class Account extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'account';
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
    protected $casts = ['id' => 'integer', 'authorization_id' => 'integer', 'uid' => 'integer', 'state' => 'integer', 'created_at' => 'datetime'];

    /**
     * @var string[]
     */
    protected $hidden = ['deleted_at'];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * 账号状态
     */
    // 关闭
    const STATE_CLOSE = 0;
    // 开启
    const STATE_OPEN = 1;
    // 删除
    const STATE_DELETE = 2;

    /**
     * 获取账号ID
     * @param int $account_id
     * @param int $authorization_id
     * @return int
     */
    protected function getAccountId($account_id = 0, $authorization_id = 0): int
    {
        $builder = $this;

        if ($account_id) {
            $builder = $builder->where('id', $account_id);
        }

        return intval($builder->where(['authorization_id' => $authorization_id ?: AppHelper::getAuthorizationId(), 'state' => self::STATE_OPEN])
            ->value('id'));
    }

    /**
     * 通过应用ID 获取账号ID
     * @param $uid
     * @return int
     */
    protected function getUidByAccountId($uid): int
    {
        return intval($this->where(['uid' => $uid, 'authorization_id' => AppHelper::getAuthorizationId()])
            ->value('id'));
    }

    /**
     * 账号是否可用
     * @param $account_id
     * @return bool
     */
    protected function isAccountAvailable($account_id): bool
    {
        return $this->where(['id' => $account_id, 'authorization_id' => AppHelper::getAuthorizationId(), 'state' => self::STATE_OPEN])
            ->exists();
    }

    /**
     * 获取账号信息
     * @param $account_id
     * @return \Hyperf\Database\Model\Builder|\Hyperf\Database\Model\Model|object|null
     */
    protected function getAccount($account_id)
    {
        return $this->where(['id' => $account_id, 'authorization_id' => AppHelper::getAuthorizationId()])->first();
    }

    /**
     * 添加用户账号
     * @param $uid
     * @param $username
     * @param $avatar
     * @return int
     */
    protected function addAccount($uid, $username, $avatar): int
    {
        return $this->insertGetId([
            'authorization_id' => AppHelper::getAuthorizationId(),
            'uid' => $uid,
            'username' => $username,
            'avatar' => $avatar,
            'state' => self::STATE_OPEN,
            'created_at' => Carbon::now(),
            'deleted_at' => null,
        ]);
    }

    /**
     * 重置用户账号
     * @param $account_id
     * @param $uid
     * @param $username
     * @param $avatar
     * @return int
     */
    protected function resetAccount($account_id, $uid, $username, $avatar): int
    {
        return $this->where(['id' => $account_id])->update([
            'authorization_id' => AppHelper::getAuthorizationId(),
            'uid' => $uid,
            'username' => $username,
            'avatar' => $avatar,
            'state' => self::STATE_OPEN,
            'created_at' => Carbon::now(),
            'deleted_at' => null,
        ]);
    }

    /**
     * @param $account_id
     * @param $username
     * @param $avatar
     * @return int
     */
    protected function updateAccount($account_id, $username, $avatar): int
    {
        return $this->where(['id' => $account_id])->update([
            'username' => $username,
            'avatar' => $avatar,
        ]);
    }

    /**
     * @param $account_id
     * @return int
     */
    protected function deleteAccount($account_id): int
    {
        return $this->where(['id' => $account_id])->update([
            'state' => self::STATE_DELETE,
            'deleted_at' => Carbon::now(),
        ]);
    }

    /**
     * @param Account $account
     * @return array
     */
    protected function builderAccount(Account $account): array
    {
        return [
            'id' => $account->id,
            'authorization_id' => $account->authorization_id,
            'uid' => $account->uid,
            'username' => $account->username,
            'avatar' => $account->avatar,
            'created_at' => $account->created_at,
        ];
    }
}