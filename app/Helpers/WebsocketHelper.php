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

use App\Constants\MessageDefinition\EnterGroupMessage;
use App\Constants\MessageDefinition\ExitGroupMessage;
use App\Constants\MessageDefinition\FriendApplyMessage;
use App\Constants\MessageDefinition\JoinGroupMessage;
use App\Constants\MessageDefinition\MessageStruct;
use App\Constants\MessageDefinition\QuitGroupMessage;
use App\Constants\MessageDefinition\TextMessage;
use App\Constants\WebSocketErrorCode;
use App\Contract\MessageDefinitionInterface;
use App\Contract\MessageInterface;
use App\Repository\AchieveClass\PushMessage;
use App\Repository\AchieveClass\RoomType;
use App\Swoole\Table\IMTable;

class WebsocketHelper extends Helper
{
    /**
     * 获取/设置 消息结构体
     * @return MessageInterface
     */
    protected function getMessageStruct(): MessageInterface
    {
        return AppHelper::getContainer()->get(MessageStruct::class);
    }

    /**
     * 获取用户账号授权的所有 fd
     * @param $account_id
     * @param $authorization_id
     * @return array
     */
    protected function getAccountAuthorizationFd($account_id, $authorization_id): array
    {
        $fds = [];
        foreach (AppHelper::getIMTable()->get() as $row) {
            if (($row[IMTable::CONLUMN_ACCOUNT_ID] == $account_id) && ($row[IMTable::COLUMN_AUTHORIZATION_ID] == $authorization_id)) {
                $fds[] = $row[IMTable::COLUMN_FD];
            }
        }

        return $fds;
    }

    /**
     * 消息内容解析
     * @param        $fd
     * @param string $data
     * @return \App\Constants\MessageDefinition\Message|TextMessage|void
     */
    protected function resolveMessage($fd, string $data)
    {
        $data = json_decode($data, true);
        if (json_last_error() || empty($data)) {
            $this->pushMessage($fd, null, WebSocketErrorCode::WS_MESSAGE_RESOLVE_ERROR);
            return;
        }

        $roomTypeInstance = AppHelper::getContainer()->get(RoomType::class);

        $accountId = AppHelper::getAccountToken()->getAccountId();
        $messageType = $data[MessageStruct::MESSAGE_TYPE] ?? '';
        $messageData = $data[MessageStruct::MESSAGE_DATA] ?? '';
        $roomType = $data[MessageStruct::ROOM_TYPE] ?? '';
        $roomId = $data[MessageStruct::ROOM_ID] ?? '';
        $toAccountId = intval($data[MessageStruct::TO_ACCOUNT_ID] ?? 0);
        if (empty($messageType)
            || empty($messageData)
            || empty($roomType)
            || !in_array($roomType, $roomTypeInstance->toArray())
            || (($roomType == $roomTypeInstance->getC2C()) && ($toAccountId <= 0))
            || (($roomType == $roomTypeInstance->getGroup()) && empty($roomId))
        ) {
            $this->pushMessage($fd, null, WebSocketErrorCode::WS_MESSAGE_FORMAT_ERROR);
            return;
        }

        switch ($messageType) {
            case MessageStruct::MESSAGE_TYPE_TEXT:
                $messageStruct = TextMessage::withMessageContent(
                    $messageData[TextMessage::MESSAGE_DATA_CONTENT] ?? ''
                );
                break;
            case MessageStruct::MESSAGE_ENTER_GROUP:
                $messageStruct = EnterGroupMessage::withEnterAccountId(
                    $messageData[EnterGroupMessage::MESSAGE_ENTER_ACCOUNT_ID] ?? 0
                );
                break;
            case MessageStruct::MESSAGE_QUIT_GROUP:
                $messageStruct = QuitGroupMessage::withQuitAccountId(
                    $messageData[QuitGroupMessage::MESSAGE_QUIT_ACCOUNT_ID] ?? 0
                );
                break;
            case MessageStruct::MESSAGE_JOIN_GROUP:
                $messageStruct = JoinGroupMessage::withJoinAccountId(
                    $messageData[JoinGroupMessage::MESSAGE_JOIN_ACCOUNT_ID] ?? 0
                );
                break;
            case MessageStruct::MESSAGE_EXIT_GROUP:
                $messageStruct = ExitGroupMessage::withExitAccountId(
                    $messageData[ExitGroupMessage::MESSAGE_EXIT_ACCOUNT_ID] ?? 0
                );
                break;
            case MessageStruct::MESSAGE_FRIEND_APPLY:
                // 不能加自己为好友
                if ($toAccountId == $accountId) {
                    $this->pushMessage($fd, null, WebSocketErrorCode::WS_NOT_FIREND_TO_ME);
                    return;
                }
                $messageStruct = FriendApplyMessage::withApplyRemark(
                    $messageData[FriendApplyMessage::MESSAGE_APPLY_REMARK] ?? ''
                );
                break;
            default:
                $this->pushMessage($fd, null, WebSocketErrorCode::WS_UNSUPPORTED_MESSAGE_TYPE);
                return;
        }

        $messageStruct->withBasicMessage(0, $roomType, $roomId, $accountId, $toAccountId);
        return $messageStruct;
    }

    /**
     * 消息协议发送
     * @param                                 $fd
     * @param MessageDefinitionInterface|null $definition
     * @param int                             $code
     * @param null                            $message
     * @param bool                            $isClose
     */
    protected function pushMessage(
        $fd,
        ?MessageDefinitionInterface $definition,
        $code = WebSocketErrorCode::WS_SUCCESS,
        $message = null,
        bool $isClose = false)
    {
        $fd = intval($fd);
        $code = intval($code);

        /** @var PushMessage $push_message */
        $push_message = AppHelper::getContainer()->get(PushMessage::class)
            ->withCode($code)
            ->withMessage(strval($message))
            ->withData($definition);

        $server = AppHelper::getServer();
        $server->push($fd, $push_message->toJson());
        if ($isClose) $server->close($fd);
    }
}
