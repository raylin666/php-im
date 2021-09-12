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

use App\Model\Account\AccountAuthorization;
use Exception;
use App\Constants\HttpErrorCode;
use App\Model\Account\Account;
use App\Model\Group\Group;
use App\Model\Group\GroupAccount;
use App\Model\Group\GroupAccountApply;
use App\Services\Service;
use Hyperf\DbConnection\Db;

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
        $this->verifyAccountOrGet($account_id);
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
     * 获取群成员列表
     * @param     $group_id
     * @param int $page
     * @param int $size
     * @return array|mixed|void
     */
    public function accountList($group_id, $page = 1, $size = 30)
    {
        if (! Group::getGroupInfo($group_id)) {
            return $this->response()->error(HttpErrorCode::GROUP_NOT_EXIST);
        }

        $list = GroupAccount::getAccountList($group_id, $page, $size);
        foreach ($list as &$item) {
            // 判断群成员是否在线
            $item['is_online'] = AccountAuthorization::isOnline($item['account_id']);
        }

        return $this->response()->success($list);
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
        $this->verifyAccountOrGet($account_id);
        if (! ($group = Group::getGroupInfo($group_id))) {
            return $this->response()->error(HttpErrorCode::GROUP_NOT_EXIST);
        }

        // 判断是否有权限操作
        if (! GroupAccount::isGroupAccountIdentityHostOrAdmin($account_id, $group_id)) {
            return $this->response()->error(HttpErrorCode::GROUP_ACCOUNT_NOT_OPERATED_AUTH);
        }

        if (Group::updateGroup($group_id, $name, $cover)) {
            $group->name = $name;
            $group->cover = $cover;
        }

        return $this->response()->success(Group::builderGroupInfo($group));
    }

    /**
     * 解散群聊
     * @param $group_id
     * @param $account_id
     * @return array|mixed|void
     */
    public function delete($group_id, $account_id)
    {
        $this->verifyAccountOrGet($account_id);

        // 判断是否有权限操作
        if (! GroupAccount::isGroupAccountIdentityHost($account_id, $group_id)) {
            return $this->response()->error(HttpErrorCode::GROUP_ACCOUNT_NOT_OPERATED_AUTH);
        }

        // 判断群聊是否可用
        if (! Group::getGroupId($group_id)) {
            return $this->response()->error(HttpErrorCode::GROUP_NOT_EXIST);
        }

        // 移除所有群内成员
        GroupAccount::removeAllGroupAccount($group_id);

        // 删除群组
        Group::deleteGroup($group_id);

        return $this->response()->success();
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
        $this->verifyAccountOrGet($account_id);

        // 判断群聊是否可用
        if (! Group::getGroupId($group_id)) {
            return $this->response()->error(HttpErrorCode::GROUP_NOT_EXIST);
        }

        // 判断用户是否在群内
        if (GroupAccount::isGroupAccount($account_id, $group_id)) {
            return $this->response()->error(HttpErrorCode::GROUP_ACCOUNT_ALREADY_EXIST);
        }

        // 判断是否已有未确认的入群消息
        if (GroupAccountApply::isExistBeConfirm($account_id, $group_id)) {
            return $this->response()->error(HttpErrorCode::GROUP_ACCOUNT_JOIN_BE_CONFIRM);
        }

        // 写入入群消息
        if (! ($apply_id = GroupAccountApply::addGroupAccountApply($account_id, $group_id, $remark))) {
            return $this->response()->error(HttpErrorCode::GROUP_ACCOUNT_JOIN_ERROR);
        }

        return $this->response()->success([
            'apply_id' => $apply_id,
        ]);
    }

    /**
     * 确认加入群聊
     * @param        $group_id
     * @param        $from_account_id
     * @param        $operated_account_id
     * @return array|mixed|void
     */
    public function passed($group_id, $from_account_id, $operated_account_id)
    {
        $is_join = false;

        // 判断操作人是否有权限操作
        if (! GroupAccount::isGroupAccountIdentityHostOrAdmin($operated_account_id, $group_id)) {
            return $this->response()->error(HttpErrorCode::GROUP_ACCOUNT_NOT_OPERATED_AUTH);
        }

        // 对方用户账号是否可用
        if (! Account::isAccountAvailable($from_account_id)) {
            $this->rejected($group_id, $from_account_id);
            return $this->response()->error(HttpErrorCode::ACCOUNT_OTHER_NOT_AVAILABLE);
        }

        // 获取未确认的消息
        if (! ($apply = GroupAccountApply::getBeConfirm($from_account_id, $group_id))) {
            return $this->response()->error(HttpErrorCode::GROUP_ACCOUNT_JOIN_FRIEND);
        }

        // 判断用户是否在群内
        if (GroupAccount::isGroupAccount($from_account_id, $group_id)) {
            $is_join = true;
        }

        Db::beginTransaction();

        try {
            // 通过入群申请
            if (! GroupAccountApply::passedGroupAccountApply($apply['id'], $operated_account_id)) {
                throw new Exception('Failed to apply for joining the group');
            }

            if (! $is_join) {
                // 群成员绑定
                if (! GroupAccount::bindGroupAccountRelation($from_account_id, $group_id)) {
                    throw new Exception('Group membership binding failed');
                }
            }

            Db::commit();

        } catch (Exception $e) {
            Db::rollBack();
        }

        return $this->response()->success();
    }

    /**
     * 拒绝加入群聊
     * @param $group_id
     * @param $from_account_id
     * @param $operated_account_id
     * @return array|mixed|void
     */
    public function rejected($group_id, $from_account_id, $operated_account_id)
    {
        // 获取未确认的消息
        if ($apply = GroupAccountApply::getBeConfirm($from_account_id, $group_id)) {
            GroupAccountApply::rejectedGroupAccountApply($apply['id'], $operated_account_id);
        }

        return $this->response()->success();
    }

    /**
     * 退出群聊
     * @param $account_id
     * @param $group_id
     * @return array|mixed|void
     */
    public function quit($account_id, $group_id)
    {
        $this->verifyAccountOrGet($account_id);

        // 判断用户是否在群内
        if (! GroupAccount::isGroupAccount($account_id, $group_id)) {
            return $this->response()->error(HttpErrorCode::GROUP_ACCOUNT_NOT_EXIST);
        }

        // 判断用户是否群主,不允许退群
        if (! GroupAccount::isGroupAccountIdentityHost($account_id, $group_id)) {
            return $this->response()->error(HttpErrorCode::GROUP_ACCOUNT_IS_HOST_NOT_QUIT);
        }

        // 退群操作
        GroupAccount::unbindGroupAccountRelation($account_id, $group_id);

        return $this->response()->success();
    }
}
