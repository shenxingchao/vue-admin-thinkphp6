<?php
declare (strict_types = 1);

namespace app\admin\controller;

use app\admin\validate\Admin as ValidateAdmin;
use app\admin\validate\CommonValidate;
use think\Exception;
use think\exception\ValidateException;
use think\facade\Db;
use think\Response;

class Admin extends BaseController {
    /**
     * @api {post} /Admin/adminAdd 管理员添加
     * @apiVersion 0.0.1
     * @apiName adminAdd
     * @apiGroup 管理员
     *
     * @apiParam (参数) {String} username='empty string' 账号名
     * @apiParam (参数) {String} password='empty string' 密码
     * @apiParam (参数) {Boolean} status=true 是否启用
     * @apiParam (参数) {Array} role_ids 角色id数组
     * @apiParamExample {json} 请求示例
     * {
     *    "username":"11",
     *    "password":"2",
     *    "status":true,
     *    "role_ids":[
     *        1,
     *        2]
     * }
     *
     * @apiSuccess (返回字段) {Number} code 状态码
     * @apiSuccess (返回字段) {String} message  消息
     * @apiSuccess (返回字段) {Array}  data  数据
     *
     * @apiSuccessExample 成功示例
     * HTTP/1.1 200 Success
     * {
     *      "code":20000,
     *      "message":"SUCCESS",
     *      "data":null
     * }
     * @apiErrorExample 失败示例1
     * {
     *    "code": "10001"
     * }
     * @apiErrorExample 失败示例2
     * {
     *    "code": "10002"
     * }
     * @apiErrorExample 失败示例3
     * {
     *    "code": "10003"
     * }
     * @apiError (错误代码) 10001 数据验证失败
     * @apiError (错误代码) 10002 账号已存在
     * @apiError (错误代码) 10003 数据库写入失败
     */
    public function adminAdd(): Response {
        $request = $this->request;
        $request->filter(['trim']);
        $code    = 20000;
        $message = 'SUCCESS';
        try {
            $param = $request->param();
            try {
                validate(ValidateAdmin::class)->scene('add')->check($param);
            } catch (ValidateException $e) {
                // 验证失败 输出错误信息
                $code = 10001;
                throw new Exception($e->getError());
            }
            $is_exist = Db::name("admin")->where(['username' => $param['username']])->find();
            if ($is_exist) {
                $code = 10002;
                throw new Exception('账号已存在');
            }
            $param['role_ids'] = implode(',', $param['role_ids']);
            $param['password'] = md5($param['password']);
            $res               = Db::name('admin')->insert($param);
            if ($res) {
                $data = null;
            } else {
                $code = 10003;
                throw new Exception('添加失败');
            }
        } catch (Exception $e) {
            $data    = null;
            $message = $e->getMessage();
        }
        return json(['code' => $code, 'message' => $message, 'data' => $data]);
    }

    /**
     * @api {get} /Admin/adminList 管理员列表
     * @apiVersion 0.0.1
     * @apiName adminList
     * @apiGroup 管理员
     *
     * @apiParam (参数) {Number} page 当前页
     * @apiParam (参数) {Number} pageSize 分页变量
     * @apiParam (参数) {Number} [keyword] 关键词
     * @apiParam (参数) {Number} [status] 状态 1启用 0禁用
     * @apiParamExample {json} 请求示例
     * {
     *    "page": 1,
     *    "pageSize": 10,
     *    "keyword": "1"
     *    "status": 1
     * }
     * @apiSuccess (返回字段) {Number} code 状态码
     * @apiSuccess (返回字段) {String} message  消息
     * @apiSuccess (返回字段) {Array}  data  数据
     * @apiSuccess (返回字段) {Number} data.total  总记录数
     * @apiSuccess (返回字段) {Number} data.per_page  每页数量
     * @apiSuccess (返回字段) {Number} data.current_page  当前页
     * @apiSuccess (返回字段) {Number} data.last_page  最后一页
     * @apiSuccess (返回字段) {Number} data.last_page  最后一页
     * @apiSuccess (返回字段) {Array} data.data  管理员详情数组
     * @apiSuccess (返回字段) {Number} data.data.id  管理员id
     * @apiSuccess (返回字段) {String} data.data.username  账号名
     * @apiSuccess (返回字段) {Number} data.data.status  状态 1启用 0 禁用
     * @apiSuccess (返回字段) {String} data.data.role_ids  角色id数组
     * @apiSuccess (返回字段) {String} data.data.roles  角色
     *
     * @apiSuccessExample 成功示例
     * HTTP/1.1 200 Success
     * {
     *    "code": 20000,
     *    "message": "SUCCESS",
     *    "data": {
     *        "total": 1,
     *        "per_page": 2,
     *        "current_page": 1,
     *        "last_page": 1,
     *        "data": [
     *            {
     *                "id": 3,
     *                "username": "33",
     *                "status": 1,
     *                "role_ids": "2",
     *                "roles": "访客"
     *            }
     *        ]
     *    }
     * }
     * @apiErrorExample 失败示例1
     * {
     *    "code": "1001"
     * }
     * @apiError (错误代码) 1001 数据验证失败
     */
    public function adminList(): Response {
        $request = $this->request;
        $request->filter(['trim']);
        $code    = 20000;
        $message = 'SUCCESS';
        try {
            $param = $request->param();
            try {
                validate(CommonValidate::class)->scene('admin_list')->check($param);
            } catch (ValidateException $e) {
                // 验证失败 输出错误信息
                $code = 10001;
                throw new Exception($e->getError());
            }
            $where    = [];
            $whereAnd = [];
            if (isset($param['keyword']) && $param['keyword'] != '') {
                //$where = [['username|password', 'like', '%' . $param['keyword'] . '%']];
                //或者的语法
                $where = [['username', 'like', '%' . $param['keyword'] . '%']];
            }
            if (isset($param['status']) && $param['status'] != '') {
                $whereAnd[] = ['status', '=', $param['status']];
            }
            $list = Db::name('admin')->where($where)->where($whereAnd)->field('id,username,status,role_ids')->paginate($param['pageSize'], false)->each(function ($item, $key) {
                $roles         = Db::name('role')->where([['id', 'in', explode(',', $item['role_ids'])]])->column('role_name');
                $item['roles'] = implode(' | ', $roles);
                return $item;
            });
            $data = $list;
        } catch (Exception $e) {
            $data    = null;
            $message = $e->getMessage();
        }
        return json(['code' => $code, 'message' => $message, 'data' => $data]);
    }
}