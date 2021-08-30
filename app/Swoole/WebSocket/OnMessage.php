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

use App\Constants\MessageDefinition\Message;
use App\Constants\MessageDefinition\TextMessage;
use App\Helpers\WebsocketHelper;
use Hyperf\Contract\OnMessageInterface;
use Swoole\WebSocket\Frame;

class OnMessage implements OnMessageInterface
{
    /**
     * @param \Swoole\Http\Response|\Swoole\WebSocket\Server $server
     * @param Frame                                          $frame
     */
    public function onMessage($server, Frame $frame): void
    {
        // TODO: Implement onMessage() method.

        $message  = WebsocketHelper::resolveMessage($frame->fd, $frame->data);

        if (! $message instanceof Message) return;

        switch (get_class($message)) {
            // 文本消息
            case TextMessage::class:
                break;
        }

        WebsocketHelper::pushMessage($frame->fd, $message);
    }
}