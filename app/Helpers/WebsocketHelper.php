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
namespace App\Helpers;

use App\Constants\MessageDefinition\MessageStruct;
use App\Constants\WebSocketErrorCode;
use App\Contract\MessageDefinitionInterface;
use App\Contract\MessageInterface;

class WebsocketHelper extends Helper
{
    /**
     * @return MessageInterface
     */
    protected function getMessageStruct(): MessageInterface
    {
        return AppHelper::getContainer()->get(MessageStruct::class);
    }

    /**
     * 消息协议发送
     * @param                                 $fd
     * @param MessageDefinitionInterface|null $definition
     * @param int                             $code
     * @param null                            $message
     * @param bool                            $isClose
     */
    protected function push(
        $fd,
        ?MessageDefinitionInterface $definition,
        $code = WebSocketErrorCode::WS_SUCCESS,
        $message = null,
        bool $isClose = false)
    {
        $fd = intval($fd);
        $code = intval($code);

        $server = AppHelper::getServer();

        $server->push(
            $fd,
            json_encode([
                'time' => time(),
                'code' => $code,
                'message' => $message ?: WebSocketErrorCode::getMessage($code),
                'data' => $definition instanceof MessageDefinitionInterface ? $definition->toArray() : null,
            ])
        );

        if ($isClose) $server->close($fd);
    }
}
