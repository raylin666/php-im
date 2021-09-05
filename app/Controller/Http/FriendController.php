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
use App\Request\Friend\ApplyFriendRequest;
use App\Request\Friend\PassedFriendRequest;
use App\Services\Account\FriendService;

/**
 * 好友模块
 */
class FriendController extends AbstractController
{
    /**
     * 申请添加好友
     * @param ApplyFriendRequest $request
     * @param                    $account_id
     * @return mixed
     */
    public function apply(ApplyFriendRequest $request, $account_id)
    {
        $data = $request->validated();

        return $this->response->RESTfulAPI(
            FriendService::getInstance()->apply($account_id, $data['to_account_id'], $data['remark'])
        );
    }

    /**
     * 确认添加好友
     * @param $account_id
     * @return mixed
     */
    public function passed(PassedFriendRequest $request, $account_id)
    {
        $data = $request->validated();

        return $this->response->RESTfulAPI(
            FriendService::getInstance()->passed($account_id, $data['from_account_id'], $data['remark'])
        );
    }

    /**
     * 拒绝添加好友
     * @param $account_id
     * @return mixed
     */
    public function rejected($account_id)
    {
        return $this->response->RESTfulAPI(
            FriendService::getInstance()->rejected($account_id)
        );
    }
}
