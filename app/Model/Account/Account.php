<?php

declare (strict_types=1);
namespace App\Model\Account;

use App\Model\Model;
use Carbon\Carbon;

/**
 * @property int $id 
 * @property int $authorization_id 
 * @property string $username 
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
     * @param     $authorization_id
     * @param int $account_id
     * @return int
     */
    protected function getAccountId($authorization_id, $account_id = 0): int
    {
        $builder = $this;

        if ($account_id) {
            $builder->where('id', $account_id);
        }

        return intval($builder->where(['authorization_id' => $authorization_id, 'state' => self::STATE_OPEN])
            ->value('id'));
    }

    /**
     * 通过应用ID 获取账号ID
     * @param $uid
     * @param $authorization_id
     * @return int
     */
    protected function getUidByAccountId($uid, $authorization_id): int
    {
        return intval($this->where(['uid' => $uid, 'authorization_id' => $authorization_id])
            ->value('id'));
    }

    /**
     * 账号是否可用
     * @param $account_id
     * @return bool
     */
    protected function isAccountAvailable($account_id): bool
    {
        return $this->where(['id' => $account_id, 'state' => self::STATE_OPEN])->exists();
    }

    /**
     * 添加用户账号
     * @param $authorization_id
     * @param $uid
     * @param $username
     * @param $avatar
     * @return bool
     */
    protected function addAccount($authorization_id, $uid, $username, $avatar): bool
    {
        return $this->insert([
            'authorization_id' => $authorization_id,
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
     * @param $authorization_id
     * @param $uid
     * @param $username
     * @param $avatar
     * @return int
     */
    protected function resetAccount($account_id, $authorization_id, $uid, $username, $avatar): int
    {
        return $this->where(['id' => $account_id])->update([
            'authorization_id' => $authorization_id,
            'uid' => $uid,
            'username' => $username,
            'avatar' => $avatar,
            'state' => self::STATE_OPEN,
            'created_at' => Carbon::now(),
            'deleted_at' => null,
        ]);
    }
}