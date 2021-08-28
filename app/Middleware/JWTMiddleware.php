<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Helpers\AppHelper;
use Phper666\JWTAuth\JWT;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class JWTMiddleware implements MiddlewareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var JWT
     */
    protected $jwt;

    public function __construct(ContainerInterface $container, JWT $jwt)
    {
        $this->container = $container;
        $this->jwt = $jwt;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // JWT 注入进 Request 对象
        AppHelper::getRequest()->JWT = $this->jwt;

        return $handler->handle($request);
    }
}