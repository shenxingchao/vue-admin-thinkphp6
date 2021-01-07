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

//路由资源编辑
export function routeResourceEdit(ruleForm) {
  return request({
    url: '/RouteResource/routeResourceEdit',
    method: 'put',
    data: ruleForm
  })
}

//路由资源详情
export function routeResourceDetail(params) {
  return request({
    url: '/RouteResource/routeResourceDetail',
    method: 'get',
    params: params
  })
}

//路由资源删除
export function routeResourceDelete(ruleForm) {
  return request({
    url: '/RouteResource/routeResourceDelete',
    method: 'delete',
    data: ruleForm
  })
}

//路由资源节点树
export function routeResourceNodes(params) {
  return request({
    url: '/RouteResource/routeResourceNodes',
    method: 'get',
    params: params
  })
}
