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
 * 用户账号模块
 */
Router::addGroup('/account', function () {
    // 获取用户账号信息
    Router::get('/{account_id}/info', 'App\Controller\Http\AccountController@info');
    // 获取用户账号 Token
    Router::get('/{account_id}/account-token', 'App\Controller\Http\AccountController@accountToken');
    // 创建用户账号
    Router::put('/create', 'App\Controller\Http\AccountController@create');
    // 修改用户账号
    Router::post('/{account_id}/update', 'App\Controller\Http\AccountController@update');
    // 删除用户账号
    Router::delete('/{account_id}/delete', 'App\Controller\Http\AccountController@delete');
});

Router::addGroup('/friend', function () {
    // 申请添加好友
    Router::post('/{account_id}/apply', 'App\Controller\Http\FriendController@apply');
    // 确认添加好友
    Router::post('/{account_id}/passed', 'App\Controller\Http\FriendController@passed');
    // 拒绝添加好友
    Router::post('/{account_id}/rejected', 'App\Controller\Http\FriendController@rejected');
    // 删除好友
    Router::delete('/{account_id}/delete', 'App\Controller\Http\FriendController@delete');
});

Router::addGroup('/group', function () {
    // 创建群聊
    Router::put('/{account_id}/create', 'App\Controller\Http\GroupController@create');
    // 获取群信息
    Router::get('/{group_id}/info', 'App\Controller\Http\GroupController@info');
    // 修改群信息
    Router::post('/{account_id}/update', 'App\Controller\Http\GroupController@update');
    // 删除群聊
    Router::delete('/{group_id}/delete', 'App\Controller\Http\GroupController@delete');

    // 申请加入群聊
    Router::post('/{group_id}/apply', 'App\Controller\Http\GroupController@apply');
    // 确认加入群聊
    Router::post('/{group_id}/passed', 'App\Controller\Http\GroupController@passed');
    // 拒绝加入群聊
    Router::post('/{group_id}/rejected', 'App\Controller\Http\GroupController@rejected');
    // 退出群聊
    Router::post('/{account_id}/quit', 'App\Controller\Http\GroupController@quit');
});
