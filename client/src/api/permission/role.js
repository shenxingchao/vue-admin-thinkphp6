import request from '@/utils/request'

//角色添加
export function roleAdd(ruleForm) {
  return request({
    url: '/Role/roleAdd',
    method: 'post',
    data: ruleForm
  })
}

//角色列表
export function roleList(params) {
  return request({
    url: 'Role/roleList',
    method: 'get',
    params: params
  })
}

//角色详情
export function roleDetail(params) {
  return request({
    url: 'Role/roleDetail',
    method: 'get',
    params: params
  })
}

//角色编辑
export function roleEdit(ruleForm) {
  return request({
    url: 'Role/roleEdit',
    method: 'put',
    data: ruleForm
  })
}

//角色删除
export function roleDelete(ruleForm) {
  return request({
    url: 'Role/roleDelete',
    method: 'delete',
    data: ruleForm
  })
}
