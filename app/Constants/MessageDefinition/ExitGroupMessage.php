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
 * 用户账号退出群消息类型
 */
class ExitGroupMessage extends Message
{
    const MESSAGE_EXIT_ACCOUNT_ID = 'exit_account_id';

    /**
     * 退出群用户账号
     * @var int
     */
    protected $exitAccountId = 0;

    /**
     * @return string
     */
    public function getMessageType(): string
    {
        // TODO: Implement getMessageType() method.

        return MessageStruct::MESSAGE_EXIT_GROUP;
    }

    /**
     * 设置离开群用户账号
     * @param int $exitAccountId
     * @return $this
     */
    protected function withExitAccountId(int $exitAccountId): self
    {
        $this->exitAccountId = $exitAccountId;
        return $this;
    }

    /**
     * @return int
     */
    public function getExitAccountId(): int
    {
        return $this->exitAccountId;
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
                self::MESSAGE_EXIT_ACCOUNT_ID => $this->getExitAccountId(),
            ]);
    }
}