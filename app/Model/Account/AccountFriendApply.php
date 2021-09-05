<?php

declare (strict_types=1);
namespace App\Model\Account;

use App\Model\Model;
use Carbon\Carbon;

/**
 * @property int $id 
 * @property int $account_id 
 * @property int $to_account_id 
 * @property string $to_account_remark 
 * @property int $state 
 * @property \Carbon\Carbon $created_at 
 * @property string $operated_at 
 */
class AccountFriendApply extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'account_friend_apply';
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
     * @var bool
     */
    public $timestamps = false;

    /**
     * 审核状态
     */
    // 待确认
    const STATE_BE_CONFIRM = 0;
    // 已通过
    const STATE_PASSED = 1;
    // 已拒绝
    const STATE_REJECTED = 2;

    /**
     * 判断是否已存在待确认的好友申请消息
     * @param $from_account_id
     * @param $to_account_id
     * @return bool
     */
    protected function isExistBeConfirm($from_account_id, $to_account_id): bool
    {
        return $this->where(['account_id' => $from_account_id, 'to_account_id' => $to_account_id, 'state' => self::STATE_BE_CONFIRM])
            ->exists();
    }

    /**
     * 获取未确认的消息
     * @param $from_account_id
     * @param $to_account_id
     * @return \Hyperf\Database\Model\Builder|\Hyperf\Database\Model\Model|object|null
     */
    protected function getBeConfirm($from_account_id, $to_account_id)
    {
        return $this->where(['account_id' => $from_account_id, 'to_account_id' => $to_account_id, 'state' => self::STATE_BE_CONFIRM])
            ->first();
    }

    /**
     * @param           $from_account_id
     * @param           $to_account_id
     * @param string    $to_account_remark
     * @return int
     */
    protected function addAccountFriendApply($from_account_id, $to_account_id, string $to_account_remark = ''): int
    {
        return $this->insertGetId([
            'account_id' => $from_account_id,
            'to_account_id' => $to_account_id,
            'to_account_remark' => $to_account_remark,
            'created_at' => Carbon::now(),
        ]);
    }

    /**
     * 通过好友申请
     * @param $apply_id
     * @return bool
     */
    protected function passedAccountFriendApply($apply_id): bool
    {
        return boolval($this->where('id', $apply_id)->update([
            'state' => self::STATE_PASSED,
            'operated_at' => Carbon::now(),
        ]));
    }
}