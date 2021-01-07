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
    //路由资源配置
    Route::get('RouteResource/routeResourceOptions', 'RouteResource/routeResourceOptions');
    //路由资源列表
    Route::get('RouteResource/routeResourceList', 'RouteResource/routeResourceList');
    //路由资源详情
    Route::get('RouteResource/routeResourceDetail', 'RouteResource/routeResourceDetail');
    //路由资源编辑
    Route::post('RouteResource/routeResourceEdit', 'RouteResource/routeResourceEdit');
    //路由资源删除
    Route::post('RouteResource/routeResourceDelete', 'RouteResource/routeResourceDelete');
    //路由资源节点树
    Route::get('RouteResource/routeResourceNodes', 'RouteResource/routeResourceNodes');
    //角色添加
    Route::post('Role/roleAdd', 'Role/roleAdd');
    //角色列表
    Route::get('Role/roleList', 'Role/roleList');
    //角色详情
    Route::get('Role/roleDetail', 'Role/roleDetail');
    //角色编辑
    Route::post('Role/roleEdit', 'Role/roleEdit');
});
