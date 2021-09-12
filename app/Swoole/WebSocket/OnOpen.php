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
namespace App\Swoole\Websocket;

use App\Constants\Common;
use App\Helpers\JWTHelper;
use App\Repository\AchieveClass\AccountToken;
use Throwable;
use RuntimeException;
use App\Constants\WebSocketErrorCode;
use App\Helpers\AppHelper;
use App\Helpers\WebsocketHelper;
use App\Model\Account\Account;
use App\Model\Account\AccountAuthorization;
use App\Model\Account\Authorization;
use Hyperf\Contract\OnOpenInterface;
use Swoole\Http\Request;

class OnOpen implements OnOpenInterface
{
    /**
     * @param \Swoole\Http\Response|\Swoole\WebSocket\Server $server
     * @param Request                                        $request
     */
    public function onOpen($server, Request $request): void
    {
        // TODO: Implement onOpen() method.

        $fd = $request->fd;

        $is_success_token = false;
        $account_token = $request->header[Common::REQUEST_HEADERS_ACCOUNT_TOKEN] ?? '';
        $authorization_key = $request->header[Common::REQUEST_HEADERS_AUTHORIZATION_KEY] ?? '';
        $authorization_secret = $request->header[Common::REQUEST_HEADERS_AUTHORIZATION_SECRET] ?? '';

        if (! ($authorization_id = Authorization::getAuthorizationId($authorization_key, $authorization_secret))) {
            WebsocketHelper::pushMessage($fd, null, WebSocketErrorCode::WS_ACCOUNT_NOT_AUTHORIZED, null, true);
            return;
        }

        if (strlen($account_token) <= 0) {
            goto GOTO_VALID_RESULT;
        }

        // 校验 Token 的有效性
        try {
            if (! JWTHelper::get()->checkToken($account_token)) {
                throw new RuntimeException('Token verification failed.');
            }
        } catch (Throwable $e) {
            goto GOTO_VALID_RESULT;
        }

        // 解析 Token
        $parserData = JWTHelper::get()->getParserData($account_token);
        if ((! isset($parserData[AccountToken::ACCOUNT_ID])) || (! isset($parserData[AccountToken::AUTHORIZATION_ID]))) {
            goto GOTO_VALID_RESULT;
        }

        if (! ($account_id = Account::getAccountId($parserData[AccountToken::ACCOUNT_ID], $parserData[AccountToken::AUTHORIZATION_ID]))) {
            goto GOTO_VALID_RESULT;
        }

        if (AccountAuthorization::isNormalAccountAuthorization($account_id, $account_token, $parserData[AccountToken::AUTHORIZATION_ID])) {
            $is_success_token = true;
        }

GOTO_VALID_RESULT:

        if (! $is_success_token) {
            WebsocketHelper::pushMessage($fd, null, WebSocketErrorCode::WS_AUTHORIZATION_ACCOUNT_VERIFICATION_FAILED, null, true);
            return;
        }

        // 同一用户账号不允许同时在线
        if (AccountAuthorization::isOnline($account_id, $authorization_id)) {
            WebsocketHelper::pushMessage($fd, null, WebSocketErrorCode::WS_ACCOUNT_ON_OTHER_DEVICES_LOGIN, null, true);
            return;
        }

        // Token 解析内容数据保存
        AppHelper::getAccountToken()->withAccountId($account_id)->withAuthorizationId($authorization_id);

        // 创建 fd -> account 绑定关系
        AppHelper::getIMTable()
            ->withFd($fd)
            ->withAccountId($account_id)
            ->withAuthorizationId($authorization_id)
            ->withData('')
            ->bind($fd);

        // 设置用户在线状态
        AccountAuthorization::setOnline($account_id);
    }
}