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
  //管理员管理 level2
  Admin: () => import('@/views/permission/admin/admin'),
  //管理员列表 level3
  AdminList: () => import('@/views/permission/admin/admin_list'),
  //管理员添加 level3
  AdminAdd: () => import('@/views/permission/admin/admin_add'),
  //管理员编辑 level3
  AdminEdit: () => import('@/views/permission/admin/admin_edit')
}
export default map
