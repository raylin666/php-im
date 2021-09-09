<?php

declare (strict_types=1);
namespace App\Model\Group;

use App\Model\Model;
use Carbon\Carbon;

/**
 * @property int $id 
 * @property int $group_id 
 * @property int $account_id 
 * @property int $operated_account_id 
 * @property string $apply_remark 
 * @property int $state 
 * @property \Carbon\Carbon $created_at 
 * @property string $operated_at 
 */
class GroupAccountApply extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'group_account_apply';
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
    protected $casts = ['id' => 'integer', 'group_id' => 'integer', 'account_id' => 'integer', 'operated_account_id' => 'integer', 'state' => 'integer', 'created_at' => 'datetime'];
    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * 审核状态
     */
    // 待审核
    const STATE_BE_CONFIRM = 0;
    // 已通过
    const STATE_PASSED = 1;
    // 已拒绝
    const STATE_REJECTED = 2;

    /**
     * 判断是否已存在待审核的入群申请消息
     * @param $account_id
     * @param $group_id
     * @return bool
     */
    protected function isExistBeConfirm($account_id, $group_id): bool
    {
        return $this->where(['account_id' => $account_id, 'group_id' => $group_id, 'state' => self::STATE_BE_CONFIRM])
            ->exists();
    }

    /**
     * 获取待审核的入群消息
     * @param $account_id
     * @param $group_id
     * @return \Hyperf\Database\Model\Builder|\Hyperf\Database\Model\Model|object|null
     */
    protected function getBeConfirm($account_id, $group_id)
    {
        return $this->where(['account_id' => $account_id, 'group_id' => $group_id, 'state' => self::STATE_BE_CONFIRM])
            ->first();
    }

    /**
     * @param        $account_id
     * @param        $group_id
     * @param string $apply_remark
     * @return int
     */
    protected function addGroupAccountApply($account_id, $group_id, string $apply_remark = ''): int
    {
        return $this->insertGetId([
            'account_id' => $account_id,
            'group_id' => $group_id,
            'apply_remark' => $apply_remark,
            'created_at' => Carbon::now(),
        ]);
    }

    /**
     * 通过入群申请
     * @param $apply_id
     * @param $operated_account_id
     * @return bool
     */
    protected function passedGroupAccountApply($apply_id, $operated_account_id): bool
    {
        return boolval($this->where('id', $apply_id)->update([
            'state' => self::STATE_PASSED,
            'operated_account_id' => $operated_account_id,
            'operated_at' => Carbon::now(),
        ]));
    }

    /**
     * 拒绝好友申请
     * @param $apply_id
     * @param $operated_account_id
     * @return bool
     */
    protected function rejectedGroupAccountApply($apply_id, $operated_account_id): bool
    {
        return boolval($this->where('id', $apply_id)->update([
            'state' => self::STATE_REJECTED,
            'operated_account_id' => $operated_account_id,
            'operated_at' => Carbon::now(),
        ]));
    }
}