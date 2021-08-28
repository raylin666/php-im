<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Exception\AccountAuthorizationException;
use App\Helpers\AppHelper;
use App\Model\Account\Account;
use App\Model\Account\AccountAuthorization;
use App\Model\Account\Authorization;
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
        $isSuccessToken = false;
        $account_token = $request->getHeader('account-token')[0] ?? '';
        $authorization_key = $request->getHeader('authorization-key')[0] ?? '';
        $authorization_secret = $request->getHeader('authorization-secret')[0] ?? '';

        if (! ($authorization_id = Authorization::getAuthorizationId($authorization_key, $authorization_secret))) {
            throw new AccountAuthorizationException(403, 'Account not authorized.');
        }

        if (strlen($account_token) <= 0) {
            goto VALID_RESULT;
        }

        // 校验 Token 的有效性
        if (! AppHelper::getJWT()->checkToken($account_token)) {
            goto VALID_RESULT;
        }

        // 解析 Token
        $parserData = AppHelper::getJWt()->getParserData($account_token);
        if ((! isset($parserData['account_id'])) || (! isset($parserData['authorization_id']))) {
            goto VALID_RESULT;
        }

        if (! ($account_id = Account::getAccountId($parserData['authorization_id'], $parserData['account_id']))) {
            goto VALID_RESULT;
        }

        if (AccountAuthorization::isNormalAccountAuthorization($account_id, $parserData['authorization_id'], $account_token)) {
            $isSuccessToken = true;
        }

VALID_RESULT:

        if (! $isSuccessToken) {
            throw new AccountAuthorizationException(403, 'Authorization account verification failed. Please ensure the normal authorization account.');
        }

        defined('ACCOUNT_ID') or define('ACCOUNT_ID', $account_id);
        defined('AUTHORIZATION_ID') or define('AUTHORIZATION_ID', $authorization_id);

        return $handler->handle($request);
    }
}