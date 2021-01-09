<?php
declare (strict_types = 1);

namespace app\admin\controller;

use app\admin\validate\User as ValidateUser;
use think\Exception;
use think\exception\ValidateException;
use think\facade\Db;
use think\Response;

//用户操作控制器 未登录
class User extends BaseController {
    /**
     * @api {post} /User/login 用户登录
     * @apiVersion 0.0.1
     * @apiName login
     * @apiGroup 用户
     *
     * @apiParam (参数) {String} username=admin 账号名
     * @apiParam (参数) {String} password=admin 密码
     * @apiParamExample {json} 请求示例
     * {
     *    "username": "admin",
     *    "password": "admin"
     * }
     * @apiSuccess (返回字段) {Number} code 状态码
     * @apiSuccess (返回字段) {String} message  消息
     * @apiSuccess (返回字段) {Array}  data  数据
     * @apiSuccess (返回字段) {String}  data.token  接口token值
     *
     * @apiSuccessExample 成功示例
     * HTTP/1.1 200 Success
     * {
     *    "code": 20000,
     *    "message": "SUCCESS",
     *    "data": {
     *        "token": "82af88abd08291305ff0f96ed185a781"
     *    }
     * }
     * @apiErrorExample 失败示例1
     * {
     *    "code": "1001"
     * }
     * @apiErrorExample 失败示例2
     * {
     *    "code": "1002"
     * }
     * @apiErrorExample 失败示例3
     * {
     *    "code": "1003"
     * }
     * @apiError (错误代码) 1001 数据验证失败
     * @apiError (错误代码) 1002 用户名或密码错误
     * @apiError (错误代码) 1003 登录失败,token生成失败
     */
    public function login(): Response {
        $request = $this->request;
        $request->filter(['trim']);
        $code    = 20000;
        $message = 'SUCCESS';
        try {
            $param = $request->param();
            try {
                validate(ValidateUser::class)->scene('login')->check($param);
            } catch (ValidateException $e) {
                // 验证失败 输出错误信息
                $code = 10001;
                throw new Exception($e->getError());
            }
            $user = Db::name("admin")->where([
                ['username', '=', $param['username']],
                ['password', '=', md5($param['password'])],
            ])->find();
            if (!$user) {
                $code = 10002;
                throw new Exception('用户名或密码错误');
            }
            //验证成功生成用户token
            $token = Token::refreshUserToken($user['id']);
            if (!$token) {
                $code = 10003;
                throw new Exception('登录失败');
            }
            $data = array('token' => $token); //返回数据，可自行添加
        } catch (Exception $e) {
            $data    = null;
            $message = $e->getMessage();
        }
        return json(['code' => $code, 'message' => $message, 'data' => $data]);
    }
}