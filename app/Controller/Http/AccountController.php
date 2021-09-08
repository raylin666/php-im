<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace App\Controller\Http;

use App\Controller\AbstractController;
use App\Request\Account\AccountRequest;
use App\Services\Account\AccountService;

/**
 * 账号模块
 */
class AccountController extends AbstractController
{
    /**
     * 获取用户账号信息
     * @param $account_id
     * @return mixed
     */
    public function info($account_id)
    {
        return $this->response->RESTfulAPI(
            AccountService::getInstance()->info($account_id)
        );
    }

    /**
     * 获取用户账号 Token
     * @param $account_id
     * @return mixed
     */
    public function accountToken($account_id)
    {
        return $this->response->RESTfulAPI(
            AccountService::getInstance()->accountToken($account_id)
        );
    }

    /**
     * 创建用户账号
     * @param AccountRequest $request
     * @return mixed
     */
    public function create(AccountRequest $request)
    {
        $data = $request->validated();

        return $this->response->RESTfulAPI(
            AccountService::getInstance()->create($data)
        );
    }

    /**
     * 修改用户账号
     * @param                $account_id
     * @param AccountRequest $request
     * @return mixed
     */
    public function update($account_id, AccountRequest $request)
    {
        $data = $request->validated();

        return $this->response->RESTfulAPI(
            AccountService::getInstance()->update($account_id, $data)
        );
    }

    /**
     * 删除用户账号
     * @param $account_id
     * @return mixed
     */
    public function delete($account_id)
    {
        return $this->response->RESTfulAPI(
            AccountService::getInstance()->delete($account_id)
        );
    }
}
