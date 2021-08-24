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

/**
 * 账号模块
 */
class AccountController extends AbstractController
{
    public function info()
    {

    }

    public function add(AccountRequest $request)
    {
        $validated = $request->validated();
        return $this->response->RESTfulAPI($validated);
    }

    public function edit()
    {

    }

    public function delete()
    {

    }
}
