/**
 * 组件映射文件
 */
//Layout
import Layout from '@/layout'
export const map = {
  //基础布局 level1
  Layout: Layout,
  //控制面板 level2
  Dashboard: () => import('@/views/dashboard/index'),
  //文章管理
  Article: () => Layout,
  //文章列表
  ArticleList: () => import('@/views/article/article-list'),
  //文章添加
  ArticleAdd: () => import('@/views/article/article-add'),
  //文章编辑
  ArticleEdit: () => import('@/views/article/article-edit'),
  //权限管理 level1
  Permission: () => Layout,
  //路由资源管理 level2
  RouteResource: () =>
    import('@/views/permission/route_resource/route_resource'),
  //路由资源列表 level3
  RouteResourceList: () =>
    import('@/views/permission/route_resource/route_resource_list'),
  //路由资源添加 level3
  RouteResourceAdd: () =>
    import('@/views/permission/route_resource/route_resource_add'),
  //路由资源编辑 level3
  RouteResourceEdit: () =>
    import('@/views/permission/route_resource/route_resource_edit'),
  //角色管理 level2
  Role: () => import('@/views/permission/role/role'),
  //角色列表 level3
  RoleList: () => import('@/views/permission/role/role_list'),
  //角色添加 level3
  RoleAdd: () => import('@/views/permission/role/role_add'),
  //角色编辑 level3
  RoleEdit: () => import('@/views/permission/role/role_edit'),
  //管理员管理 level1
  Admin: () => import('@/views/permission/admin/admin')
}
export default map
