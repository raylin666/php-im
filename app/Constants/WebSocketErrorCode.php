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
     * @Message("未授权的用户帐户")
     */
    const WS_ACCOUNT_NOT_AUTHORIZED = 10001;

    /**
     * @Message("授权用户帐户验证失败。请确保正常的授权用户帐户")
     */
    const WS_AUTHORIZATION_ACCOUNT_VERIFICATION_FAILED = 10002;

    /**
     * @Message("用户帐户已登录到其他设备, 不支持同时在线")
     */
    const WS_ACCOUNT_ON_OTHER_DEVICES_LOGIN = 10003;

    /**
     * @Message("请先将另一方用户添加为好友")
     */
    const WS_TO_ACCOUNT_NOT_FRIEND = 10004;

    /**
     * @Message("你不能把自己添加为好友")
     */
    const WS_NOT_FIREND_TO_ME = 10005;

    /**
     * @Message("你们已经是好友了")
     */
    const WS_TO_ACCOUNT_IS_FRIEND = 10006;

    /**
     * @Message("您的好友申请已发送, 请不要多次申请")
     */
    const WS_TO_ACCOUNT_JOIN_FRIEND_BE_CONFIRM = 10007;

    /**
     * @Message("你们不是好友关系")
     */
    const WS_TO_ACCOUNT_NOT_IS_FRIEND = 10008;

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

    /**
     * @Message("不是有效群组")
     */
    const WS_GROUP_NOT_VALID = 30001;

    /**
     * @Message("不是该群组成员")
     */
    const WS_ACCOUNT_NOT_GROUP_MEMBER = 30002;
}
