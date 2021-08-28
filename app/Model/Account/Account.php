<?php

declare (strict_types=1);
namespace App\Model\Account;

use App\Model\Model;

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
    protected $casts = ['id' => 'integer', 'authorization_id' => 'integer', 'state' => 'integer', 'created_at' => 'datetime'];

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
}