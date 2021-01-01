<template>
  <div class="app-container">
    <el-card shadow="hover">
      <el-row type="flex" justify="left">
        <el-col :xs="24" :md="12">
          <el-form ref="ruleForm" :rules="rules" :model="ruleForm" label-position="right" label-width="150px">
            <el-form-item label="菜单名称" prop="title">
              <el-input v-model="ruleForm.title" placeholder="菜单名称" />
            </el-form-item>
            <el-form-item label="路由地址" prop="path">
              <el-input v-model="ruleForm.path" placeholder="路由地址" />
            </el-form-item>
            <el-form-item label="路由名称" prop="name">
              <el-input v-model="ruleForm.name" placeholder="路由名称" />
            </el-form-item>
            <el-form-item label="映射组件名称" prop="component">
              <el-input v-model="ruleForm.component" placeholder="映射组件名称" />
            </el-form-item>
            <el-form-item label="重定向路由" prop="redirect">
              <el-input v-model="ruleForm.redirect" placeholder="重定向路由" />
            </el-form-item>
            <el-form-item label="显示根节点" prop="alwaysShow">
              <el-switch v-model="ruleForm.alwaysShow" active-color="#13ce66" inactive-color="#ff4949">
              </el-switch>
            </el-form-item>
            <el-form-item label="菜单隐藏路由" prop="hidden">
              <el-switch v-model="ruleForm.hidden" active-color="#13ce66" inactive-color="#ff4949">
              </el-switch>
            </el-form-item>
            <el-form-item label="svg图标" prop="icon">
              <el-input v-model="ruleForm.icon" placeholder="svg图标" />
            </el-form-item>
            <el-form-item label="菜单标签栏固定" prop="affix">
              <el-switch v-model="ruleForm.affix" active-color="#13ce66" inactive-color="#ff4949">
              </el-switch>
            </el-form-item>
            <el-form-item label="面包屑显示菜单" prop="breadcrumb">
              <el-switch v-model="ruleForm.breadcrumb" active-color="#13ce66" inactive-color="#ff4949">
              </el-switch>
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
import { routeResourceAdd } from '@/api/permission/route-resource'

export default {
  name: 'RouteResourceAdd',
  data() {
    return {
      ruleForm: {
        title: '',
        path: '',
        name: '',
        component: '',
        redirect: '',
        alwaysShow: false,
        hidden: false,
        icon: '',
        affix: false,
        breadcrumb: true,
      },
      rules: {
        title: [
          {
            required: true,
            message: '请输入菜单名称',
            trigger: 'blur',
          },
        ],
        path: [
          {
            required: true,
            message: '请输入路由地址',
            trigger: 'blur',
          },
        ],
        name: [
          {
            required: true,
            message: '请输入路由名称',
            trigger: 'blur',
          },
        ],
        component: [
          {
            required: true,
            message: '请输入映射组件名称',
            trigger: 'blur',
          },
        ],
      },
    }
  },
  mounted() {},
  methods: {
    submitForm(formName) {
      const _this = this
      this.$refs[formName].validate((valid) => {
        if (valid) {
          routeResourceAdd(this.ruleForm)
            .then((res) => {
              this.$message({
                message: '添加成功',
                type: 'success',
                onClose: function () {
                  _this.$router.push(
                    '/permission/route-resource/route-resource-list'
                  )
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
