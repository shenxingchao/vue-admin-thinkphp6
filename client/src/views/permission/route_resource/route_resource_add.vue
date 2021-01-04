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
            <el-form-item label="显示根节点" prop="always_show">
              <el-switch v-model="ruleForm.always_show" active-color="#13ce66" inactive-color="#ff4949">
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
            <el-form-item label="上级路由" prop="parent_id">
              <el-select v-model="ruleForm.parent_id" placeholder="请选择" clearable>
                <el-option v-for="item in options" :key="item.value" :label="item.label" :value="item.value">
                </el-option>
              </el-select>
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
import {
  routeResourceAdd,
  routeResourceOptions,
} from '@/api/permission/route_resource'

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
        always_show: false,
        hidden: false,
        icon: '',
        affix: false,
        breadcrumb: true,
        parent_id: null,
      },
      options: [
        {
          value: 0,
          label: '顶级路由',
        },
      ],
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
        parent_id: [
          {
            required: true,
            message: '请选择上级路由',
            trigger: 'blur',
          },
        ],
      },
    }
  },
  mounted() {
    this.getRouteResourceOptions()
  },
  methods: {
    //获取路由配置
    getRouteResourceOptions() {
      return routeResourceOptions({})
        .then((res) => {
          this.options = res.data.concat([
            {
              value: 0,
              label: '顶级路由',
            },
          ])
        })
        .catch(() => {})
    },
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
                    '/permission/route_resource/route_resource_list'
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
