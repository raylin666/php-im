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

use Hyperf\Contract\OnMessageInterface;
use Swoole\WebSocket\Frame;

class OnMessage implements OnMessageInterface
{
    public function onMessage($server, Frame $frame): void
    {
        // TODO: Implement onMessage() method.

        $server->push($frame->fd, 'Recv: ' . $frame->data);
    }
}