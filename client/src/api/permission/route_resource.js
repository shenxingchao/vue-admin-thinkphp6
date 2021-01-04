import request from '@/utils/request'

//路由资源添加
export function routeResourceAdd(ruleForm) {
  return request({
    url: '/RouteResource/routeResourceAdd',
    method: 'post',
    data: ruleForm
  })
}

//路由资源上级路由选项
export function routeResourceOptions(params) {
  return request({
    url: '/RouteResource/routeResourceOptions',
    method: 'get',
    params: params
  })
}

//路由资源列表
export function routeResourceList(params) {
  return request({
    url: '/RouteResource/routeResourceList',
    method: 'get',
    params: params
  })
}
