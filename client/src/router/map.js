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
    import('@/views/permission/route-resource/route-resource'), // 路由资源管理 level2
  RouteResourceList: () =>
    import('@/views/permission/route-resource/route-resource-list') // 路由资源列表 level3
}
export default map
