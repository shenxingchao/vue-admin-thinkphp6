<template>
  <div class="app-container">
    <el-card shadow="hover">
      <el-row type="flex" justify="left">
        <el-col :xs="24" :md="12">
          <el-form ref="ruleForm" :rules="rules" :model="ruleForm" label-position="right" label-width="150px">
            <el-form-item label="角色名称" prop="role_name">
              <el-input v-model="ruleForm.role_name" placeholder="角色名称" />
            </el-form-item>
            <el-form-item label="权限">
              <!-- 点击节点选中 带扩展 -->
              <el-tree ref="tree" :props="defaultProps" :data="routeResourceNodes" node-key="id" show-checkbox
                       default-expand-all />
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
import { roleEdit, roleDetail } from '@/api/permission/role'
import { routeResourceNodes } from '@/api/permission/route_resource'
export default {
  name: 'RoleEdit',
  components: {},
  data() {
    return {
      ruleForm: {
        id: this.$route.query.id,
        role_name: '', //角色名称
        route_resource_ids: [], //全选中节点和半选中节点
        temp_route_resource_ids: [], //全选中节点
      },
      rules: {
        role_name: [
          {
            required: true,
            message: '请输入角色名称',
            trigger: 'blur',
          },
        ],
      },
      defaultProps: {
        children: 'children',
        label: 'label',
      },
      routeResourceNodes: [],
    }
  },
  async mounted() {
    await this.getRouteResourceNodes()
    await this.getRoleDetail()
  },
  methods: {
    getRoleDetail() {
      return roleDetail({ id: this.ruleForm.id })
        .then((res) => {
          this.ruleForm = res.data
          this.$refs.tree.setCheckedKeys(this.ruleForm.temp_route_resource_ids)
          this.expandedKeys = this.ruleForm.temp_route_resource_ids
        })
        .catch(() => {})
    },
    getRouteResourceNodes() {
      return routeResourceNodes({})
        .then((res) => {
          this.routeResourceNodes = res.data
        })
        .catch(() => {})
    },
    submitForm(formName) {
      const _this = this
      this.$refs[formName].validate((valid) => {
        if (valid) {
          this.ruleForm.route_resource_ids = [].concat(
            this.$refs.tree.getCheckedKeys(),
            this.$refs.tree.getHalfCheckedKeys()
          )
          this.ruleForm.temp_route_resource_ids = this.$refs.tree.getCheckedKeys()
          roleEdit(this.ruleForm)
            .then((res) => {
              this.$message({
                message: '保存成功',
                type: 'success',
                onClose: function () {
                  _this.$router.push('/permission/role/role_list')
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
