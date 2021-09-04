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
 * 用户账号离开群消息类型
 */
class QuitGroupMessage extends Message
{
    const MESSAGE_QUIT_ACCOUNT_ID = 'quit_account_id';

    /**
     * 离开群用户账号
     * @var int
     */
    protected $quitAccountId = 0;

    /**
     * @return string
     */
    public function getMessageType(): string
    {
        // TODO: Implement getMessageType() method.

        return MessageStruct::MESSAGE_QUIT_GROUP;
    }

    /**
     * 设置离开群用户账号
     * @param  $quitAccountId
     * @return $this
     */
    protected function withQuitAccountId($quitAccountId): self
    {
        $this->quitAccountId = intval($quitAccountId);
        return $this;
    }

    /**
     * @return int
     */
    public function getQuitAccountId(): int
    {
        return $this->quitAccountId;
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
                self::MESSAGE_QUIT_ACCOUNT_ID => $this->getQuitAccountId(),
            ]);
    }
}