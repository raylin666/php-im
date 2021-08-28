<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class WebSocketMiddleware implements MiddlewareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $account_id = intval($request->getHeader('account-token')[0] ?? 0);
        $authorization_id = intval($request->getHeader('authorization-id')[0] ?? 0);

        defined('ACCOUNT_TOKEN') or define('ACCOUNT_TOKEN', $account_id);
        defined('AUTHORIZATION_ID') or define('AUTHORIZATION_ID', $authorization_id);

        return $handler->handle($request);
    }
}