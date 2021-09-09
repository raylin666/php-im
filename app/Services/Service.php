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
namespace App\Services;

use App\Constants\HttpErrorCode;
use App\Dependencies\Response;
use App\Helpers\AppHelper;
use App\Model\Account\Account;

/**
 * Class Service
 * @package App\Services
 */
abstract class Service
{
    /**
     * @var array
     */
    private static $instance = [];

    /**
     * @return object
     */
    public static function getInstance()
    {
        $className = get_called_class();
        if (!isset(self::$instance[$className]) || !self::$instance[$className] instanceof self) {
            self::$instance[$className] = new static();
        }

        return self::$instance[$className];
    }

    /**
     * @return \Hyperf\HttpServer\Contract\ResponseInterface|Response|mixed
     */
    protected function response()
    {
        return AppHelper::getResponse();
    }

    /**
     * 验证用户账号是否可用, 返回可用用户账号信息
     * @param $account_id
     * @return \Hyperf\Database\Model\Builder|\Hyperf\Database\Model\Model|object|void
     */
    protected function verifyAccountOrGet($account_id)
    {
        $account = Account::getAccount($account_id);
        if (! $account) {
            return $this->response()->error(HttpErrorCode::ACCOUNT_NOT_AVAILABLE);
        }

        if ($account['state'] != Account::STATE_OPEN) {
            return $this->response()->error(HttpErrorCode::ACCOUNT_NOT_AVAILABLE);
        }

        return $account;
    }
}
