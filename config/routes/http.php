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
use Hyperf\HttpServer\Router\Router;

// 路由拦截
Router::get('/favicon.ico', function () {
    return '';
});

/**
 * 账号模块
 */
Router::addGroup('/account', function () {
    // 获取账号信息
    Router::get('/{account_id}/info', 'App\Controller\Http\AccountController@info');
    // 添加账号
    Router::put('/add', 'App\Controller\Http\AccountController@add');
    // 修改账号
    Router::post('/{account_id}/edit', 'App\Controller\Http\AccountController@edit');
    // 删除账号
    Router::delete('/{account_id}/delete', 'App\Controller\Http\AccountController@delete');
});
