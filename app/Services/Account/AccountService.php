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
use App\Model\Account\Account;
use App\Model\Account\AccountAuthorization;
use App\Services\Service;
use Carbon\Carbon;

/**
 * Class AccountService
 * @method static $this getInstance(...$args)
 * @package App\Services\Account
 */
class AccountService extends Service
{
    /**
     * 获取用户账号信息
     * @param $account_id
     * @return array|void
     */
    public function info($account_id)
    {
        $account = $this->verifyAccountOrGet($account_id);
        return $this->response()->success(Account::builderAccount($account));
    }

    /**
     * 获取用户账号 Token
     * @param $account_id
     * @return array|mixed|void
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function accountToken($account_id)
    {
        $account = $this->verifyAccountOrGet($account_id);

        $account_authorization_func = function ($account_id) {
            return AccountAuthorization::getAccountAuthorization($account_id);
        };

        $account_authorization = $account_authorization_func($account_id);
        // 判断是否有用户账号授权
        if ($account_authorization) {
            $is_update = false;
            // 判断是否失效, 失效则重新生成
            if ($account_authorization['expired_at'] <= Carbon::now()
                || $account_authorization['deleted_at']
            ) {
                $is_update = true;
            }

            // 更新 Token
            if ($is_update) {
                AccountAuthorization::updateTokenData($account_id);
                $account_authorization = $account_authorization_func($account_id);
            }
        } else {
            // 生成用户账号授权数据
            AccountAuthorization::addAccountAuthorization($account_id);
            $account_authorization = $account_authorization_func($account_id);
        }

        return $this->response()->success(
            array_merge(
                AccountAuthorization::builderAccountAuthorization($account_authorization),
                [
                    'account' => Account::builderAccount($account),
                ]
            )
        );
    }

    /**
     * 创建用户账号
     * @param array $data
     * @return array|mixed|void
     */
    public function create(array $data)
    {
        $data['uid'] = intval($data['uid']);

        $is_add = false;
        if ($account_id = Account::getUidByAccountId($data['uid'])) {
            if (Account::isAccountAvailable($account_id)) {
                // 提示账号已存在
                return $this->response()->error(HttpErrorCode::ACCOUNT_ALREADY_EXISTS);
            } else {
                // 更新已被废弃的账号信息
                if (! Account::resetAccount($account_id, $data['uid'], $data['username'], $data['avatar'])) {
                    return $this->response()->error(HttpErrorCode::ACCOUNT_ADD_ERROR);
                }

                $is_add = true;
            }
        }

        // 新增账号信息
        if ((! $is_add) && (! ($account_id = Account::addAccount($data['uid'], $data['username'], $data['avatar'])))) {
            return $this->response()->error(HttpErrorCode::ACCOUNT_ADD_ERROR);
        }

        return $this->response()->success([
            'id' => $account_id,
            'uid' => $data['uid'],
            'username' => $data['username'],
            'avatar' => $data['avatar'],
        ]);
    }

    /**
     * 修改用户账号
     * @param       $account_id
     * @param array $data
     * @return array|mixed|void
     */
    public function update($account_id, array $data)
    {
        $account = $this->verifyAccountOrGet($account_id);
        // 必须保证修改的数据 在业务层内 uid 相同
        if ($account['uid'] != $data['uid']) {
            return $this->response()->error(HttpErrorCode::ACCOUNT_UID_CANNOT_BE_MODIFIED);
        }

        Account::updateAccount($account_id, $data['username'], $data['avatar']);

        return $this->response()->success();
    }

    /**
     * 删除用户账号
     * @param $account_id
     * @return array|mixed|void
     */
    public function delete($account_id)
    {
        $this->verifyAccountOrGet($account_id);
        Account::deleteAccount($account_id);
        AccountAuthorization::deleteAccountAuthorization($account_id);
        return $this->response()->success();
    }
}
