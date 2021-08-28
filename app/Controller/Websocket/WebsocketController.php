<?php
declare(strict_types=1);

namespace App\Controller\Websocket;

use App\Helpers\AppHelper;
use App\Swoole\Websocket\OnClose;
use App\Swoole\Websocket\OnMessage;
use App\Swoole\Websocket\OnOpen;
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
    /**
     * @param \Swoole\Http\Response|\Swoole\WebSocket\Server $server
     * @param Request                                        $request
     */
    public function onOpen($server, Request $request): void
    {
        // TODO: Implement onOpen() method.

        AppHelper::getContainer()->get(OnOpen::class)->onOpen($server, $request);
    }

    /**
     * @param \Swoole\Http\Response|\Swoole\WebSocket\Server $server
     * @param Frame                                          $frame
     */
    public function onMessage($server, Frame $frame): void
    {
        // TODO: Implement onMessage() method.

        AppHelper::getContainer()->get(OnMessage::class)->onMessage($server, $frame);
    }

    /**
     * @param \Swoole\Http\Response|\Swoole\Server $server
     * @param int                                  $fd
     * @param int                                  $reactorId
     */
    public function onClose($server, int $fd, int $reactorId): void
    {
        // TODO: Implement onClose() method.

        AppHelper::getContainer()->get(OnClose::class)->onClose($server, $fd, $reactorId);
    }
}
