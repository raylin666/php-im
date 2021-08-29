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
namespace App\Constants;

use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;

/**
 * @method $this getMessage($code)
 * @Constants
 */
class WebSocketErrorCode extends AbstractConstants
{
    /**
     * @Message("Error")
     */
    const WS_ERROR = -1;

    /**
     * @Message("Success")
     */
    const WS_SUCCESS = 0;

    /**
     * @Message("Account not authorized")
     */
    const WS_ACCOUNT_NOT_AUTHORIZED = 10001;

    /**
     * @Message("Authorization account verification failed. Please ensure the normal authorization account")
     */
    const WS_AUTHORIZATION_ACCOUNT_VERIFICATION_FAILED = 10002;
}
