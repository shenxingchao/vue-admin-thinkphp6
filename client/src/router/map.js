/**
 * 组件映射文件
 */
//Layout
import Layout from '@/layout'
export const map = {
  Layout: Layout, // 基础布局
  Dashboard: () => import('@/views/dashboard/index'), // 控制面板
  Article: () => Layout, //文章管理
  ArticleList: () => import('@/views/article/article-list'), // 文章列表
  ArticleAdd: () => import('@/views/article/article-add'), // 文章添加
  ArticleEdit: () => import('@/views/article/article-edit') // 文章编辑
}
export default map
