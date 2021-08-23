<?php
declare(strict_types=1);

namespace App\Controller\Websocket;

use Hyperf\Contract\OnCloseInterface;
use Hyperf\Contract\OnMessageInterface;
use Hyperf\Contract\OnOpenInterface;
use Swoole\Http\Request;
use Swoole\Websocket\Frame;

/**
 * Websocket server
 */
class WebSocketController implements OnMessageInterface,
    OnOpenInterface,
    OnCloseInterface
{
    public function onOpen($server, Request $request): void
    {
        // TODO: Implement onOpen() method.

        $server->push($request->fd, 'Opened');
    }

    public function onMessage($server, Frame $frame): void
    {
        // TODO: Implement onMessage() method.

        $server->push($frame->fd, 'Recv: ' . $frame->data);
    }

    public function onClose($server, int $fd, int $reactorId): void
    {
        // TODO: Implement onClose() method.

        var_dump('closed');
    }
}
