import request from '@/utils/request'

//管理员添加
export function adminAdd(ruleForm) {
  return request({
    url: '/Admin/adminAdd',
    method: 'post',
    data: ruleForm
  })
}

//管理员列表
export function adminList(params) {
  return request({
    url: 'Admin/adminList',
    method: 'get',
    params: params
  })
}

//管理员详情
export function adminDetail(params) {
  return request({
    url: 'Admin/adminDetail',
    method: 'get',
    params: params
  })
}

//管理员编辑
export function adminEdit(ruleForm) {
  return request({
    url: 'Admin/adminEdit',
    method: 'put',
    data: ruleForm
  })
}

//管理员删除
export function adminDelete(ruleForm) {
  return request({
    url: 'Admin/adminDelete',
    method: 'delete',
    data: ruleForm
  })
}
