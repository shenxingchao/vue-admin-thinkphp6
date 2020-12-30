import request from '@/utils/request'

export function fileUpload(ruleForm) {
  return request({
    url: '/Upload/fileUpload',
    method: 'post',
    headers: { 'Content-Type': 'multipart/form-data' },
    data: ruleForm
  })
}
