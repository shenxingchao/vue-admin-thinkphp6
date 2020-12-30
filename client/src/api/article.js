import request from '@/utils/request'

export function articleAdd(ruleForm) {
  return request({
    url: '/Article/articleAdd',
    method: 'post',
    data: ruleForm
  })
}

export function articleEdit(ruleForm) {
  return request({
    url: '/Article/articleEdit',
    method: 'post',
    data: ruleForm
  })
}

export function articleLst(params) {
  return request({
    url: '/Article/articleLst',
    method: 'get',
    params: params
  })
}

export function articleDetail(params) {
  return request({
    url: '/Article/articleDetail',
    method: 'get',
    params: params
  })
}

export function articleDelete(ruleForm) {
  return request({
    url: '/Article/articleDelete',
    method: 'post',
    data: ruleForm
  })
}
