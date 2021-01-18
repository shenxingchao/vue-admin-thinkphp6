<?php
declare (strict_types = 1);
namespace app\admin\middleware;

//跨域中间件
class Cors {
    public function handle($request, \Closure$next) {
        //构造方法 设置允许跨域请求
        header('Access-Control-Allow-Origin:*');
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept,  X-Token");
        header("Access-Control-Expose-Headers: Token,Code");
        header('Access-Control-Max-Age: 3600');
        if (strtoupper($_SERVER['REQUEST_METHOD']) == 'OPTIONS') {
            exit;
        }
        return $next($request);
    }
}
