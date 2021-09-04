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
 * 用户账号申请加好友消息类型
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
        "message_data": {
            "apply_remark": "\u60a8\u597d\u554a\uff0c\u5144\u5f1f"
        }
    }
 */
class FriendApplyMessage extends Message
{
    const MESSAGE_APPLY_REMARK = 'apply_remark';

    /**
     * 申请加好友原因
     * @var string
     */
    protected $applyRemark = '';

    /**
     * @return string
     */
    public function getMessageType(): string
    {
        // TODO: Implement getMessageType() method.

        return MessageStruct::MESSAGE_FRIEND_APPLY;
    }

    /**
     * 申请加好友原因
     * @param string $applyRemark
     * @return $this
     */
    protected function withApplyRemark(string $applyRemark): self
    {
        $this->applyRemark = $applyRemark;
        return $this;
    }

    /**
     * @return string
     */
    public function getApplyRemark(): string
    {
        return $this->applyRemark;
    }

    /**
     * @return MessageInterface
     */
    protected function toMessage(): MessageInterface
    {
        // TODO: Implement toMessage() method.

        return $this->getMessageStruct()
            ->withMessageType($this->getMessageType())
            ->withMessageData([
                self::MESSAGE_APPLY_REMARK => $this->getApplyRemark(),
            ]);
    }
}