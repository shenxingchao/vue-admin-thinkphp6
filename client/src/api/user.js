import request from '@/utils/request'

export function login(ruleForm) {
  return request({
    url: '/User/login',
    method: 'post',
    data: ruleForm
  })
}

//分割线，下面这些接口是已经登录后请求的

export function getInfo(params) {
  return request({
    url: '/UserCenter/getInfo',
    method: 'get',
    params: params
  })
}

export function logout(ruleForm) {
  return request({
    url: '/UserCenter/logout',
    method: 'post',
    data: ruleForm
  })
}

export function getPermissionRouter(params) {
  return request({
    url: '/UserCenter/getPermissionRouter',
    method: 'post',
    params: params
  })
}
