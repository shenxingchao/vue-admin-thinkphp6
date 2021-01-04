<?php
declare (strict_types = 1);

namespace app\admin\controller;

use app\admin\validate\RouteResource as ValidateRouteResource;
use think\exception\ValidateException;
use think\facade\Db;

class RouteResource extends BaseController {
    /**
     * @api {post} /RouteResource/routeResourceAdd 添加权限资源
     * @apiVersion 0.0.1
     * @apiName routeResourceAdd
     * @apiGroup 权限资源
     *
     * @apiParam (参数) {String} title='empty string' 菜单名称
     * @apiParam (参数) {String} path='empty string' 路由地址
     * @apiParam (参数) {String} name='empty string' 路由名称
     * @apiParam (参数) {String} component='empty string' 映射组件名称
     * @apiParam (参数) {String} redirect='empty string' 重定向路由
     * @apiParam (参数) {Bool} [always_show=false] 显示根节点
     * @apiParam (参数) {Bool} [hidden=false] 菜单隐藏路由
     * @apiParam (参数) {String} icon='empty string' svg图标
     * @apiParam (参数) {Bool} [affix=false] 菜单标签栏固定
     * @apiParam (参数) {Bool} [breadcrumb=true] 面包屑显示菜单
     * @apiParam (参数) {Number} parent_id=0 上级路由id
     * @apiParamExample {json} 请求示例
     *{
     * "title":"首页",
     * "path":"/",
     * "name":"Index",
     * "component":"Layout",
     * "redirect":"/dashboard",
     * "always_show":false,
     * "hidden":false,
     * "icon":"",
     * "affix":false,
     * "breadcrumb":false,
     *  "parent_id":0
     *}

     * @apiSuccess (返回字段) {Number} code 状态码
     * @apiSuccess (返回字段) {String} message  消息
     *
     * @apiSuccessExample 成功示例
     * HTTP/1.1 200 Success
     *{
     *  "code":20000,
     *  "message":"添加成功"
     *}
     * @apiErrorExample 失败示例1
     * {
     *    'code': '10001'
     * }
     * @apiErrorExample 失败示例2
     * {
     *    'code': '10002'
     * }
     * @apiError (错误代码) 10001 数据验证失败
     * @apiError (错误代码) 10002 数据库写入失败
     */
    public function routeResourceAdd() {
        $post = $this->request->param();
        try {
            validate(ValidateRouteResource::class)->check($post);
        } catch (ValidateException $e) {
            // 验证失败 输出错误信息
            return json(['code' => 10001, 'message' => $e->getError()]);
        }
        $res = Db::name('route_resource')->insert($post);
        if ($res) {
            return json(['code' => 20000, 'message' => "添加成功"]);
        }
        return json(['code' => 10002, 'message' => "添加失败"]);
    }
}