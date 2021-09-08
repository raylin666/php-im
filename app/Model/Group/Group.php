<?php

declare (strict_types=1);
namespace App\Model\Group;

use App\Model\Model;

/**
 * @property int $id 
 * @property int $account_id 
 * @property string $ident 
 * @property string $name 
 * @property string $cover 
 * @property int $type 
 * @property int $state 
 * @property \Carbon\Carbon $created_at 
 * @property string $deleted_at 
 */
class Group extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'group';
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
    protected $casts = ['id' => 'integer', 'account_id' => 'integer', 'type' => 'integer', 'state' => 'integer', 'created_at' => 'datetime'];

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
     * 获取群组 ID
     * @param $ident
     * @return int
     */
    protected function getGroupId($ident): int
    {
        return intval($this->where(['ident' => $ident, 'state' => self::STATE_OPEN])
            ->whereNull('deleted_at')
            ->value('id'));
    }
}