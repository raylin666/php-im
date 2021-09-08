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
use App\Request\Group\ApplyRequest;
use App\Request\Group\CreateRequest;
use App\Request\Group\PassedRequest;
use App\Request\Group\RejectedRequest;
use App\Services\Account\GroupService;

/**
 * 群聊模块
 */
class GroupController extends AbstractController
{
    /**
     * 创建群聊
     * @param CreateRequest $request
     * @param              $group_id
     * @return mixed
     */
    public function create(CreateRequest $request, $account_id)
    {
        $data = $request->validated();
        $type = $data['type'] ?? 0;

        return $this->response->RESTfulAPI(
            GroupService::getInstance()->create($account_id, $data['name'], $data['cover'], intval($type))
        );
    }

    /**
     * 获取群信息
     * @param $group_id
     * @return mixed
     */
    public function info($group_id)
    {
        return $this->response->RESTfulAPI(
            GroupService::getInstance()->info($group_id)
        );
    }

    /**
     * 申请加入群聊
     * @param ApplyRequest $request
     * @param              $group_id
     * @return mixed
     */
    public function apply(ApplyRequest $request, $group_id)
    {
        $data = $request->validated();

        return $this->response->RESTfulAPI(
            GroupService::getInstance()->apply($group_id, $data['account_id'], $data['remark'])
        );
    }

    /**
     * 确认加入群聊
     * @param PassedRequest $request
     * @param               $group_id
     * @return mixed
     */
    public function passed(PassedRequest $request, $group_id)
    {
        $data = $request->validated();

        return $this->response->RESTfulAPI(
            GroupService::getInstance()->passed($group_id, $data['from_account_id'])
        );
    }

    /**
     * 拒绝加入群聊
     * @param RejectedRequest $request
     * @param                 $group_id
     * @return mixed
     */
    public function rejected(RejectedRequest $request, $group_id)
    {
        $data = $request->validated();

        return $this->response->RESTfulAPI(
            GroupService::getInstance()->rejected($group_id, $data['from_account_id'])
        );
    }
}
