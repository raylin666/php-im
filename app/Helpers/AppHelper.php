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

use Hyperf\Contract\StdoutLoggerInterface;
use Throwable;
use App\Repository\AchieveClass\AccountToken;
use App\Swoole\Table\IMTable;
use Hyperf\DbConnection\Db;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Redis\Redis;
use Hyperf\Utils\ApplicationContext;
use Psr\Http\Message\ServerRequestInterface;
use Swoole\Server;

class AppHelper extends Helper
{
    /**
     * 获取容器
     * @return \Psr\Container\ContainerInterface
     */
    protected function getContainer()
    {
        return ApplicationContext::getContainer();
    }

    /**
     * 获取请求类
     * @return RequestInterface|mixed
     */
    protected function getRequest()
    {
        return $this->getContainer()->get(RequestInterface::class);
    }

    /**
     * 获取服务请求类
     * @return mixed|ServerRequestInterface
     */
    protected function getServerRequest()
    {
        return $this->getContainer()->get(ServerRequestInterface::class);
    }

    /**
     * 获取响应类
     * @return ResponseInterface|mixed
     */
    protected function getResponse()
    {
        return $this->getContainer()->get(ResponseInterface::class);
    }

    /**
     * 获取 DB 服务
     * @return Db|mixed
     */
    protected function getDb()
    {
        return $this->getContainer()->get(Db::class);
    }

    /**
     * 获取 Redis 服务
     * @return Redis|mixed
     */
    protected function getRedis()
    {
        return $this->getContainer()->get(Redis::class);
    }

    /**
     * 获取日志服务
     * @return StdoutLoggerInterface|mixed
     */
    protected function getLogger()
    {
        return $this->getContainer()->get(StdoutLoggerInterface::class);
    }

    /**
     * 获取 Swoole Server
     * @return mixed|Server
     */
    protected function getServer()
    {
        return $this->getContainer()->get(Server::class);
    }

    /**
     * 获取 IM Table 表
     * @return IMTable|mixed
     */
    protected function getIMTable()
    {
        return $this->getContainer()->get(IMTable::class);
    }

    /**
     * @return AccountToken|mixed
     */
    protected function getAccountToken()
    {
        return $this->getContainer()->get(AccountToken::class);
    }

    /**
     * @return int
     */
    protected function getAuthorizationId(): int
    {
        return intval($this->getServerRequest()->authorization_id) ?: $this->getAccountToken()->getAuthorizationId();
    }

    /**
     * 协程处理
     * @param callable $closure
     * @param          ...$arguments
     */
    protected function getGo(callable $closure, ...$arguments)
    {
        go(function () use ($closure, $arguments) {
            try {
                $closure(...$arguments);
            } catch (Throwable $e) {
                $this->getLogger()->warning($e->getMessage(), [
                    'code' => $e->getCode(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]);
            }
        });
    }
}
