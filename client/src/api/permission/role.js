import request from '@/utils/request'

export function roleAdd(ruleForm) {
  return request({
    url: '/Role/roleAdd',
    method: 'post',
    data: ruleForm
  })
}
