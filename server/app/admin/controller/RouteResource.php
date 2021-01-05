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
     *  "code":20000,
     *  "message":"SUCCESS",
     *  "data":null
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
                validate(ValidateRouteResource::class)->check($param);
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
     *  'id': 1，
     * }
     *
     * @apiSuccess (返回字段) {Number} code 状态码
     * @apiSuccess (返回字段) {String} message  消息
     *
     * @apiSuccessExample 成功示例
     * HTTP/1.1 200 Success
     * {
     *  "code":20000,
     *  "message":"SUCCESS",
     *  "data":null
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
     *  "code":20000,
     *  "message":"SUCCESS",
     *  "data":[{
     *      affix: 0
     *      always_show: 0
     *      breadcrumb: 0
     *      children: []
     *      component: "Layout"
     *      hidden: 0
     *      icon: ""
     *      id: 1
     *      name: "Index"
     *      parent_id: 0
     *      path: "/"
     *      redirect: "/dashboard"
     *      title: "首页"
     *  }]
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
     * @api {get} /routeResourceDetail 路由资源详情
     * @apiVersion 0.0.1
     * @apiName routeResourceDetail
     * @apiGroup 用户
     *
     * @apiParam (参数) {Number} id=2 用户id
     * @apiParam (参数) {Number} [age=4] 年龄(中括号表示可选)
     * @apiParamExample {json} 请求示例
     * {
     *  'id': 1，
     *  'age': 2
     * }
     * @apiSuccess (返回字段) {String} firstname 姓
     * @apiSuccess (返回字段) {String} lastname  名字
     *
     * @apiSuccessExample 成功示例
     * HTTP/1.1 200 Success
     * {
     *    'firstname': '张',
     *    'lastname': '三四'
     * }
     * @apiErrorExample 失败示例1
     * {
     *    'code': '1001'
     * }
     * @apiErrorExample 失败示例2
     * {
     *    'code': '1002'
     * }
     * @apiError (错误代码) 1001 内容1
     * @apiError (错误代码) 1002 内容2
     */
    public function routeResourceDetail() {
        $request = $this->request;
        $request->filter(['trim']);
        $code    = 20000;
        $message = 'SUCCESS';
        try {
            $param = $request->param();
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
}