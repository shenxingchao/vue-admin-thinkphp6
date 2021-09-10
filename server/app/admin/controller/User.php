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

    /**
     * @api {get} /User/getInfo 获取用户信息
     * @apiVersion 0.0.1
     * @apiName getInfo
     * @apiGroup 用户
     * @apiHeader {String} X-token api-token
     *
     * @apiSuccess (返回字段) {Number} code 状态码
     * @apiSuccess (返回字段) {String} message  消息
     * @apiSuccess (返回字段) {Array}  data  数据
     * @apiSuccess (返回字段) {Number}  data.id  用户id
     * @apiSuccess (返回字段) {String}  data.username  用户名
     * @apiSuccess (返回字段) {Array}  data.roles  角色id数组
     * @apiSuccess (返回字段) {String}  data.avatar  头像
     *
     * @apiSuccessExample 成功示例
     * HTTP/1.1 200 Success
     * {
     *    "code": 20000,
     *    "message": "SUCCESS",
     *    "data": {
     *        "id": 1,
     *        "username": "admin",
     *        "roles": ["1"],
     *        "avatar": "https:\/\/wpimg.wallstcn.com\/f778738c-e4f8-4870-b634-56703b4acafe.gif"
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
     * @apiError (错误代码) 1001 token不存在
     * @apiError (错误代码) 1002 账号已禁用
     */
    public function getInfo(): Response {
        $request = $this->request;
        $request->filter(['trim']);
        $code    = 20000;
        $message = 'SUCCESS';
        try {
            $param = $request->param();
            if (!isset($this->request->token)) {
                $code = 10001;
                throw new Exception('token不存在');
            }

            $user = Db::name('admin')->where(['token' => $this->request->token, 'status' => 1])->field('id,username,role_ids')->find();
            if (!$user) {
                $code = 10002;
                throw new Exception('账号已禁用');
            }
            $user['roles']  = explode(',', $user['role_ids']);
            $user['avatar'] = 'https://wpimg.wallstcn.com/f778738c-e4f8-4870-b634-56703b4acafe.gif';
            unset($user['role_ids']);
            $data = $user;
        } catch (Exception $e) {
            $data    = null;
            $message = $e->getMessage();
        }
        return json(['code' => $code, 'message' => $message, 'data' => $data]);
    }

    /**
     * @api {put} /User/logout 用户退出登录
     * @apiVersion 0.0.1
     * @apiName logout
     * @apiGroup 用户
     * @apiHeader {String} X-token api-token
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
     * @apiError (错误代码) 10001 数据验证失败
     * @apiError (错误代码) 10002 数据库写入失败
     */
    public function logout(): Response {
        $request = $this->request;
        $request->filter(['trim']);
        $code    = 20000;
        $message = 'SUCCESS';
        try {
            $param = $request->param();
            if (!isset($this->request->token)) {
                $code = 10001;
                throw new Exception('token不存在');
            }

            $res = Db::name('admin')->where(['token' => $this->request->token])->update(['token' => '', 'expire_time' => 0]);
            if ($res !== false) {
                $data = null;
            } else {
                $code = 10002;
                throw new Exception('注销登录失败');
            }
        } catch (Exception $e) {
            $data    = null;
            $message = $e->getMessage();
        }
        return json(['code' => $code, 'message' => $message, 'data' => $data]);
    }

    /**
     * @api {get} /User/getPermissionRouter 获取用户路由权限
     * @apiVersion 0.0.1
     * @apiName getPermissionRouter
     * @apiGroup 用户
     * @apiHeader {String} X-token api-token
     *
     * @apiParam (参数) {Array} roles 角色id数组
     * @apiParamExample {json} 请求示例
     * {
     *  "roles": [
     *      1,
     *      2],
     * }
     * @apiSuccess (返回字段) {Number} code 状态码
     * @apiSuccess (返回字段) {String} message  消息
     * @apiSuccess (返回字段) {Array} data  资源对象
     * @apiSuccess (返回字段) {String} data.path  路由路径
     * @apiSuccess (返回字段) {String} data.name 路由名称
     * @apiSuccess (返回字段) {String} data.component 路由映射组件名称
     * @apiSuccess (返回字段) {String} data.redirect 重定向地址
     * @apiSuccess (返回字段) {Boolean} data.alwaysShow  显示根节点
     * @apiSuccess (返回字段) {Boolean} data.hidden  菜单是否显示
     * @apiSuccess (返回字段) {Object} data.meta  meta
     * @apiSuccess (返回字段) {String} data.meta.title 菜单名称
     * @apiSuccess (返回字段) {String} data.meta.icon svg图标
     * @apiSuccess (返回字段) {Boolean} data.meta.affix 标签栏固定
     * @apiSuccess (返回字段) {Boolean} data.meta.breadcrumb 面包屑导航显示
     *
     * @apiSuccessExample 成功示例
     * HTTP/1.1 200 Success
     * {
     *    "code":20000,
     *    "message":"SUCCESS",
     *    "data":[
     *    {
     *        "path":"\/",
     *        "name":"Index",
     *        "component":"Layout",
     *        "redirect":"\/dashboard",
     *        "alwaysShow":false,
     *        "hidden":false,
     *        "meta":{
     *        "title":"",
     *        "icon":"",
     *        "affix":false,
     *        "breadcrumb":false
     *        },
     *        "children":[
     *        {
     *            "path":"dashboard",
     *            "name":"Dashboard",
     *            "component":"Dashboard",
     *            "alwaysShow":false,
     *            "hidden":false,
     *            "meta":{
     *            "title":"控制台",
     *            "icon":"dashboard",
     *            "affix":true,
     *            "breadcrumb":true
     *            }
     *        }]
     *    },
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
     * @apiErrorExample 失败示例4
     * {
     *    "code": "10004"
     * }
     * @apiErrorExample 失败示例5
     * {
     *    "code": "10005"
     * }
     * @apiError (错误代码) 10001 token不存在
     * @apiError (错误代码) 10002 数据验证失败
     * @apiError (错误代码) 10003 未找到角色1 账号是不一致
     * @apiError (错误代码) 10004 当前请求角色不是该账号下角色
     * @apiError (错误代码) 10005 未找到角色2
     */
    public function getPermissionRouter(): Response {
        $request = $this->request;
        $request->filter(['trim']);
        $code    = 20000;
        $message = 'SUCCESS';
        try {
            $param = $request->param();
            if (!isset($this->request->token)) {
                $code = 10001;
                throw new Exception('token不存在');
            }
            try {
                validate(ValidateUser::class)->scene('permission_router')->check($param);
            } catch (ValidateException $e) {
                // 验证失败 输出错误信息
                $code = 10002;
                throw new Exception($e->getError());
            }
            //验证一下账号是否一致
            $role_ids = Db::name('admin')->where(['token' => $this->request->token])->value('role_ids');
            if (!$role_ids) {
                $code = 10003;
                throw new Exception('未找到角色1');
            }
            $role_ids = explode(',', $role_ids);
            foreach ($role_ids as $key => $value) {
                $role_ids[$key] = intval($value);
            }
            if (count(array_diff($role_ids, $param['roles'])) !== 0) {
                $code = 10004;
                throw new Exception('当前请求角色不是该账号下角色');
            }

            $roles = Db::name('role')->where([
                ['id', 'in', $param['roles']],
            ])->select();
            if (!$roles) {
                $code = 10005;
                throw new Exception('未找到角色2');
            }

            $route_resource_str = '';
            foreach ($roles as $key => $value) {
                if ($value['route_resource_ids'] != '') {
                    $route_resource_str .= "," . $value['route_resource_ids'];
                }
            }
            $route_resource_ids = array_filter(array_unique(explode(',', $route_resource_str)));
            $route_resource     = Db::name('route_resource')->where([['id', 'in', $route_resource_ids]])->select();
            $route_resource     = $this->getTreeArr($route_resource);
            //组装返回权限的数据
            $data = $this->execute($route_resource);
        } catch (Exception $e) {
            $data    = null;
            $message = $e->getMessage();
        }
        return json(['code' => $code, 'message' => $message, 'data' => $data]);
    }

    /**
     * 获取无限极分类数组（直接是树形结构的数组）
     * @param $data
     * @return array
     */
    private function getTreeArr($data): array{
        //构造数据
        $items = array();
        //以分类的id为索引
        foreach ($data as $key => $value) {
            $items[$value['id']] = $value;
        }
        //第二部 遍历数据 生成树状结构
        $tree = array();
        foreach ($items as $key => $value) {
            if ($value['parent_id'] !== 0) { //不是顶级分类
                //把当前循环的value放入父节点下面
                $items[$value['parent_id']]['son'][] = &$items[$key];
                //引用传值  当items更改时，tree里面的items也会更改
            } else {
                $tree[] = &$items[$key];
            }
        }
        return $tree;
    }

    /**
     * 递归返回可使用格式的权限数组
     * @param $data
     * @return array
     */
    private function execute($data): array{
        $res = array();
        foreach ($data as $key => $value) {
            $arr = [
                'path'       => $value['path'],
                'name'       => $value['name'],
                'component'  => $value['component'],
                'redirect'   => $value['redirect'],
                'alwaysShow' => $value['always_show'] === 1 ? true : false,
                'hidden'     => $value['hidden'] === 1 ? true : false,
                'meta'       => [
                    'title'      => $value['path'] == '/' ? '' : $value['title'],
                    'icon'       => $value['icon'],
                    'affix'      => $value['affix'] === 1 ? true : false,
                    'breadcrumb' => $value['breadcrumb'] === 1 ? true : false,
                ],
            ];
            if ($arr['redirect'] == '') {
                unset($arr['redirect']);
            }
            if (!empty($value['son'])) {
                $arr['children'] = $this->execute($value['son']);
            }
            $res[$key] = $arr;
        }
        return $res;
    }
}