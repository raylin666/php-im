<?php

declare (strict_types=1);
namespace App\Model\Group;

use App\Model\Model;

/**
 * @property int $id 
 * @property int $group_id 
 * @property int $account_id 
 * @property string $to_account_remark 
 * @property string $group_remark 
 * @property int $identity 
 * @property int $state 
 * @property \Carbon\Carbon $created_at 
 * @property string $deleted_at 
 */
class GroupAccount extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'group_account';
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
    protected $casts = ['id' => 'integer', 'group_id' => 'integer', 'account_id' => 'integer', 'identity' => 'integer', 'state' => 'integer', 'created_at' => 'datetime'];

    /**
     * @var string[]
     */
    protected $hidden = ['deleted_at'];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * 状态
     */
    // 关闭
    const STATE_CLOSE = 0;
    // 开启
    const STATE_OPEN = 1;
    // 删除
    const STATE_DELETE = 2;

    /**
     * 用户账号是否群成员
     * @param $account_id
     * @param $group_id
     * @return bool
     */
    protected function isGroupAccount($account_id, $group_id): bool
    {
        return $this->where(['account_id' => $account_id, 'group_id' => $group_id, 'state' => self::STATE_OPEN])
            ->whereNull('deleted_at')
            ->exists();
    }
}