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
use App\Model\Account\Account;
use App\Model\Account\AccountAuthorization;
use App\Services\Service;
use Carbon\Carbon;

/**
 * Class Service
 * @method static $this getInstance(...$args)
 * @package App\Services\Account
 */
class AccountService extends Service
{
    /**
     * 获取用户账号 Token
     * @param $account_id
     * @return array|mixed|void
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function accountToken($account_id)
    {
        $authorization_id = AppHelper::getAuthorizationId();
        if (! Account::getAccountId($authorization_id, $account_id)) {
            return $this->response()->error(HttpErrorCode::ACCOUNT_NOT_AVAILABLE);
        }

        $account_authorization_func = function ($account_id, $authorization_id) {
            return AccountAuthorization::getAccountAuthorization($account_id, $authorization_id);
        };

        $account_authorization = $account_authorization_func($account_id, $authorization_id);
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
                AccountAuthorization::updateTokenData($account_id, $authorization_id);
                $account_authorization = $account_authorization_func($account_id, $authorization_id);
            }
        } else {
            // 生成用户账号授权数据
            AccountAuthorization::createData($account_id, $authorization_id);
            $account_authorization = $account_authorization_func($account_id, $authorization_id);
        }

        return $this->response()->success([
            'id' => $account_authorization['id'],
            'account_id' => $account_authorization['account_id'],
            'authorization_id' => $account_authorization['authorization_id'],
            'token' => $account_authorization['token'],
            'expired_at' => $account_authorization['expired_at'],
            'refresh_at' => $account_authorization['refresh_at'],
        ]);
    }

    /**
     * 添加用户账号
     * @param array $data
     * @return array|mixed|void
     */
    public function add(array $data)
    {
        $is_add = false;
        $authorization_id = AppHelper::getAuthorizationId();
        if ($account_id = Account::getUidByAccountId($data['uid'], $authorization_id)) {
            if (Account::isAccountAvailable($account_id)) {
                // 提示账号已存在
                return $this->response()->error(HttpErrorCode::ACCOUNT_ALREADY_EXISTS);
            } else {
                // 更新已被废弃的账号信息
                if (! Account::resetAccount($account_id, $authorization_id, $data['uid'], $data['username'], $data['avatar'])) {
                    return $this->response()->error(HttpErrorCode::ACCOUNT_ADD_ERROR);
                }

                $is_add = true;
            }
        }

        // 新增账号信息
        if ((! $is_add) && (! Account::addAccount($authorization_id, $data['uid'], $data['username'], $data['avatar']))) {
            return $this->response()->error(HttpErrorCode::ACCOUNT_ADD_ERROR);
        }

        return $this->response()->success();
    }
}
