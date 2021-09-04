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

    /**
     * @Message("The user account has been logged in on other devices")
     */
    const WS_ACCOUNT_ON_OTHER_DEVICES_LOGIN = 10003;

    /**
     * @Message("Please add the other party as a friend first")
     */
    const WS_TO_ACCOUNT_NOT_FRIEND = 10004;

    /**
     * @Message("You can't add yourself as a friend")
     */
    const WS_NOT_FIREND_TO_ME = 10005;

    /**
     * @Message("Message data parsing failed")
     */
    const WS_MESSAGE_RESOLVE_ERROR = 20001;

    /**
     * @Message("Message format error")
     */
    const WS_MESSAGE_FORMAT_ERROR = 20002;

    /**
     * @Message("Unsupported message type")
     */
    const WS_UNSUPPORTED_MESSAGE_TYPE = 20003;
}
