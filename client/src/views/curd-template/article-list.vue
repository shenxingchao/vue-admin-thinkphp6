<template>
  <div class="app-container">
    <el-card shadow="hover">
      <custom-table id="article-list" :data="List" :table-head="tableHead" :params="params" :show-selection="true"
                    :opt-width="180" @handleSizeChange="handleSizeChange" @handleCurrentChange="handleCurrentChange"
                    @handleSelectionChange="handleSelectionChange" @handleRowDblClick="handleRowDblClick"
                    @handleEdit="handleEdit" @handleDelete="handleDelete">
        <template v-slot:searchBar>
          <el-form ref="searchForm" :inline="true" :model="params" class="demo-form-inline" size="mini">
            <el-form-item prop="keyword">
              <el-input v-model="params.keyword" placeholder="搜索关键词" />
            </el-form-item>
            <el-form-item prop="recommend">
              <el-select v-model="params.recommend" placeholder="推荐">
                <el-option label="是" :value="true"> </el-option>
                <el-option label="否" :value="false"> </el-option>
              </el-select>
            </el-form-item>
            <el-form-item prop="top">
              <el-select v-model="params.top" placeholder="置顶">
                <el-option label="是" :value="true"> </el-option>
                <el-option label="否" :value="false"> </el-option>
              </el-select>
            </el-form-item>
            <el-form-item prop="status">
              <el-select v-model="params.status" placeholder="状态">
                <el-option label="显示" :value="true"> </el-option>
                <el-option label="隐藏" :value="false">
                </el-option>
              </el-select>
            </el-form-item>
            <el-form-item>
              <el-button type="primary" icon="el-icon-search" @click.native="onSubmit">查询</el-button>
              <el-button icon="el-icon-refresh-left" @click.native="$refs['searchForm'].resetFields();onSubmit()">重置
              </el-button>
              <el-button type="primary" icon="el-icon-plus" size="mini"
                         @click.native="$router.push('/article/article-add')">添加
              </el-button>
              <el-button type="danger" icon="el-icon-delete" size="mini" @click.native="handleDeleteRows">删除</el-button>
              <el-button type="warning" icon="el-icon-rank" @click="dialogVisible = true">这是一个可移动弹窗</el-button>
            </el-form-item>
          </el-form>
        </template>
        <template v-slot:opt="slotProps">
          <el-button size="mini" icon="el-icon-download" type="warning"
                     @click.stop="handleDownload(slotProps.scope.$index, slotProps.scope.row)">
          </el-button>
        </template>
      </custom-table>
    </el-card>
    <el-dialog v-el-drag-dialog title="弹窗" :visible.sync="dialogVisible">
      <span>这是一个可移动弹窗</span>
      <span slot="footer" class="dialog-footer">
        <el-button @click="dialogVisible = false">取消</el-button>
        <el-button type="primary" @click="dialogVisible = false">确定</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
import CustomTable from '@/components/CustomTable'
import { articleLst, articleDelete } from '@/api/article'
export default {
  name: 'ArticleList',
  components: {
    CustomTable
  },
  data() {
    return {
      List: [],
      selectionIdList: [],
      tableHead: [
        {
          label: '编号',
          prop: 'id',
          width: 60
        },
        {
          label: '标题',
          prop: 'title',
          width: 300
        },
        {
          label: '图片',
          prop: 'image',
          render: row => {
            return '<img  src="' + row.image + '" class="table-img"/>'
          }
        },
        {
          label: '作者',
          prop: 'author'
        },
        {
          label: '推荐',
          prop: 'recommend',
          component: row => {
            return row.recommend
              ? { is: 'custom-tag', type: 'success', title: '是' }
              : { is: 'custom-tag', type: 'danger', title: '否' }
          }
        },
        {
          label: '置顶',
          prop: 'top',
          component: row => {
            return row.top
              ? { is: 'custom-tag', type: 'success', title: '是' }
              : { is: 'custom-tag', type: 'danger', title: '否' }
          }
        },
        {
          label: '状态',
          prop: 'status',
          component: row => {
            return row.status
              ? { is: 'custom-tag', type: 'success', title: '启用' }
              : { is: 'custom-tag', type: 'danger', title: '禁用' }
          }
        },
        {
          label: '添加时间',
          prop: 'addtime',
          width: 140
        },
        {
          label: '修改时间',
          prop: 'updatetime',
          width: 140
        }
      ],
      params: {
        page: 1,
        total: 0,
        pageSize: 10,
        pageSizes: [10, 20, 30, 50],
        keyword: '',
        recommend: '',
        top: '',
        status: ''
      },
      dialogVisible: false //可移动弹窗
    }
  },
  async mounted() {
    await this.getArticleLst()
  },
  methods: {
    getArticleLst() {
      return articleLst(this.params)
        .then(res => {
          this.List = res.data.data
          this.params.total = res.data.total
        })
        .catch(() => {})
    },
    handleSizeChange(val) {
      this.params.pageSize = val
      this.getArticleLst()
    },
    handleCurrentChange(val) {
      this.params.page = val
      this.getArticleLst()
    },
    handleSelectionChange(val) {
      this.selectionIdList = val
    },
    handleRowDblClick(val) {
      this.$router.push({
        path: '/article/article-edit',
        query: {
          id: val
        }
      })
    },
    handleEdit(index, row) {
      this.handleRowDblClick(row.id)
    },
    handleDelete(index, row) {
      articleDelete({ ids: [row.id] })
        .then(res => {
          this.List.splice(index, 1)
          this.$message({
            message: '删除成功',
            type: 'success'
          })
        })
        .catch(() => {})
    },
    handleDownload(index, row) {
      this.$message({
        message: '当前下载行的id是' + row.id,
        type: 'success'
      })
    },
    handleDeleteRows() {
      let self = this
      if (this.selectionIdList.length == 0) {
        this.$message({
          message: '请选择要删除的数据',
          type: 'error'
        })
        return false
      }
      articleDelete({ ids: this.selectionIdList })
        .then(res => {
          //这里删除还可以使用逆向循环删除，删除以后还可以重新获取数据
          this.List = this.List.filter(
            item => this.selectionIdList.indexOf(item.id) == -1
          )
          this.$message({
            message: '删除成功',
            type: 'success',
            onClose: function() {
              self.getArticleLst()
            }
          })
        })
        .catch(() => {})
    },
    onSubmit() {
      this.params.page = 1
      this.params.pageSize = 10
      this.getArticleLst()
    }
  }
}
</script>

<style></style>
