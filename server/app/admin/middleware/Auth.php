<?php
declare (strict_types = 1);
namespace app\admin\middleware;

use app\admin\controller\Token;
use think\facade\Db;

//跨域中间件
class Auth {
    public function handle($request, \Closure$next) {
        //测试环境模拟成功，不改变数据
        // if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST' || strtoupper($_SERVER['REQUEST_METHOD']) == 'PUT' || strtoupper($_SERVER['REQUEST_METHOD']) == 'DELETE') {
        //     return json(['code' => 20000, 'message' => '测试环境模拟成功，不改变数据', 'data' => null]);
        // }
        //token鉴权和初始化
        $this->checkToken($request);
        return $next($request);
    }

    protected function checkToken($request) {
        $headers = getallheaders();
        if (!isset($headers['X-Token'])) {
            header('Code:50008'); //token不存在 登录
            exit;
        } else {
            //验证token
            $user = Db::name('admin')
                ->where(array('token' => $headers['X-Token']))
                ->field('expire_time,id,token')
                ->find();
            if (!$user) {
                header('Code:50008'); //token不存在 登录
                exit;
            } else if (time() - $user['expire_time'] > 10) {
                //token过期 刷新token 返回新token值
                $token = Token::refreshUserToken($user['id']);
                if (!$token) {
                    header('Code:50014');
                }
                //刷新token失败返回token过期状态码
                header('Token:' . $token);
            }
            $request->token = isset($token) ? $token : $user['token'];
        }
    }
}
