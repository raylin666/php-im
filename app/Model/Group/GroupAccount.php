<?php

declare (strict_types=1);
namespace App\Model\Group;

use App\Model\Model;
use Carbon\Carbon;

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
     * 群成员身份
     */
    // 普通
    const IDENTITY_PUBLIC = 0;
    // 管理员
    const IDENTITY_ADMIN = 1;
    // 群主
    const IDENTITY_HOST = 2;

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

    /**
     * 群成员是否群主或管理员
     * @param $account_id
     * @param $group_id
     * @return bool
     */
    protected function isGroupAccountIdentityHostOrAdmin($account_id, $group_id): bool
    {
        return $this->where(['account_id' => $account_id, 'group_id' => $group_id, 'state' => self::STATE_OPEN])
            ->whereIn('identity', [self::IDENTITY_ADMIN, self::IDENTITY_HOST])
            ->whereNull('deleted_at')
            ->exists();
    }

    /**
     * 群成员是否群主
     * @param $account_id
     * @param $group_id
     * @return bool
     */
    protected function isGroupAccountIdentityHost($account_id, $group_id): bool
    {
        return $this->where(['account_id' => $account_id, 'group_id' => $group_id, 'identity' => self::IDENTITY_HOST, 'state' => self::STATE_OPEN])
            ->whereNull('deleted_at')
            ->exists();
    }

    /**
     * 群成员是否管理员
     * @param $account_id
     * @param $group_id
     * @return bool
     */
    protected function isGroupAccountIdentityAdmin($account_id, $group_id): bool
    {
        return $this->where(['account_id' => $account_id, 'group_id' => $group_id, 'identity' => self::IDENTITY_ADMIN, 'state' => self::STATE_OPEN])
            ->whereNull('deleted_at')
            ->exists();
    }

    /**
     * 群成员是否普通身份
     * @param $account_id
     * @param $group_id
     * @return bool
     */
    protected function isGroupAccountIdentityPublic($account_id, $group_id): bool
    {
        return $this->where(['account_id' => $account_id, 'group_id' => $group_id, 'identity' => self::IDENTITY_PUBLIC, 'state' => self::STATE_OPEN])
            ->whereNull('deleted_at')
            ->exists();
    }

    /**
     * 群成员绑定
     * @param     $account_id
     * @param     $group_id
     * @param int $identity
     * @return bool
     */
    protected function bindGroupAccountRelation($account_id, $group_id, $identity = self::IDENTITY_PUBLIC): bool
    {
        if (! in_array($identity, [
            self::IDENTITY_HOST,
            self::IDENTITY_ADMIN,
            self::IDENTITY_PUBLIC
        ])) {
            $identity = self::IDENTITY_PUBLIC;
        }

        $this->updateOrInsert(
            [
                'account_id' => $account_id,
                'group_id' => $group_id,
            ],
            [
                'account_id' => $account_id,
                'group_id' => $group_id,
                'identity' => $identity,
                'state' => self::STATE_OPEN,
                'created_at' => Carbon::now(),
                'deleted_at' => null,
            ]
        );

        return true;
    }

    /**
     * 解除群成员绑定
     * @param $account_id
     * @param $group_id
     * @return bool
     */
    protected function unbindGroupAccountRelation($account_id, $group_id): bool
    {
        $this->where(['account_id' => $account_id, 'group_id' => $group_id])
            ->update([
                'state' => self::STATE_DELETE,
                'deleted_at' => Carbon::now(),
            ]);
    }

    /**
     * 移除群聊所有成员
     * @param $group_id
     */
    protected function removeAllGroupAccount($group_id)
    {
        $this->where(['group_id' => $group_id, 'state' => self::STATE_OPEN])
            ->update([
                'state' => self::STATE_DELETE,
                'deleted_at' => Carbon::now(),
            ]);
    }

    /**
     * 获取群成员列表
     * @param     $group_id
     * @param int $page
     * @param int $size
     * @return \Hyperf\Contract\LengthAwarePaginatorInterface
     */
    protected function getAccountList($group_id, $page = 1, $size = 30)
    {
        $builder = $this->where(['group_id' => $group_id, 'state' => self::STATE_OPEN])
            ->orderByDesc('identity');

        return $builder->paginate($size, ['*'], 'page', $page);
    }
}