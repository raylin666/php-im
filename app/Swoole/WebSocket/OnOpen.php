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
use App\Helpers\HeadersHelper;
use Hyperf\Contract\OnOpenInterface;
use Swoole\Http\Request;

class OnOpen implements OnOpenInterface
{
    /**
     * @param \Swoole\Http\Response|\Swoole\WebSocket\Server $server
     * @param Request                                        $request
     */
    public function onOpen($server, Request $request): void
    {
        // TODO: Implement onOpen() method.

        $fd = $request->fd;

        // 创建 fd -> account 绑定关系
        AppHelper::getIMTable()->withFd($fd)
            ->withAccountId(HeadersHelper::getAccountId())
            ->withAuthorizationId(HeadersHelper::getAuthorizationId())
            ->withData('')
            ->bind($fd);

        $server->push($fd, 'Opened');
    }
}