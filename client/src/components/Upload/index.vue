<template>
  <div class="upload-file-commpent">
    <template v-if="!multiple">
      <div v-if="imgUrl" class="uplaod-file-preview" @mouseenter="isShowOpt = true" @mouseleave="isShowOpt = false">
        <img :src="imgUrl" alt="上传成功图片" />
        <div v-show="isShowOpt" class="opt">
          <i class="el-icon-delete delete-btn" @click="handleClickDelete()"></i>
        </div>
      </div>
      <div v-if="!imgUrl" class="upload-file-btn" @click="$refs.file.click()">
        <i class="el-icon-plus"></i>
        <input ref="file" type="file" class="upload-file-input" :accept="fileType"
               @change="handleUploadFile($event.target.files[0])" />
      </div>
    </template>
    <template v-if="multiple">
      <div v-for="(item,index) in imgList" :key="index" class="uplaod-file-preview" @mouseenter="item.isShowMask = true"
           @mouseleave="item.isShowMask = false">
        <img :src="item.url" alt="上传成功图片" />
        <div v-show="item.isShowMask" class="opt">
          <i class="el-icon-delete delete-btn" @click="handleClickDeleteMultiple(index)"></i>
        </div>
      </div>
      <div v-if="imgList.length < limit" class="upload-file-btn" @click="$refs.files.click()">
        <i class="el-icon-plus"></i>
        <input ref="files" type="file" class="upload-file-input" :accept="fileType" :multiple="multiple"
               @change="handleUploadFiles($event.target.files)" />
      </div>
    </template>
  </div>
</template>
<script>
import { fileUpload } from '@/api/upload'
export default {
  name: 'Upload',
  props: {
    file: {
      type: String,
      default: ''
    },
    fileType: {
      type: String,
      default: 'image/*'
    },
    multiple: {
      type: Boolean,
      defalut: false
    },
    limit: {
      type: Number,
      default: 3
    },
    files: {
      type: Array,
      default: function() {
        return []
      }
    }
  },
  data() {
    return {
      imgUrl: '',
      imgList: [],
      isShowOpt: false
    }
  },
  watch: {
    file: function(newValue, oldValue) {
      this.imgUrl = newValue
    },
    files: function(newValue, oldValue) {
      this.imgList = []
      newValue.forEach(url => {
        this.imgList.push({
          url: url,
          isShowMask: false
        })
      })
    }
  },
  mounted() {},
  methods: {
    /**
     * 文件上传
     */
    handleUploadFile: function(file) {
      let fd = new FormData()
      fd.append('file', file)
      fileUpload(fd)
        .then(res => {
          this.imgUrl = res.data.imgUrl
          this.$message({
            message: '上传成功',
            type: 'success',
            onClose: function() {}
          })
          //子组件通知父组件上传成功
          this.$emit('handleUploadSuccess', this.imgUrl)
        })
        .catch(() => {})
    },
    /**
     * 文件删除
     */
    handleClickDelete: function() {
      this.imgUrl = ''
      this.$emit('handleDeleteFile')
    },
    /**
     * 多文件删除
     */
    handleClickDeleteMultiple: function(index) {
      this.imgList.splice(index, 1)
      let urlList = [...this.imgList].map(item => item.url)
      this.$emit('handleClickDeleteMultiple', urlList)
    },
    /**
     * 多文件上传
     */
    handleUploadFiles(files) {
      if (files.length + this.imgList.length > this.limit) {
        this.$message({
          type: 'error',
          message: '最多可上传' + this.limit + '张'
        })
        return false
      }
      new Promise(resolve => {
        files.forEach(async (file, index) => {
          let fd = new FormData()
          fd.append('file', file)
          await fileUpload(fd)
            .then(res => {
              this.imgList.push({
                url: res.data.imgUrl,
                isShowMask: false
              })
              if (index == files.length - 1) {
                resolve()
              }
            })
            .catch(() => {})
        })
      }).then(() => {
        this.$message({
          type: 'success',
          message: '图片列表上传成功'
        })
        let urlList = [...this.imgList].map(item => item.url) //JSON.parse(JSON.stringify(this.imgList))
        //子组件通知父组件上传成功
        this.$emit('handleUploadMultipleSuccess', urlList)
      })
    }
  }
}
</script>
<style lang="scss" scoped>
.upload-file-commpent {
  display: flex;
  flex-wrap: wrap;
  .uplaod-file-preview {
    width: 100px;
    height: 100px;
    margin-right: 10px;
    margin-bottom: 10px;
    position: relative;
    img {
      width: 100%;
      height: 100%;
      display: block;
    }
    .opt {
      width: 100%;
      height: 100%;
      position: absolute;
      top: 0;
      bottom: 0;
      left: 0;
      right: 0;
      background: rgba($color: #000000, $alpha: 0.3);
      .delete-btn {
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: #ffffff;
        position: absolute;
        top: 0;
        right: 0;
      }
    }
  }
  .upload-file-btn {
    width: 100px;
    height: 100px;
    border: 1px dashed #cccccc;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    &:hover {
      border-color: #409eff;
    }
    .el-icon-plus {
      font-size: 26px;
      color: #777777;
    }
    .upload-file-input {
      display: none;
    }
  }
}
</style>
/** 
@图片上传组件
引入
import Upload from '@/components/Upload'

使用
<Upload :file="ruleForm.avatar" @handleUploadSuccess="handleUploadSuccess($event)"
        @handleDeleteFile="ruleForm.avatar = ''">
</Upload>

//上传成功事件
handleUploadSuccess: function(imgUrl) {
    this.ruleForm.avatar = imgUrl
    //取消头像验证
    this.$refs['ruleForm'].clearValidate('avatar')
}

多图上传
<Upload :files="ruleForm.image_list" multiple
        @handleUploadMultipleSuccess="handleUploadMultipleSuccess($event)"
        @handleClickDeleteMultiple="ruleForm.image_list = $event">
</Upload>
      
//多图上传成功事件
handleUploadMultipleSuccess: function(imgUrlList) {
  this.ruleForm.image_list = imgUrlList
  this.$refs['ruleForm'].clearValidate('image_list')
}
*/