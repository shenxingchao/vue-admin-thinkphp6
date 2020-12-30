import router from './router'
import store from './store'
import { Message } from 'element-ui'
import NProgress from 'nprogress' // progress bar
import 'nprogress/nprogress.css' // progress bar style
import { getToken } from '@/utils/auth' // get token from cookie
import getPageTitle from '@/utils/get-page-title'

NProgress.configure({
  showSpinner: false
}) // NProgress Configuration

const whiteList = ['/login'] // no redirect whitelist

router.beforeEach(async (to, from, next) => {
  // start progress bar
  NProgress.start()

  // set page title
  document.title = getPageTitle(to.meta.title)

  // determine whether the user has logged in
  const hasToken = getToken()

  if (hasToken) {
    if (to.path === '/login') {
      //如果已经登录，则跳转到首页
      next({
        path: '/'
      })
      NProgress.done()
    } else {
      //路由权限验证开始
      const hasRoles = store.getters.roles && store.getters.roles.length > 0 //vuex是否有角色状态
      if (hasRoles) {
        next()
      } else {
        try {
          const { roles } = await store.dispatch('user/getInfo') //此处获取角色id数组

          const asyncRouterMapRes = await store.dispatch(
            'permission/generateRoutes',
            roles
          ) //根据角色id数组 创建动态路由
          router.options.routes = store.getters.addRoutes // bug 新增的路由添加到路由配置 不然菜单会不显示 看菜单组件用的是这个 router.options.routes
          router.addRoutes(asyncRouterMapRes) //异步动态映射路由添加到当前路由

          next({ ...to, replace: true }) //确保addRoutes已完成
        } catch (error) {
          // remove token and go to login page to re-login
          await store.dispatch('user/resetToken')
          Message.error(error || 'Has Error')
          next(`/login?redirect=${to.path}`)
          NProgress.done()
        }
      }
    }
  } else {
    /* has no token*/

    if (whiteList.indexOf(to.path) !== -1) {
      // in the free login whitelist, go directly
      next()
    } else {
      // other pages that do not have permission to access are redirected to the login page.
      next(`/login?redirect=${to.path}`)
      NProgress.done()
    }
  }
})

router.afterEach(() => {
  // finish progress bar
  NProgress.done()
})
