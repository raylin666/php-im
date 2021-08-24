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

/**
 * 替换某个类的实现
 */

return [
    Hyperf\HttpServer\Contract\ResponseInterface::class => App\Dependencies\Response::class,
];
