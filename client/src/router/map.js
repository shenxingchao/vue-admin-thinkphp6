/**
 * 组件映射文件
 */
//Layout
import Layout from '@/layout'
export const map = {
  Layout: Layout, // 基础布局 level1
  Dashboard: () => import('@/views/dashboard/index'), // 控制面板 level2
  Article: () => Layout, //文章管理
  ArticleList: () => import('@/views/article/article-list'), // 文章列表
  ArticleAdd: () => import('@/views/article/article-add'), // 文章添加
  ArticleEdit: () => import('@/views/article/article-edit'), // 文章编辑
  Permission: () => Layout, //权限管理 level1
  RouteResource: () =>
    import('@/views/permission/route_resource/route_resource'), // 路由资源管理 level2
  RouteResourceList: () =>
    import('@/views/permission/route_resource/route_resource_list'), // 路由资源列表 level3
  RouteResourceAdd: () =>
    import('@/views/permission/route_resource/route_resource_add'), // 路由资源添加 level3
  RouteResourceEdit: () =>
    import('@/views/permission/route_resource/route_resource_edit'), // 路由资源编辑 level3
  Role: () => import('@/views/permission/role/role'), // 角色管理 level2
  RoleList: () => import('@/views/permission/role/role_list') // 角色列表 level2
}
export default map
