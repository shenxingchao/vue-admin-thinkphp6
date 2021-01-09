<template>
  <div class="app-container">
    <el-card shadow="hover">
      <el-row type="flex" justify="left">
        <el-col :xs="24" :md="12">
          <el-form ref="ruleForm" :rules="rules" :model="ruleForm" label-position="right" label-width="150px">
            <el-form-item label="账号名" prop="username">
              <el-input v-model="ruleForm.username" placeholder="账号名" />
            </el-form-item>
            <el-form-item label="密码" prop="password">
              <el-input v-model="ruleForm.password" placeholder="密码" />
            </el-form-item>
            <el-form-item label="状态" prop="">
              <el-switch v-model="ruleForm.status" active-color="#13ce66" inactive-color="#ff4949">
              </el-switch>
            </el-form-item>
            <el-form-item label="角色" prop="role_ids">
              <el-checkbox-group v-model="ruleForm.role_ids">
                <el-checkbox v-for="(item,index) in roleList" :key="index" :label="item.id">
                  {{ item.role_name }}
                </el-checkbox>
              </el-checkbox-group>
            </el-form-item>
            <el-form-item>
              <el-button type="primary" @click="submitForm('ruleForm')">确定</el-button>
              <el-button @click="resetForm('ruleForm')">重置</el-button>
            </el-form-item>
          </el-form>
        </el-col>
      </el-row>
    </el-card>
  </div>
</template>
 
<script>
import { adminEdit, adminDetail } from '@/api/permission/admin'
import { roleAllList } from '@/api/permission/role'

export default {
  name: 'AdminEdit',
  components: {},
  data() {
    return {
      ruleForm: {
        id: this.$route.query.id,
        username: '',
        password: '',
        status: true,
        role_ids: [],
      },
      rules: {
        username: [
          { required: true, message: '请输入账号名', trigger: 'blur' },
          {
            min: 2,
            max: 20,
            message: '长度在 2 到 20 个字符',
            trigger: 'blur',
          },
        ],
        role_ids: [{ required: true, message: '请设置角色' }],
      },
      //角色选项
      roleList: [],
    }
  },
  async mounted() {
    await this.getRoleAllList()
    await this.getAdminDetail()
  },
  methods: {
    getAdminDetail() {
      return adminDetail({ id: this.ruleForm.id })
        .then((res) => {
          this.ruleForm = res.data
        })
        .catch(() => {})
    },
    getRoleAllList() {
      return roleAllList({})
        .then((res) => {
          this.roleList = res.data
        })
        .catch(() => {})
    },
    submitForm(formName) {
      const _this = this
      this.$refs[formName].validate((valid) => {
        if (valid) {
          adminEdit(this.ruleForm)
            .then((res) => {
              this.$message({
                message: '保存成功',
                type: 'success',
                onClose: function () {
                  _this.$router.push('/permission/admin/admin_list')
                },
              })
            })
            .catch(() => {})
        } else {
          console.log('error submit!!')
          return false
        }
      })
    },
    resetForm(formName) {
      this.$refs[formName].resetFields()
    },
  },
}
</script>
  
<style lang="scss">
</style>
