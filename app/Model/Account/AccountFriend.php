<?php

declare (strict_types=1);
namespace App\Model\Account;

use App\Model\Model;

/**
 * @property int $id 
 * @property int $account_id 
 * @property int $to_account_id 
 * @property string $to_account_remark 
 * @property int $state 
 * @property \Carbon\Carbon $created_at 
 * @property string $deleted_at 
 */
class AccountFriend extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'account_friend';
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
    protected $casts = ['id' => 'integer', 'account_id' => 'integer', 'to_account_id' => 'integer', 'state' => 'integer', 'created_at' => 'datetime'];

    /**
     * @var string[]
     */
    protected $hidden = ['deleted_at'];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * 好友关系状态
     */
    // 关闭
    const STATE_CLOSE = 0;
    // 开启
    const STATE_OPEN = 1;
    // 删除
    const STATE_DELETE = 2;

    /**
     * 是否我的好友
     * @param $account_id
     * @param $to_account_id
     * @return bool
     */
    protected function isMyFriend($account_id, $to_account_id): bool
    {
        return $this->where(['account_id' => $account_id, 'to_account_id' => $to_account_id, 'state' => self::STATE_OPEN])
            ->exists();
    }

    /**
     * 是否都是双向好友关系(因为一方可以删除另一方,则不属于双向好友关系)
     * @param $account_id
     * @param $to_account_id
     * @return bool
     */
    protected function isBetoFriendRelation($account_id, $to_account_id): bool
    {
        return $this->where(function ($query) use ($account_id, $to_account_id) {
                $query->where(['account_id' => $account_id, 'to_account_id' => $to_account_id])
                    ->orWhere(['account_id' => $to_account_id, 'to_account_id' => $account_id]);
            })
            ->where('state', self::STATE_OPEN)
            ->count() > 1;
    }
}