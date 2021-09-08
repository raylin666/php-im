<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace App\Services\Account;

use App\Constants\HttpErrorCode;
use App\Helpers\AppHelper;
use App\Model\Group\Group;
use App\Services\Service;

/**
 * Class GroupService
 * @method static $this getInstance(...$args)
 * @package App\Services\Account
 */
class GroupService extends Service
{
    /**
     * 创建群聊
     * @param     $account_id
     * @param     $name
     * @param     $cover
     * @param int $type
     * @return array|mixed|void
     */
    public function create($account_id, $name, $cover, $type = Group::TYPE_PUBLIC)
    {
        AccountService::getInstance()->verifyAccountOrGet($account_id);
        $group_id = Group::createGroup($account_id, $name, $cover, $type);
        if ((! $group_id) || (! ($group = Group::getGroupInfo($group_id, $account_id)))) {
            return $this->response()->error(HttpErrorCode::GROUP_CREATE_ERROR);
        }

        return $this->response()->success(Group::builderGroupInfo($group));
    }

    /**
     * 获取群信息
     * @param $group_id
     * @return array|mixed|void
     */
    public function info($group_id)
    {
        if (! ($group = Group::getGroupInfo($group_id))) {
            return $this->response()->error(HttpErrorCode::GROUP_NOT_EXIST);
        }

        return $this->response()->success(Group::builderGroupInfo($group));
    }

    /**
     * 修改群信息
     * @param $account_id
     * @param $group_id
     * @param $name
     * @param $cover
     * @return array|mixed|void
     */
    public function update($account_id, $group_id, $name, $cover)
    {
        AccountService::getInstance()->verifyAccountOrGet($account_id);
        if (! ($group = Group::getGroupInfo($group_id, $account_id))) {
            return $this->response()->error(HttpErrorCode::GROUP_NOT_EXIST);
        }

        if (Group::updateGroup($group_id, $name, $cover)) {
            $group->name = $name;
            $group->cover = $cover;
        }

        return $this->response()->success(Group::builderGroupInfo($group));
    }

    /**
     * 申请加入群聊
     * @param        $group_id
     * @param        $account_id
     * @param string $remark
     * @return array|mixed|void
     */
    public function apply($group_id, $account_id, string $remark = '')
    {

    }

    /**
     * 确认添加好友
     * @param        $group_id
     * @param        $from_account_id
     * @return array|mixed|void
     */
    public function passed($group_id, $from_account_id)
    {

    }

    /**
     * 拒绝添加好友
     * @param $group_id
     * @param $from_account_id
     * @return array|mixed|void
     */
    public function rejected($group_id, $from_account_id)
    {

    }
}
