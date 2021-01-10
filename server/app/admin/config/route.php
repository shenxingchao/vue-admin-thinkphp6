<?php
//路由
return [
    //路由中间件
    'middleware' => [
        app\admin\middleware\Cors::class, //跨域中间件 这里开了整个模块都跨域
    ],
    'priority'   => [
        app\admin\middleware\Auth::class,
    ],
];