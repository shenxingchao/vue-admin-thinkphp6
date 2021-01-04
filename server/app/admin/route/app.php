<?php
use think\facade\Route;

Route::get('hello', 'Test/hello');

//不需要鉴权的常规路由
Route::group('normal', function () {
});

//需要鉴权的路由分组
Route::group('auth', function () {
    //路由资源添加
    Route::post('RouteResource/routeResourceAdd', 'RouteResource/routeResourceAdd');
});
