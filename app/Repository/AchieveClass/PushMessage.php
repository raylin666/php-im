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
namespace App\Repository\AchieveClass;

use App\Constants\WebSocketErrorCode;
use App\Contract\MessageDefinitionInterface;
use App\Contract\PushMessageInterface;
use Carbon\Carbon;

class PushMessage implements PushMessageInterface
{
    const RESPONSE_TIME = 'response_time';
    const CODE = 'code';
    const MESSAGE = 'message';
    const DATA = 'data';

    protected $response_time;
    protected $code;
    protected $message;
    protected $data;

    public function withResponseTime(Carbon $carbon): PushMessageInterface
    {
        // TODO: Implement withResponseTime() method.

        $this->response_time = $carbon;
        return $this;
    }

    public function getResponseTime(): Carbon
    {
        // TODO: Implement getResponseTime() method.

        return $this->response_time ?: Carbon::now();
    }

    public function withCode(int $code): PushMessageInterface
    {
        // TODO: Implement withCode() method.

        $this->code = $code;
        $this->message = WebSocketErrorCode::getMessage($code);
        return $this;
    }

    public function getCode(): int
    {
        // TODO: Implement getCode() method.

        return intval($this->code);
    }

    public function withMessage(string $message): PushMessageInterface
    {
        // TODO: Implement withMessage() method.

        if (! empty($message)) {
            $this->message = $message;
        }

        return $this;
    }

    public function getMessage(): string
    {
        // TODO: Implement getMessage() method.

        return strval($this->message);
    }

    public function withData(?MessageDefinitionInterface $definition): PushMessageInterface
    {
        // TODO: Implement withData() method.

        $this->data = $definition;
        return $this;
    }

    /**
     * @return MessageDefinitionInterface|null
     */
    public function getData(): ?MessageDefinitionInterface
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $definition = $this->getData();
        $data = $definition instanceof MessageDefinitionInterface ? $definition->toArray() : null;

        return [
            self::RESPONSE_TIME => $this->getResponseTime(),
            self::CODE => $this->getCode(),
            self::MESSAGE => $this->getMessage(),
            self::DATA => $data,
        ];
    }

    /**
     * @return false|string
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }
}