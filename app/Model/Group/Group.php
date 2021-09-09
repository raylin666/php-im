<?php

declare (strict_types=1);
namespace App\Model\Group;

use Exception;
use App\Contract\RoomTypeInterface;
use App\Helpers\AppHelper;
use App\Helpers\CommonHelper;
use App\Model\Model;
use Carbon\Carbon;
use Hyperf\DbConnection\Db;

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
     * @param $group_id
     * @return int
     */
    protected function getGroupId($group_id): int
    {
        return intval($this->where(['id' => $group_id, 'authorization_id' => AppHelper::getAuthorizationId(), 'state' => self::STATE_OPEN])
            ->whereNull('deleted_at')
            ->value('id'));
    }

    /**
     * 获取群聊信息
     * @param $group_id
     * @return \Hyperf\Database\Model\Builder|\Hyperf\Database\Model\Model|object|null
     */
    protected function getGroupInfo($group_id, $account_id = 0)
    {
        $builder = $this->where(['id' => $group_id, 'authorization_id' => AppHelper::getAuthorizationId(), 'state' => self::STATE_OPEN]);

        if ($account_id) {
            $builder->where('account_id', $account_id);
        }

        return $builder->whereNull('deleted_at')->first();
    }

    /**
     * 创建群聊
     * @param     $account_id
     * @param     $name
     * @param     $cover
     * @param int $type
     * @return int
     */
    protected function createGroup($account_id, $name, $cover, $type = self::TYPE_PUBLIC): int
    {
        $ident = RoomTypeInterface::ROOM_TYPE_GROUP . CommonHelper::generateUniqid();

        Db::beginTransaction();

        try {
            $group_id = $this->insertGetId([
                'account_id' => $account_id,
                'authorization_id' => AppHelper::getAuthorizationId(),
                'ident' => $ident,
                'name' => $name,
                'cover' => $cover,
                'type' => $type,
                'created_at' => Carbon::now(),
            ]);

            if (! $group_id) {
                throw new Exception('Failed to create group chat');
            }

            // 将用户账号作为群主加入到群内
            if (GroupAccount::bindGroupAccountRelation($account_id, $group_id, GroupAccount::IDENTITY_HOST)) {
                Db::commit();
            }

            return $group_id;
        } catch (Exception $e) {
            Db::rollBack();
        }

        return 0;
    }

    /**
     * 修改群聊信息
     * @param $id
     * @param $name
     * @param $cover
     * @return int
     */
    protected function updateGroup($id, $name, $cover): int
    {
        return $this->where(['id' => $id])->update([
            'name' => $name,
            'cover' => $cover,
        ]);
    }

    /**
     * 删除群组
     * @param $id
     */
    protected function deleteGroup($id)
    {
        $this->where(['id' => $id])->update([
            'state' => self::STATE_DELETE,
            'deleted_at' => Carbon::now(),
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