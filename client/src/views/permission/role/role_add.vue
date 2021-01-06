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
              <el-tree ref="tree" :props="defaultProps" :data="routeResourceNodes" node-key="id" show-checkbox />
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
import { articleAdd } from '@/api/article'
import { routeResourceNodes } from '@/api/permission/route_resource'
export default {
  name: 'RoleAdd',
  components: {},
  data() {
    return {
      ruleForm: {
        role_name: '', //角色名称
        routeResourceIds: [], //权限Id数组
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
  },
  methods: {
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
          //  this.ruleForm.permissionSrcIds = [].concat(this.$refs.treeSrc.getCheckedKeys(), this.$refs.treeSrc.getHalfCheckedKeys())
          // this.ruleForm.tempMenuIds = this.$refs.tree.getCheckedKeys()
          // this.ruleForm.tempSrcIds = this.$refs.treeSrc.getCheckedKeys()
          articleAdd(this.ruleForm)
            .then((res) => {
              this.$message({
                message: '添加成功',
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
