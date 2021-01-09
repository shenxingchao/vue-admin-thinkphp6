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
                      title: '路由资源列表',
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
                      icon: '',
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
                      icon: '',
                      affix: false,
                      breadcrumb: true
                    }
                  }
                ]
              },
              {
                path: 'role',
                name: 'Role',
                component: 'Role',
                redirect: '/permission/role/role_list',
                alwaysShow: false,
                hidden: false,
                meta: {
                  title: '角色管理',
                  icon: '',
                  affix: false,
                  breadcrumb: false
                },
                children: [
                  {
                    path: 'role_list',
                    name: 'RoleList',
                    component: 'RoleList',
                    alwaysShow: false,
                    hidden: false,
                    meta: {
                      title: '角色列表',
                      icon: 'table',
                      affix: false,
                      breadcrumb: true
                    }
                  },
                  {
                    path: 'role_add',
                    name: 'RoleAdd',
                    component: 'RoleAdd',
                    alwaysShow: false,
                    hidden: true,
                    meta: {
                      title: '角色添加',
                      icon: '',
                      affix: false,
                      breadcrumb: true
                    }
                  },
                  {
                    path: 'role_edit',
                    name: 'RoleEdit',
                    component: 'RoleEdit',
                    alwaysShow: false,
                    hidden: true,
                    meta: {
                      title: '角色编辑',
                      icon: '',
                      affix: false,
                      breadcrumb: true
                    }
                  }
                ]
              },
              {
                path: 'admin',
                name: 'Admin',
                component: 'Admin',
                redirect: '/permission/admin/admin_list',
                alwaysShow: false,
                hidden: false,
                meta: {
                  title: '管理员管理',
                  icon: '',
                  affix: false,
                  breadcrumb: false
                },
                children: [
                  {
                    path: 'admin_list',
                    name: 'AdminList',
                    component: 'AdminList',
                    alwaysShow: false,
                    hidden: false,
                    meta: {
                      title: '管理员列表',
                      icon: 'table',
                      affix: false,
                      breadcrumb: true
                    }
                  },
                  {
                    path: 'admin_add',
                    name: 'AdminAdd',
                    component: 'AdminAdd',
                    alwaysShow: false,
                    hidden: true,
                    meta: {
                      title: '管理员添加',
                      icon: '',
                      affix: false,
                      breadcrumb: true
                    }
                  },
                  {
                    path: 'admin_edit',
                    name: 'AdminEdit',
                    component: 'AdminEdit',
                    alwaysShow: false,
                    hidden: true,
                    meta: {
                      title: '管理员编辑',
                      icon: '',
                      affix: false,
                      breadcrumb: true
                    }
                  }
                ]
              }
            ]
          }
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
