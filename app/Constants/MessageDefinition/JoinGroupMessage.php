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
 * 用户账号加入群消息类型
 */
class JoinGroupMessage extends Message
{
    const MESSAGE_JOIN_ACCOUNT_ID = 'join_account_id';

    /**
     * 加入群用户账号
     * @var int
     */
    protected $joinAccountId = 0;

    /**
     * @return string
     */
    public function getMessageType(): string
    {
        // TODO: Implement getMessageType() method.

        return MessageStruct::MESSAGE_JOIN_GROUP;
    }

    /**
     * 设置加入群用户账号
     * @param  $joinAccountId
     * @return $this
     */
    protected function withJoinAccountId($joinAccountId): self
    {
        $this->joinAccountId = intval($joinAccountId);
        return $this;
    }

    /**
     * @return int
     */
    public function getJoinAccountId(): int
    {
        return $this->joinAccountId;
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
                self::MESSAGE_JOIN_ACCOUNT_ID => $this->getJoinAccountId(),
            ]);
    }
}