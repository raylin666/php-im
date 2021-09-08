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
namespace App\Swoole\Tcp;

use App\Helpers\AppHelper;

class ServerStartCallback extends \Hyperf\Framework\Bootstrap\ServerStartCallback
{
    /**
     * 服务启动初始化
     */
    public function beforeStart()
    {
        // 创建 IM Table 表
        AppHelper::getIMTable()->create(65536);
        // 创建 IM Group Table 表
        AppHelper::getIMGroupTable()->create(65536);
    }
}