<?php

declare (strict_types=1);
namespace App\Model\Group;

use App\Contract\RoomTypeInterface;
use App\Helpers\CommonHelper;
use App\Model\Model;
use Carbon\Carbon;

/**
 * @property int $id 
 * @property int $account_id
 * @property int $authorization_id
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
    protected $casts = ['id' => 'integer', 'account_id' => 'integer', 'authorization_id' => 'integer', 'type' => 'integer', 'state' => 'integer', 'created_at' => 'datetime'];

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
     * 群类型
     */
    // 普通
    const TYPE_PUBLIC = 0;
    // 讨论组
    const TYPE_DISCUSS = 1;

    /**
     * 获取群组 ID
     * @param $ident
     * @param $authorization_id
     * @return int
     */
    protected function getGroupId($ident, $authorization_id): int
    {
        return intval($this->where(['ident' => $ident, 'authorization_id' => $authorization_id, 'state' => self::STATE_OPEN])
            ->whereNull('deleted_at')
            ->value('id'));
    }

    /**
     * 获取群聊信息
     * @param $group_id
     * @param $authorization_id
     * @return \Hyperf\Database\Model\Builder|\Hyperf\Database\Model\Model|object|null
     */
    protected function getGroupInfo($group_id, $authorization_id)
    {
        return $this->where(['id' => $group_id, 'authorization_id' => $authorization_id, 'state' => self::STATE_OPEN])
            ->whereNull('deleted_at')
            ->first();
    }

    /**
     * 创建群聊
     * @param     $account_id
     * @param     $authorization_id
     * @param     $name
     * @param     $cover
     * @param int $type
     * @return int
     */
    protected function createGroup($account_id, $authorization_id, $name, $cover, $type = self::TYPE_PUBLIC): int
    {
        $ident = RoomTypeInterface::ROOM_TYPE_GROUP . CommonHelper::generateUniqid();
        return $this->insertGetId([
            'account_id' => $account_id,
            'authorization_id' => $authorization_id,
            'ident' => $ident,
            'name' => $name,
            'cover' => $cover,
            'type' => $type,
            'created_at' => Carbon::now(),
        ]);
    }

    /**
     * @param Group $group
     * @return array
     */
    protected function builderGroupInfo(Group $group)
    {
        return [
            'group_id' => $group->id,
            'account_id' => $group->account_id,
            'authorization_id' => $group->authorization_id,
            'ident' => $group->ident,
            'name' => $group->name,
            'cover' => $group->cover,
            'type' => $group->type,
            'created_at' => $group->created_at,
        ];
    }
}