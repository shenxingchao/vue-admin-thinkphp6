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
      getPermissionRouter({ roles: roles }).then(res => {
        asyncRouterMap = res.data.concat({
          path: '*',
          redirect: '/404',
          hidden: true
        }) //404 must be put end
        // 组建映射
        const asyncRouterMapRes = routerMapComponet(asyncRouterMap)
        commit('SET_ROUTES', asyncRouterMapRes)
        resolve(asyncRouterMapRes)
      })
    })
  }
}

export default {
  namespaced: true,
  state,
  mutations,
  actions
}
