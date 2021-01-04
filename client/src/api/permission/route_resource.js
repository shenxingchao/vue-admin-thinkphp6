import request from '@/utils/request'

//路由资源添加
export function routeResourceAdd(ruleForm) {
  return request({
    url: '/RouteResource/routeResourceAdd',
    method: 'post',
    data: ruleForm
  })
}
