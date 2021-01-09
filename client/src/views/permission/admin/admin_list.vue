<template>
  <div class="app-container">
    <el-card shadow="hover">
      <custom-table id="admin-list" :data="List" :table-head="tableHead" :params="params" :show-selection="true"
                    :opt-width="180" @handleSizeChange="handleSizeChange" @handleCurrentChange="handleCurrentChange"
                    @handleSelectionChange="handleSelectionChange" @handleRowDblClick="handleRowDblClick"
                    @handleEdit="handleEdit" @handleDelete="handleDelete">
        <template v-slot:searchBar>
          <el-form ref="searchForm" :inline="true" :model="params" class="demo-form-inline" size="mini">
            <el-form-item prop="keyword">
              <el-input v-model="params.keyword" placeholder="搜索关键词" />
            </el-form-item>
            <el-form-item prop="status">
              <el-select v-model="params.status" placeholder="状态" clearable>
                <el-option label="启用" :value="1"> </el-option>
                <el-option label="禁用" :value="0">
                </el-option>
              </el-select>
            </el-form-item>
            <el-form-item>
              <el-button type="primary" icon="el-icon-search" @click.native="onSubmit">查询</el-button>
              <el-button icon="el-icon-refresh-left" @click.native="$refs['searchForm'].resetFields();onSubmit()">重置
              </el-button>
              <el-button type="primary" icon="el-icon-plus" size="mini"
                         @click.native="$router.push('/permission/admin/admin_add')">添加
              </el-button>
              <el-button type="danger" icon="el-icon-delete" size="mini" @click.native="handleDeleteRows">删除</el-button>
            </el-form-item>
          </el-form>
        </template>
      </custom-table>
    </el-card>
  </div>
</template>

<script>
import CustomTable from '@/components/CustomTable'
import { adminList, adminDelete } from '@/api/permission/admin'
export default {
  name: 'AdminList',
  components: {
    CustomTable,
  },
  data() {
    return {
      List: [],
      selectionIdList: [],
      tableHead: [
        {
          label: '编号',
          prop: 'id',
          width: 60,
        },
        {
          label: '账号名',
          prop: 'username',
          width: 150,
        },
        {
          label: '状态',
          prop: 'status',
          component: (row) => {
            return row.status
              ? { is: 'custom-tag', type: 'success', title: '启用' }
              : { is: 'custom-tag', type: 'danger', title: '禁用' }
          },
        },
        {
          label: '角色',
          prop: 'roles',
        },
      ],
      params: {
        page: 1,
        total: 0,
        pageSize: 10,
        pageSizes: [10, 20, 30, 50],
        keyword: '',
        status: '',
      },
    }
  },
  async mounted() {
    await this.getAdminList()
  },
  methods: {
    getAdminList() {
      return adminList(this.params)
        .then((res) => {
          this.List = res.data.data
          this.params.total = res.data.total
        })
        .catch(() => {})
    },
    handleSizeChange(val) {
      this.params.pageSize = val
      this.getAdminList()
    },
    handleCurrentChange(val) {
      this.params.page = val
      this.getAdminList()
    },
    handleSelectionChange(val) {
      this.selectionIdList = val
    },
    handleRowDblClick(val) {
      this.$router.push({
        path: '/permission/admin/admin_edit',
        query: {
          id: val,
        },
      })
    },
    handleEdit(index, row) {
      this.handleRowDblClick(row.id)
    },
    handleDelete(index, row) {
      adminDelete({ ids: [row.id] })
        .then((res) => {
          this.List.splice(index, 1)
          this.$message({
            message: '删除成功',
            type: 'success',
          })
        })
        .catch(() => {})
    },
    handleDeleteRows() {
      let self = this
      if (this.selectionIdList.length == 0) {
        this.$message({
          message: '请选择要删除的数据',
          type: 'error',
        })
        return false
      }
      adminDelete({ ids: this.selectionIdList })
        .then((res) => {
          //这里删除还可以使用逆向循环删除，删除以后还可以重新获取数据
          this.List = this.List.filter(
            (item) => this.selectionIdList.indexOf(item.id) == -1
          )
          this.$message({
            message: '删除成功',
            type: 'success',
            onClose: function () {
              self.getAdminList()
            },
          })
        })
        .catch(() => {})
    },
    onSubmit() {
      this.params.page = 1
      this.params.pageSize = 10
      this.getAdminList()
    },
  },
}
</script>

<style></style>
