<?php
use think\facade\Route;

Route::get('hello', 'Test/hello');
//路由资源添加
Route::post('RouteResource/routeResourceAdd', 'RouteResource/routeResourceAdd');
