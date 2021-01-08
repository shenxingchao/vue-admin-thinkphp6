<template>
  <div class="app-container">
    <el-card shadow="hover">
      <el-row type="flex" justify="left">
        <el-col :xs="24" :md="12">
          <el-form ref="ruleForm" :rules="rules" :model="ruleForm" label-position="right" label-width="150px">
            <el-form-item label="标题" prop="title">
              <el-input v-model="ruleForm.title" placeholder="标题" />
            </el-form-item>
            <el-form-item label="图片" prop="image">
              <Upload :file="ruleForm.image" @handleUploadSuccess="handleUploadSuccess($event)"
                      @handleDeleteFile="ruleForm.image = ''">
              </Upload>
            </el-form-item>
            <el-form-item label="图片列表" prop="image_list">
              <Upload :files="ruleForm.image_list" multiple
                      @handleUploadMultipleSuccess="handleUploadMultipleSuccess($event)"
                      @handleClickDeleteMultiple="ruleForm.image_list = $event">
              </Upload>
            </el-form-item>
            <el-form-item label="作者" prop="author">
              <el-input v-model="ruleForm.author" placeholder="作者" />
            </el-form-item>
            <el-form-item label="详情" prop="detail">
              <QuillEditor :url="serverUrl" :header="header" :value="ruleForm.detail" @input="input($event)">
              </QuillEditor>
            </el-form-item>
            <el-form-item label="推荐" prop="recommend">
              <el-switch v-model="ruleForm.recommend" active-color="#13ce66" inactive-color="#ff4949">
              </el-switch>
            </el-form-item>
            <el-form-item label="置顶" prop="top">
              <el-switch v-model="ruleForm.top" active-color="#13ce66" inactive-color="#ff4949">
              </el-switch>
            </el-form-item>
            <el-form-item label="状态" prop="">
              <el-switch v-model="ruleForm.status" active-color="#13ce66" inactive-color="#ff4949">
              </el-switch>
            </el-form-item>
            <el-form-item>
              <el-button type="success" @click="submitForm('ruleForm')">确定</el-button>
              <el-button @click="resetForm('ruleForm')">重置</el-button>
            </el-form-item>
          </el-form>
        </el-col>
      </el-row>
    </el-card>
  </div>
</template>
 
<script>
import { articleEdit, articleDetail } from '@/api/article'
import Upload from '@/components/Upload'
import QuillEditor from '@/components/QuillEditor'
import { getToken } from '@/utils/auth'
import { configUrl } from '@/utils/config'

export default {
  name: 'ArticleEdit',
  components: {
    Upload,
    QuillEditor
  },
  data() {
    return {
      ruleForm: {
        id: this.$route.query.id,
        title: '',
        image: '',
        image_list: [],
        author: '',
        type: '',
        detail: '',
        recommend: true,
        top: true,
        status: true
      },
      rules: {
        title: [
          {
            required: true,
            message: '请输入标题',
            trigger: 'blur'
          }
        ],
        image: [
          {
            required: true,
            message: '请上传图片'
          }
        ],
        image_list: [
          {
            required: true,
            message: '请上传图片列表'
          }
        ],
        author: [
          {
            required: true,
            message: '请输入作者',
            trigger: 'blur'
          }
        ],
        detail: [
          {
            required: true,
            message: '请输入详情'
          }
        ]
      },
      header: {
        'X-Token': getToken()
      },
      serverUrl: configUrl + '/Upload/fileUpload'
    }
  },
  mounted() {
    this.getArticleDetail()
  },
  methods: {
    getArticleDetail() {
      articleDetail({ id: this.ruleForm.id })
        .then(res => {
          this.ruleForm = res.data
        })
        .catch(() => {})
    },
    submitForm(formName) {
      const _this = this
      this.$refs[formName].validate(valid => {
        if (valid) {
          articleEdit(this.ruleForm)
            .then(res => {
              this.$message({
                message: '编辑成功',
                type: 'success',
                onClose: function() {
                  _this.$router.push('/article/article-list')
                }
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
    handleUploadSuccess: function(imgUrl) {
      this.ruleForm.image = imgUrl
      this.$refs['ruleForm'].clearValidate('image')
    },
    handleUploadMultipleSuccess: function(imgUrlList) {
      this.ruleForm.image_list = imgUrlList
      this.$refs['ruleForm'].clearValidate('image_list')
    },
    input: function(content) {
      this.ruleForm.detail = content
    }
  }
}
</script>
  
<style lang="scss">
</style>
