<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Constants\Common;
use App\Constants\HttpErrorCode;
use App\Exception\AccountAuthorizationException;
use App\Exception\AuthorizationException;
use App\Helpers\AppHelper;
use App\Model\Account\Authorization;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AccountAuthorizationMiddleware implements MiddlewareInterface
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
        $authorization_key = $request->getHeaderLine(Common::REQUEST_HEADERS_AUTHORIZATION_KEY);
        $authorization_secret = $request->getHeaderLine(Common::REQUEST_HEADERS_AUTHORIZATION_SECRET);

        if (! ($authorization_id = Authorization::getAuthorizationId($authorization_key, $authorization_secret))) {
            throw new AccountAuthorizationException(HttpErrorCode::AUTHORIZATION_ACCOUNT_VERIFICATION_FAILED);
        }

        // 设置账号授权ID
        AppHelper::getServerRequest()->authorization_id = $authorization_id;

        return $handler->handle($request);
    }
}