import { constantRoutes } from '@/router'
// 导入要映射的组件
import map from '@/router/map'
// 导入后端根据角色请求动态路由方法
import { getPermissionRouter } from '../../api/user'

/**
 * 递归映射数组
 * @param {未映射路由数组} asyncRouterMap
 */
function routerMapComponet(asyncRouterMap) {
  if (typeof asyncRouterMap === 'undefined') {
    return
  }
  asyncRouterMap.forEach((value, index) => {
    if (typeof value.component === 'string') {
      if (typeof map[value.component] === 'undefined') {
        asyncRouterMap.splice(index, 1)
      }
      value.component = map[value.component]
      routerMapComponet(value.children)
    }
  })
  return asyncRouterMap
}

const state = {
  routes: [],
  addRoutes: []
}

const mutations = {
  SET_ROUTES: (state, routes) => {
    state.addRoutes = routes
    state.routes = constantRoutes.concat(routes)
  }
}

const actions = {
  generateRoutes({ commit }, roles) {
    return new Promise(resolve => {
      let asyncRouterMap
      asyncRouterMap = []
      // getPermissionRouter({ roles: roles }).then(res => {
      let res = {
        message: 'success',
        code: 20000,
        data: [
          {
            path: '/',
            name: 'Index',
            component: 'Layout',
            redirect: '/dashboard',
            alwaysShow: false,
            hidden: false,
            meta: {
              title: '', //标题为空 取权限时置 如果是/ 置为''
              icon: '',
              affix: false,
              breadcrumb: false
            },
            children: [
              {
                path: 'dashboard',
                name: 'Dashboard',
                component: 'Dashboard',
                alwaysShow: false,
                hidden: false,
                meta: {
                  title: '控制台',
                  icon: 'dashboard',
                  affix: true,
                  breadcrumb: true
                }
              }
            ]
          },
          {
            path: '/permission',
            name: 'Permission',
            component: 'Layout',
            redirect: 'noRedirect',
            alwaysShow: true,
            hidden: false,
            meta: {
              title: '权限管理',
              icon: 'permission',
              affix: false,
              breadcrumb: true
            },
            children: [
              {
                path: 'route_resource',
                name: 'RouteResource',
                component: 'RouteResource',
                redirect: '/permission/route_resource/route_resource_list',
                alwaysShow: false,
                hidden: false,
                meta: {
                  title: '路由资源管理',
                  icon: '',
                  affix: false,
                  breadcrumb: false
                },
                children: [
                  {
                    path: 'route_resource_list',
                    name: 'RouteResourceList',
                    component: 'RouteResourceList',
                    alwaysShow: false,
                    hidden: false,
                    meta: {
                      title: '路由资源',
                      icon: 'table',
                      affix: false,
                      breadcrumb: true
                    }
                  },
                  {
                    path: 'route_resource_add',
                    name: 'RouteResourceAdd',
                    component: 'RouteResourceAdd',
                    alwaysShow: false,
                    hidden: true,
                    meta: {
                      title: '路由资源添加',
                      icon: 'table',
                      affix: false,
                      breadcrumb: true
                    }
                  },
                  {
                    path: 'route_resource_edit',
                    name: 'RouteResourceEdit',
                    component: 'RouteResourceEdit',
                    alwaysShow: false,
                    hidden: true,
                    meta: {
                      title: '路由资源编辑',
                      icon: 'table',
                      affix: false,
                      breadcrumb: true
                    }
                  }
                ]
              }
            ]
          }
          // {
          //   path: '/article',
          //   name: 'Article',
          //   component: 'Layout',
          //   redirect: 'noRedirect',
          //   alwaysShow: true,
          //   hidden: false,
          //   meta: {
          //     title: '文章管理',
          //     icon: 'table'
          //   },
          //   children: [
          //     {
          //       path: 'article-list',
          //       name: 'ArticleList',
          //       component: 'ArticleList',
          //       alwaysShow: false,
          //       hidden: false,
          //       meta: {
          //         title: '文章列表',
          //         icon: 'table'
          //       }
          //     },
          //     {
          //       path: 'article-add',
          //       name: 'ArticleAdd',
          //       alwaysShow: false,
          //       hidden: true,
          //       component: 'ArticleAdd',
          //       meta: {
          //         title: '文章添加'
          //       }
          //     },
          //     {
          //       path: 'article-edit',
          //       name: 'ArticleEdit',
          //       alwaysShow: false,
          //       hidden: true,
          //       component: 'ArticleEdit',
          //       meta: {
          //         title: '文章编辑'
          //       }
          //     }
          //   ]
          // }
        ]
      }
      asyncRouterMap = res.data.concat({
        path: '*',
        redirect: '/404',
        hidden: true
      }) //404 must be put end
      // 组建映射
      const asyncRouterMapRes = routerMapComponet(asyncRouterMap)
      commit('SET_ROUTES', asyncRouterMapRes)
      resolve(asyncRouterMapRes)
      // })
    })
  }
}

export default {
  namespaced: true,
  state,
  mutations,
  actions
}
