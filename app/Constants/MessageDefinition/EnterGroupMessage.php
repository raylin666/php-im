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
 * 用户账号进入群消息类型
 */
class EnterGroupMessage extends Message
{
    const MESSAGE_ENTER_ACCOUNT_ID = 'enter_account_id';

    /**
     * 进入群用户账号
     * @var int
     */
    protected $enterAccountId = 0;

    /**
     * @return string
     */
    public function getMessageType(): string
    {
        // TODO: Implement getMessageType() method.

        return MessageStruct::MESSAGE_ENTER_GROUP;
    }

    /**
     * 设置进入群用户账号
     * @param  int  $enterAccountId
     * @return $this
     */
    protected function withEnterAccountId(int $enterAccountId): self
    {
        $this->enterAccountId = $enterAccountId;
        return $this;
    }

    /**
     * @return int
     */
    public function getEnterAccountId(): int
    {
        return $this->enterAccountId;
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
                self::MESSAGE_ENTER_ACCOUNT_ID => $this->getEnterAccountId(),
            ]);
    }
}