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

use Exception;
use App\Constants\HttpErrorCode;
use App\Model\Account\Account;
use App\Model\Friend\AccountFriend;
use App\Model\Friend\AccountFriendApply;
use App\Services\Service;
use Hyperf\DbConnection\Db;

/**
 * Class FriendService
 * @method static $this getInstance(...$args)
 * @package App\Services\Account
 */
class FriendService extends Service
{
    /**
     * 申请添加好友
     * @param        $account_id
     * @param        $to_account_id
     * @param string $remark
     * @return array|mixed|void
     */
    public function apply($account_id, $to_account_id, string $remark = '')
    {
        if ($account_id == $to_account_id) {
            return $this->response()->error(HttpErrorCode::TO_ACCOUNT_JOIN_FRIEND_NOT_ME);
        }

        // 对方用户账号是否可用
        if (! Account::isAccountAvailable($to_account_id)) {
            return $this->response()->error(HttpErrorCode::ACCOUNT_NOT_EXIST);
        }

        // 判断是否好友关系
        if (AccountFriend::isBetoFriendRelation($account_id, $to_account_id)) {
            return $this->response()->error(HttpErrorCode::TO_ACCOUNT_IS_FRIEND);
        }

        // 判断是否已有未确认的消息
        if (AccountFriendApply::isExistBeConfirm($account_id, $to_account_id)) {
            return $this->response()->error(HttpErrorCode::TO_ACCOUNT_JOIN_FRIEND_BE_CONFIRM);
        }

        // 写入申请好友消息
        if (! ($apply_id = AccountFriendApply::addAccountFriendApply($account_id, $to_account_id, $remark))) {
            return $this->response()->error(HttpErrorCode::TO_ACCOUNT_JOIN_FRIEND_ERROR);
        }

        return $this->response()->success([
            'apply_id' => $apply_id,
        ]);
    }

    /**
     * 确认添加好友
     * @param        $account_id
     * @param        $from_account_id
     * @param string $remark
     * @return array|mixed|void
     */
    public function passed($account_id, $from_account_id, string $remark = '')
    {
        $is_friend = false;

        if ($account_id == $from_account_id) {
            return $this->response()->error(HttpErrorCode::TO_ACCOUNT_JOIN_FRIEND_NOT_ME);
        }

        // 对方用户账号是否可用
        if (! Account::isAccountAvailable($from_account_id)) {
            return $this->response()->error(HttpErrorCode::ACCOUNT_OTHER_NOT_AVAILABLE);
        }

        // 获取未确认的消息
        if (! ($apply = AccountFriendApply::getBeConfirm($from_account_id, $account_id))) {
            return $this->response()->error(HttpErrorCode::TO_ACCOUNT_NOT_JOIN_FRIEND);
        }

        // 判断是否好友关系
        if (AccountFriend::isBetoFriendRelation($account_id, $from_account_id)) {
            $is_friend = true;
        }

        Db::beginTransaction();

        try {
            // 通过好友申请
            if (! AccountFriendApply::passedAccountFriendApply($apply['id'])) {
                throw new Exception('Failed to apply through friends');
            }

            if (! $is_friend) {
                // 好友关系绑定
                if (! AccountFriend::bindFriendRelation($from_account_id, $account_id, $remark)) {
                    throw new Exception('Friend relationship binding failed');
                }
            }

            Db::commit();

        } catch (Exception $e) {
            Db::rollBack();
        }

        return $this->response()->success();
    }

    /**
     * 拒绝添加好友
     * @param $account_id
     * @param $from_account_id
     * @return array|mixed|void
     */
    public function rejected($account_id, $from_account_id)
    {
        // 获取未确认的消息
        if (! ($apply = AccountFriendApply::getBeConfirm($from_account_id, $account_id))) {
            return $this->response()->error(HttpErrorCode::TO_ACCOUNT_NOT_JOIN_FRIEND);
        }

        AccountFriendApply::rejectedAccountFriendApply($apply['id']);
        return $this->response()->success();
    }
}
