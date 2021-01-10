<?php
declare (strict_types = 1);

namespace app\admin\controller;

use app\admin\validate\CommonValidate;
use app\admin\validate\Role as ValidateRole;
use think\Exception;
use think\exception\ValidateException;
use think\facade\Db;
use think\Response;

class Role extends BaseController {
    /**
     * @api {post} /Role/roleAdd 角色添加
     * @apiVersion 0.0.1
     * @apiName roleAdd
     * @apiGroup 角色
     * @apiHeader {String} X-token api-token
     *
     * @apiParam (参数) {String} role_name='empty string' 角色名称
     * @apiParam (参数) {Array} route_resource_ids 全选中和半选中权限ids
     * @apiParam (参数) {Array} temp_route_resource_ids 全选中权限ids
     * @apiParamExample {json} 请求示例
     * {
     *     "role_name":"123",
     *     "route_resource_ids":[
     *         1,
     *         2,
     *         6,
     *         8,
     *         9,
     *         3,
     *         4],
     *     "temp_route_resource_ids":[
     *         1,
     *         2,
     *         6,
     *         8,
     *         9]
     * }
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
     * @apiError (错误代码) 10002 角色名称已存在
     * @apiError (错误代码) 10003 数据库写入失败
     */
    public function roleAdd(): Response {
        $request = $this->request;
        $request->filter(['trim']);
        $code    = 20000;
        $message = 'SUCCESS';
        try {
            $param = $request->param();
            try {
                validate(ValidateRole::class)->scene('add')->check($param);
            } catch (ValidateException $e) {
                // 验证失败 输出错误信息
                $code = 10001;
                throw new Exception($e->getError());
            }
            $is_exist = Db::name("role")->where(['role_name' => $param['role_name']])->find();
            if ($is_exist) {
                $code = 10002;
                throw new Exception('角色名称已存在');
            }
            $param['route_resource_ids']      = implode(',', $param['route_resource_ids']);
            $param['temp_route_resource_ids'] = implode(',', $param['temp_route_resource_ids']);

            $res = Db::name('role')->insert($param);
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
     * @api {get} /Role/roleList 角色列表
     * @apiVersion 0.0.1
     * @apiName roleList
     * @apiGroup 角色
     * @apiHeader {String} X-token api-token
     *
     * @apiParam (参数) {Number} page 当前页
     * @apiParam (参数) {Number} pageSize 分页变量
     * @apiParam (参数) {Number} [keyword] 关键词
     * @apiParamExample {json} 请求示例
     * {
     *      "page": 1,
     *      "pageSize": 10,
     *      "keyword": "10"
     * }
     * @apiSuccess (返回字段) {Number} code 状态码
     * @apiSuccess (返回字段) {String} message  消息
     * @apiSuccess (返回字段) {Array}  data  数据
     * @apiSuccess (返回字段) {Number} data.total  总记录数
     * @apiSuccess (返回字段) {Number} data.per_page  每页数量
     * @apiSuccess (返回字段) {Number} data.current_page  当前页
     * @apiSuccess (返回字段) {Number} data.last_page  最后一页
     * @apiSuccess (返回字段) {Number} data.last_page  最后一页
     * @apiSuccess (返回字段) {Array} data.data  角色详情数组
     * @apiSuccess (返回字段) {Number} data.data.id  id
     * @apiSuccess (返回字段) {String} data.data.role_name  角色名称
     * @apiSuccess (返回字段) {String} data.data.route_resource_ids  半选中和全选中id
     * @apiSuccess (返回字段) {String} data.data.temp_route_resource_ids  全选中id
     * @apiSuccess (返回字段) {String} data.data.permissions  权限
     *
     * @apiSuccessExample 成功示例
     * HTTP/1.1 200 Success
     * {
     *    "code":20000,
     *    "message":"SUCCESS",
     *    "data":{
     *        "total":2,
     *        "per_page":10,
     *        "current_page":1,
     *        "last_page":1,
     *        "data":[
     *        {
     *            "id":1,
     *            "role_name":"超级管理员",
     *            "route_resource_ids":"1,2,6,7,8,9,3,4",
     *            "temp_route_resource_ids":"1,2,6,7,8,9",
     *            "permissions":"首页 | 控制台 | 权限管理 | 路由资源管理 | 路由资源添加 | 路由资源编辑 | 角色管理 | 角色列表"
     *        },
     *        {
     *            "id":2,
     *            "role_name":"访客",
     *            "route_resource_ids":"1,2",
     *            "temp_route_resource_ids":"1,2",
     *            "permissions":"首页 | 控制台"
     *        }]
     *    }
     * }
     * @apiErrorExample 失败示例1
     * {
     *    "code": "1001"
     * }
     * @apiError (错误代码) 1001 数据验证失败
     */
    public function roleList(): Response {
        $request = $this->request;
        $request->filter(['trim']);
        $code    = 20000;
        $message = 'SUCCESS';
        try {
            $param = $request->param();
            try {
                validate(CommonValidate::class)->scene('role_list')->check($param);
            } catch (ValidateException $e) {
                // 验证失败 输出错误信息
                $code = 10001;
                throw new Exception($e->getError());
            }
            $where = [];
            if (isset($param['keyword']) && $param['keyword'] != '') {
                $where = [['role_name', 'like', '%' . $param['keyword'] . '%']];
            }
            $list = Db::name('role')->where($where)->paginate($param['pageSize'], false)->each(function ($item, $key) {
                $permissions         = Db::name('route_resource')->where([['id', 'in', $item['route_resource_ids']]])->column('title');
                $item['permissions'] = implode(' | ', $permissions);
                return $item;
            });
            $data = $list;
        } catch (Exception $e) {
            $data    = null;
            $message = $e->getMessage();
        }
        return json(['code' => $code, 'message' => $message, 'data' => $data]);
    }

    /**
     * @api {get} /Role/roleDetail 角色详情
     * @apiVersion 0.0.1
     * @apiName roleDetail
     * @apiGroup 角色
     * @apiHeader {String} X-token api-token
     *
     * @apiParam (参数) {Number} id 角色id
     * @apiParamExample {json} 请求示例
     * {
     *  "id": 1,
     * }
     * @apiSuccess (返回字段) {Number} code 状态码
     * @apiSuccess (返回字段) {String} message  消息
     * @apiSuccess (返回字段) {Array} data  资源对象
     * @apiSuccess (返回字段) {Number} data.id  id
     * @apiSuccess (返回字段) {String} data.name  角色名称
     * @apiSuccess (返回字段) {Array} data.route_resource_ids  全选中和半选中id数组
     * @apiSuccess (返回字段) {Array} data.temp_route_resource_ids  全选中id数组
     *
     * @apiSuccessExample 成功示例
     * HTTP/1.1 200 Success
     * {
     *   "code":20000,
     *   "message":"SUCCESS",
     *   "data":{
     *     "id":1,
     *     "role_name":"超级管理员",
     *     "route_resource_ids":[
     *       "1",
     *       "2",
     *       "6",
     *       "7",
     *       "8",
     *       "9",
     *       "3",
     *       "4"],
     *     "temp_route_resource_ids":[
     *       "1",
     *       "2",
     *       "6",
     *       "7",
     *       "8",
     *       "9"]
     *   }
     * }
     *
     * @apiErrorExample 失败示例1
     * {
     *    "code": "10001"
     * }
     * @apiError (错误代码) 10001 数据验证失败
     */
    public function roleDetail(): Response {
        $request = $this->request;
        $request->filter(['trim']);
        $code    = 20000;
        $message = 'SUCCESS';
        try {
            $param = $request->param();
            try {
                validate(ValidateRole::class)->scene('detail')->check($param);
            } catch (ValidateException $e) {
                // 验证失败 输出错误信息
                $code = 10001;
                throw new Exception($e->getError());
            }
            $where = [
                'id' => $param['id'],
            ];
            $res = Db::name('role')->where($where)->find();
            if ($res) {
                $res['route_resource_ids']      = explode(',', $res['route_resource_ids']);
                $res['temp_route_resource_ids'] = explode(',', $res['temp_route_resource_ids']);
            }
            $data = $res;
        } catch (Exception $e) {
            $data    = null;
            $message = $e->getMessage();
        }
        return json(['code' => $code, 'message' => $message, 'data' => $data]);
    }

    /**
     * @api {put} /Role/roleEdit 角色编辑
     * @apiVersion 0.0.1
     * @apiName roleEdit
     * @apiGroup 角色
     * @apiHeader {String} X-token api-token
     *
     * @apiParam (参数) {String} id 角色id
     * @apiParam (参数) {String} role_name='empty string' 角色名称
     * @apiParam (参数) {Array} route_resource_ids 全选中和半选中权限ids
     * @apiParam (参数) {Array} temp_route_resource_ids 全选中权限ids
     * @apiParamExample {json} 请求示例
     * {
     *     "id":1,
     *     "role_name":"123",
     *     "route_resource_ids":[
     *         1,
     *         2,
     *         6,
     *         8,
     *         9,
     *         3,
     *         4],
     *     "temp_route_resource_ids":[
     *         1,
     *         2,
     *         6,
     *         8,
     *         9]
     * }
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
     * @apiError (错误代码) 10002 角色名称已存在
     * @apiError (错误代码) 10003 数据库写入失败
     */
    public function roleEdit(): Response {
        $request = $this->request;
        $request->filter(['trim']);
        $code    = 20000;
        $message = 'SUCCESS';
        try {
            $param = $request->param();
            try {
                validate(ValidateRole::class)->scene('edit')->check($param);
            } catch (ValidateException $e) {
                // 验证失败 输出错误信息
                $code = 10001;
                throw new Exception($e->getError());
            }
            $is_exist = Db::name("role")->where(
                [
                    ['role_name', '=', $param['role_name']],
                    ['id', '<>', $param['id']],
                ]
            )->find();
            if ($is_exist) {
                $code = 10002;
                throw new Exception('角色名称已存在');
            }
            $param['route_resource_ids']      = implode(',', $param['route_resource_ids']);
            $param['temp_route_resource_ids'] = implode(',', $param['temp_route_resource_ids']);

            $res = Db::name('role')->update($param);
            if ($res !== false) {
                $data = null;
            } else {
                $code = 10003;
                throw new Exception('保存失败');
            }
        } catch (Exception $e) {
            $data    = null;
            $message = $e->getMessage();
        }
        return json(['code' => $code, 'message' => $message, 'data' => $data]);
    }

    /**
     * @api {delete} /Role/roleDelete 角色删除
     * @apiVersion 0.0.1
     * @apiName roleDelete
     * @apiGroup 角色
     * @apiHeader {String} X-token api-token
     *
     * @apiParam (参数) {Array} ids 角色id数组
     * @apiParamExample {json} 请求示例
     * {
     *    "ids":[
     *        2]
     * }
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
     * @apiError (错误代码) 10002 删除的角色中有角色已被使用，不能删除
     * @apiError (错误代码) 10003 数据库删除失败
     */
    public function roleDelete(): Response {
        $request = $this->request;
        $request->filter(['trim']);
        $code    = 20000;
        $message = 'SUCCESS';
        try {
            $param = $request->param();
            try {
                validate(ValidateRole::class)->scene('delete')->check($param);
            } catch (ValidateException $e) {
                // 验证失败 输出错误信息
                $code = 10001;
                throw new Exception($e->getError());
            }

            //查找是否有管理员使用这个角色则提示不能删除
            $admins     = Db::name('admin')->column('role_ids');
            $is_setting = false;
            foreach ($admins as $key => $value) {
                if (count(array_intersect($param['ids'], explode(',', $value))) > 0) {
                    $is_setting = true;
                    break;
                }
            }
            if ($is_setting) {
                $code = 10002;
                throw new Exception('删除的角色中有角色已被使用，不能删除');
            }
            $res = Db::name('role')
                ->where([['id', 'in', $param['ids']]])
                ->delete();
            if ($res > 0) {
                $data = null;
            } else {
                $code = 10003;
                throw new Exception('删除失败');
            }
        } catch (Exception $e) {
            $data    = null;
            $message = $e->getMessage();
        }
        return json(['code' => $code, 'message' => $message, 'data' => $data]);
    }

    /**
     * @api {get} /Role/roleAllList 获取所有角色选项
     * @apiVersion 0.0.1
     * @apiName roleAllList
     * @apiGroup 角色
     * @apiHeader {String} X-token api-token
     *
     * @apiSuccess (返回字段) {Number} code 状态码
     * @apiSuccess (返回字段) {String} message  消息
     * @apiSuccess (返回字段) {Array}  data  数据
     * @apiSuccess (返回字段) {Number}  data.id  角色id
     * @apiSuccess (返回字段) {String}  data.role_name  角色名称
     *
     * @apiSuccessExample 成功示例
     * HTTP/1.1 200 Success
     * {
     *     "code": 20000,
     *     "message": "SUCCESS",
     *     "data": [
     *         {
     *             "id": 1,
     *             "role_name": "超级管理员"
     *         },
     *         {
     *             "id": 2,
     *             "role_name": "访客"
     *         }
     *     ]
     * }
     */
    public function roleAllList(): Response {
        $request = $this->request;
        $request->filter(['trim']);
        $code    = 20000;
        $message = 'SUCCESS';
        try {
            $param = $request->param();
            $list  = Db::name('role')->field('id,role_name')->select();
            $data  = $list;
        } catch (Exception $e) {
            $data    = null;
            $message = $e->getMessage();
        }
        return json(['code' => $code, 'message' => $message, 'data' => $data]);
    }
}