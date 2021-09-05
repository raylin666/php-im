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
namespace App\Constants\MessageDefinition;

use App\Contract\MessageInterface;

/**
 * 通过用户账号加好友申请消息类型
 *
 * 请求：
 * {"message_type": "friend_apply", "message_data": {"apply_remark": "您好啊，兄弟"}, "room_type": "C2C", "to_account_id": "5"}
 *
 * 响应：
 * {
        "room_type": "C2C",
        "room_id": "",
        "from_account_id": 8,
        "to_account_id": 5,
        "message_type": "friend_apply",
        "message_id": 1,
        "message_data": {
            "apply_remark": "\u60a8\u597d\u554a\uff0c\u5144\u5f1f"
        }
    }
 */
class PassedFriendApplyMessage extends Message
{
    /**
     * @return string
     */
    public function getMessageType(): string
    {
        // TODO: Implement getMessageType() method.

        return MessageStruct::MESSAGE_PASSED_FRIEND_APPLY;
    }

    /**
     * @return MessageInterface
     */
    protected function toMessage(): MessageInterface
    {
        // TODO: Implement toMessage() method.

        return $this->getMessageStruct()
            ->withMessageType($this->getMessageType())
            ->withMessageData();
    }
}