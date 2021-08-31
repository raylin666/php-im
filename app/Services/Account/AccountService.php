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
use App\Services\Service;

/**
 * Class Service
 * @method static $this getInstance(...$args)
 * @package App\Services\Account
 */
class AccountService extends Service
{
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
        if ((! $is_add) && Account::addAccount($authorization_id, $data['uid'], $data['username'], $data['avatar'])) {
            return $this->response()->error(HttpErrorCode::ACCOUNT_ADD_ERROR);
        }

        return $this->response()->success();
    }
}
