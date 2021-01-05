<?php
declare (strict_types = 1);

namespace app\admin\controller;

use app\admin\validate\RouteResource as ValidateRouteResource;
use think\Exception;
use think\exception\ValidateException;
use think\facade\Db;
use think\Response;

class RouteResource extends BaseController {

    /**
     * 递归获取分类配置 树形显示 一维数组
     * @param  arr $data data
     * @param  int $parent_id pid
     * @param  int $level level
     * @return arr             result
     */
    private function getTree($data, $parent_id = 0, $level = 0): array{
        static $tree = array();
        foreach ($data as $key => $value) {
            if ($value['parent_id'] == $parent_id) {
                $label  = str_repeat('——', $level * 2) . $value['title'];
                $tree[] = [
                    'label' => $label,
                    'value' => $value['id'],
                ];
                unset($data[$key]);
                $this->getTree($data, $value['id'], $level + 1);
            }
        }
        return $tree;
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
                $items[$value['parent_id']]['children'][] = &$items[$key];
                //引用传值  当items更改时，tree里面的items也会更改
            } else {
                $tree[] = &$items[$key];
            }
        }
        return $tree;
    }

    /**
     * @api {post} /RouteResource/routeResourceAdd 路由资源添加
     * @apiVersion 0.0.1
     * @apiName routeResourceAdd
     * @apiGroup 路由资源
     *
     * @apiParam (参数) {String} title='empty string' 菜单名称
     * @apiParam (参数) {String} path='empty string' 路由地址
     * @apiParam (参数) {String} name='empty string' 路由名称
     * @apiParam (参数) {String} component='empty string' 映射组件名称
     * @apiParam (参数) {String} [redirect='empty string'] 重定向路由
     * @apiParam (参数) {Bool} [always_show=false] 显示根节点
     * @apiParam (参数) {Bool} [hidden=false] 菜单隐藏路由
     * @apiParam (参数) {String} [icon='empty string'] svg图标
     * @apiParam (参数) {Bool} [affix=false] 菜单标签栏固定
     * @apiParam (参数) {Bool} [breadcrumb=true] 面包屑显示菜单
     * @apiParam (参数) {Number} parent_id=0 上级路由id
     * @apiParamExample {json} 请求示例
     *{
     *  "title":"首页",
     *  "path":"/",
     *  "name":"Index",
     *  "component":"Layout",
     *  "redirect":"/dashboard",
     *  "always_show":false,
     *  "hidden":false,
     *  "icon":"",
     *  "affix":false,
     *  "breadcrumb":false,
     *  "parent_id":0
     *}

     * @apiSuccess (返回字段) {Number} code 状态码
     * @apiSuccess (返回字段) {String} message  消息
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
     *    'code': '10001'
     * }
     * @apiErrorExample 失败示例2
     * {
     *    'code': '10002'
     * }
     * @apiErrorExample 失败示例3
     * {
     *    'code': '10003'
     * }
     * @apiError (错误代码) 10001 数据验证失败
     * @apiError (错误代码) 10002 菜单名称已存在
     * @apiError (错误代码) 10003 数据库写入失败
     */
    public function routeResourceAdd(): Response {
        $request = $this->request;
        $request->filter(['trim']);
        $code    = 20000;
        $message = 'SUCCESS';
        try {
            $param = $request->param();
            try {
                validate(ValidateRouteResource::class)->scene('add')->check($param);
            } catch (ValidateException $e) {
                // 验证失败 输出错误信息
                $code = 10001;
                throw new Exception($e->getError());
            }
            $is_exist = Db::name("route_resource")->where(['title' => $param['title']])->find();
            if ($is_exist) {
                $code = 10002;
                throw new Exception('菜单名称已存在');
            }
            $res = Db::name('route_resource')->insert($param);
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
     * @api {get} /RouteResource/routeResourceOptions 获取路由资源配置
     * @apiVersion 0.0.1
     * @apiName routeResourceOptions
     * @apiGroup 路由资源
     *
     * @apiParam (参数) {Number} [id] 不是当前的编辑的ID
     * @apiParamExample {json} 请求示例
     * {
     *  'id': 1,
     * }
     *
     * @apiSuccess (返回字段) {Number} code 状态码
     * @apiSuccess (返回字段) {String} message  消息
     *
     * @apiSuccessExample 成功示例
     * HTTP/1.1 200 Success
     * {
     *     "code": 20000,
     *     "message": "SUCCESS",
     *     "data": [
     *         {
     *             "label": "首页",
     *             "value": 1
     *         },
     *         {
     *             "label": "————控制台",
     *             "value": 2
     *         },
     *         {
     *             "label": "权限管理",
     *             "value": 3
     *         },
     *         {
     *             "label": "————路由资源管理",
     *             "value": 4
     *         },
     *         {
     *             "label": "————————路由资源",
     *             "value": 5
     *         },
     *         {
     *             "label": "————————路由资源添加",
     *             "value": 6
     *         },
     *     ]
     * }
     */
    public function routeResourceOptions(): Response {
        $request = $this->request;
        $request->filter(['trim']);
        $code    = 20000;
        $message = 'SUCCESS';
        try {
            $param = $request->param();
            $where = [];
            if (isset($param['id'])) {
                $where[] = ['id', '<>', $param['id']];
            }
            $route_resource_options = Db::name('route_resource')->where($where)->select();
            $route_resource_options = $this->getTree($route_resource_options);
            $data                   = $route_resource_options;
        } catch (Exception $e) {
            $data    = null;
            $message = $e->getMessage();
        }
        return json(['code' => $code, 'message' => $message, 'data' => $data]);
    }

    /**
     * @api {get} /RouteResource/routeResourceList 获取路由资源列表
     * @apiVersion 0.0.1
     * @apiName routeResourceList
     * @apiGroup 路由资源
     *
     * @apiSuccess (返回字段) {Number} code 状态码
     * @apiSuccess (返回字段) {String} message  消息
     * @apiSuccess (返回字段) {Array} data  资源数组无限极
     *
     * @apiSuccessExample 成功示例
     * HTTP/1.1 200 Success
     * {
     *     "code": 20000,
     *     "message": "SUCCESS",
     *     "data": [
     *         {
     *             "id": 1,
     *             "title": "首页",
     *             "path": "/",
     *             "name": "Index",
     *             "component": "Layout",
     *             "redirect": "/dashboard",
     *             "always_show": 0,
     *             "hidden": 0,
     *             "icon": "",
     *             "affix": 0,
     *             "breadcrumb": 0,
     *             "parent_id": 0,
     *             "children": [
     *                 {
     *                     "id": 2,
     *                     "title": "控制台",
     *                     "path": "dashboard",
     *                     "name": "Dashboard",
     *                     "component": "Dashboard",
     *                     "redirect": "",
     *                     "always_show": 0,
     *                     "hidden": 0,
     *                     "icon": "dashboard",
     *                     "affix": 1,
     *                     "breadcrumb": 1,
     *                     "parent_id": 1
     *                 }
     *             ]
     *         },
     *     ]
     * }
     */
    public function routeResourceList(): Response {
        $request = $this->request;
        $request->filter(['trim']);
        $code    = 20000;
        $message = 'SUCCESS';
        try {
            $param                  = $request->param();
            $route_resource_options = Db::name('route_resource')->select();
            $route_resource_options = $this->getTreeArr($route_resource_options);
            $data                   = $route_resource_options;
        } catch (Exception $e) {
            $data    = null;
            $message = $e->getMessage();
        }
        return json(['code' => $code, 'message' => $message, 'data' => $data]);
    }

    /**
     * @api {get} /RouteResource/routeResourceDetail 路由资源详情
     * @apiVersion 0.0.1
     * @apiName routeResourceDetail
     * @apiGroup 路由资源
     *
     * @apiParam (参数) {Number} id 路由资源id
     * @apiParamExample {json} 请求示例
     * {
     *  'id': 1,
     * }
     * @apiSuccess (返回字段) {Number} code 状态码
     * @apiSuccess (返回字段) {String} message  消息
     * @apiSuccess (返回字段) {Array} data  资源对象
     * @apiSuccess (返回字段) {Number} id  id
     * @apiSuccess (返回字段) {String} title  菜单名称
     * @apiSuccess (返回字段) {String} path  路由地址
     * @apiSuccess (返回字段) {String} name  路由名称
     * @apiSuccess (返回字段) {String} component  映射组件名称
     * @apiSuccess (返回字段) {String} redirect  路由重定向
     * @apiSuccess (返回字段) {Boolean} always_show  显示根节点
     * @apiSuccess (返回字段) {Boolean} hidden  菜单隐藏路由
     * @apiSuccess (返回字段) {String} icon  svg图标
     * @apiSuccess (返回字段) {Boolean} affix  菜单标签栏固定
     * @apiSuccess (返回字段) {Boolean} breadcrumb  面包屑显示菜单
     * @apiSuccess (返回字段) {Number} parent_id  资源对象
     *
     * @apiSuccessExample 成功示例
     * HTTP/1.1 200 Success
     * {
     *  "code":20000,
     *  "message":"SUCCESS",
     *  "data":{
     *      "id":7,
     *      "title":"大三大四",
     *      "path":"1",
     *      "name":"2",
     *      "component":"3",
     *      "redirect":"",
     *      "always_show":false,
     *      "hidden":false,
     *      "icon":"",
     *      "affix":false,
     *      "breadcrumb":true,
     *      "parent_id":3
     *  }
     * }
     *
     * @apiErrorExample 失败示例1
     * {
     *    'code': '10001'
     * }
     * @apiError (错误代码) 10001 数据验证失败
     */
    public function routeResourceDetail(): Response {
        $request = $this->request;
        $request->filter(['trim']);
        $code    = 20000;
        $message = 'SUCCESS';
        try {
            $param = $request->param();
            try {
                validate(ValidateRouteResource::class)->scene('detail')->check($param);
            } catch (ValidateException $e) {
                // 验证失败 输出错误信息
                $code = 10001;
                throw new Exception($e->getError());
            }
            $where = [
                'id' => $param['id'],
            ];
            $res = Db::name('route_resource')->where($where)->find();
            if ($res) {
                $res['always_show'] ? $res['always_show'] = true : $res['always_show'] = false;
                $res['breadcrumb'] ? $res['breadcrumb']   = true : $res['breadcrumb']   = false;
                $res['affix'] ? $res['affix']             = true : $res['affix']             = false;
                $res['hidden'] ? $res['hidden']           = true : $res['hidden']           = false;
            }
            $data = $res;
        } catch (Exception $e) {
            $data    = null;
            $message = $e->getMessage();
        }
        return json(['code' => $code, 'message' => $message, 'data' => $data]);
    }

    /**
     * @api {post} /RouteResource/routeResourceEdit 路由资源编辑
     * @apiVersion 0.0.1
     * @apiName routeResourceEdit
     * @apiGroup 路由资源
     *
     * @apiParam (参数) {Number} id 路由资源id
     * @apiParam (参数) {String} title='empty string' 菜单名称
     * @apiParam (参数) {String} path='empty string' 路由地址
     * @apiParam (参数) {String} name='empty string' 路由名称
     * @apiParam (参数) {String} component='empty string' 映射组件名称
     * @apiParam (参数) {String} [redirect='empty string'] 重定向路由
     * @apiParam (参数) {Bool} [always_show=false] 显示根节点
     * @apiParam (参数) {Bool} [hidden=false] 菜单隐藏路由
     * @apiParam (参数) {String} [icon='empty string'] svg图标
     * @apiParam (参数) {Bool} [affix=false] 菜单标签栏固定
     * @apiParam (参数) {Bool} [breadcrumb=true] 面包屑显示菜单
     * @apiParam (参数) {Number} parent_id=0 上级路由id
     * @apiParamExample {json} 请求示例
     *{
     *  "id":1,
     *  "title":"首页",
     *  "path":"/",
     *  "name":"Index",
     *  "component":"Layout",
     *  "redirect":"/dashboard",
     *  "always_show":false,
     *  "hidden":false,
     *  "icon":"",
     *  "affix":false,
     *  "breadcrumb":false,
     *  "parent_id":0
     *}
     * @apiSuccess (返回字段) {Number} code 状态码
     * @apiSuccess (返回字段) {String} message  消息
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
     *    'code': '10001'
     * }
     * @apiErrorExample 失败示例2
     * {
     *    'code': '10002'
     * }
     * @apiErrorExample 失败示例3
     * {
     *    'code': '10003'
     * }
     * @apiError (错误代码) 10001 数据验证失败
     * @apiError (错误代码) 10002 菜单名称已存在
     * @apiError (错误代码) 10003 数据库写入失败
     */
    public function routeResourceEdit(): Response {
        $request = $this->request;
        $request->filter(['trim']);
        $code    = 20000;
        $message = 'SUCCESS';
        try {
            $param = $request->param();
            try {
                validate(ValidateRouteResource::class)->scene('edit')->check($param);
            } catch (ValidateException $e) {
                // 验证失败 输出错误信息
                $code = 10001;
                throw new Exception($e->getError());
            }
            $is_exist = Db::name("route_resource")->where([
                ['title', '=', $param['title']],
                ['id', '<>', $param['id']],
            ])->find();
            if ($is_exist) {
                $code = 10002;
                throw new Exception('菜单名称已存在');
            }
            $res = Db::name('route_resource')->update($param);
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
     * @api {get} /RouteResource/routeResourceDelete 路由资源删除
     * @apiVersion 0.0.1
     * @apiName routeResourceDelete
     * @apiGroup 路由资源
     *
     * @apiParam (参数) {Number} id 用户id
     * @apiParamExample {json} 请求示例
     * {
     *  'id': 1,
     * }
     * @apiSuccess (返回字段) {Number} code 状态码
     * @apiSuccess (返回字段) {String} message  消息
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
     *    'code': '10001'
     * }
     * @apiErrorExample 失败示例2
     * {
     *    'code': '10002'
     * }
     * @apiErrorExample 失败示例3
     * {
     *    'code': '10003'
     * }
     * @apiErrorExample 失败示例3
     * {
     *    'code': '10004'
     * }
     * @apiError (错误代码) 10001 数据验证失败
     * @apiError (错误代码) 10002 该菜单下还有子菜单，不能删除
     * @apiError (错误代码) 10003 角色已使用该路由资源
     * @apiError (错误代码) 10004 数据库删除失败
     */
    public function routeResourceDelete(): Response {
        $request = $this->request;
        $request->filter(['trim']);
        $code    = 20000;
        $message = 'SUCCESS';
        try {
            $param = $request->param();
            try {
                validate(ValidateRouteResource::class)->scene('delete')->check($param);
            } catch (ValidateException $e) {
                // 验证失败 输出错误信息
                $code = 10001;
                throw new Exception($e->getError());
            }
            //查找是否还有子类，角色是否拥有这个菜单资源，提示不能删除
            $hasChild = Db::name('route_resource')
                ->where(['parent_id' => $param['id']])->find();
            if ($hasChild) {
                $code = 10002;
                throw new Exception('该菜单下还有子菜单，不能删除');
            }
            //*wait code = 10003 角色表 权限字段有用到 也不能删除
            $res = Db::name('route_resource')
                ->where(['id' => $param['id']])
                ->delete();
            if ($res > 0) {
                $data = null;
            } else {
                $code = 10004;
                throw new Exception('删除失败');
            }
        } catch (Exception $e) {
            $data    = null;
            $message = $e->getMessage();
        }
        return json(['code' => $code, 'message' => $message, 'data' => $data]);
    }
}