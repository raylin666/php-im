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
    public function info()
    {

    }

    /**
     * 添加账号
     * @param AccountRequest $request
     * @return mixed
     */
    public function add(AccountRequest $request)
    {
        $data = $request->validated();

        return $this->response->RESTfulAPI(
            AccountService::getInstance()->add($data)
        );
    }

    public function edit()
    {

    }

    public function delete()
    {

    }
}
