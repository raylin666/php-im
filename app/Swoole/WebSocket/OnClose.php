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
namespace App\Swoole\Websocket;

use App\Helpers\AppHelper;
use App\Model\Account\AccountAuthorization;
use Hyperf\Contract\OnCloseInterface;

class OnClose implements OnCloseInterface
{
    /**
     * @param \Swoole\Http\Response|\Swoole\Server $server
     * @param int                                  $fd
     * @param int                                  $reactorId
     */
    public function onClose($server, int $fd, int $reactorId): void
    {
        // TODO: Implement onClose() method.

        $fd_info = AppHelper::getIMTable()->getInfo($fd);

        // 删除 fd -> account 绑定关系
        AppHelper::getIMTable()->unbind($fd);

        // 设置用户离线状态
        AccountAuthorization::setOffline($fd_info['account_id'], $fd_info['authorization_id']);

        $server->push($fd, 'bye!');
    }
}