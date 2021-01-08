<template>
  <div class="app-container">
    <el-card shadow="hover">
      <custom-table id="route-resource-list" :data="List" :table-head="tableHead" :params="params"
                    :show-selection="true" :show-page="false" :is-radio="true" :opt-width="180"
                    @handleSelectionChange="handleSelectionChange" @handleRowDblClick="handleRowDblClick"
                    @handleEdit="handleEdit" @handleDelete="handleDelete">
        <template v-slot:searchBar>
          <el-form ref="searchForm" :inline="true" :model="params" class="demo-form-inline" size="mini">
            <el-form-item>
              <el-button type="primary" icon="el-icon-plus" size="mini"
                         @click.native="$router.push('/permission/route_resource/route_resource_add')">添加
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
import {
  routeResourceList,
  routeResourceDelete,
} from '@/api/permission/route_resource'
export default {
  name: 'RouteResourceList',
  components: {
    CustomTable,
  },
  data() {
    return {
      List: [],
      selectionId: null,
      tableHead: [
        // {
        //   label: '编号',
        //   prop: 'id',
        //   align: 'left',
        //   width: 120,
        // },
        {
          label: '菜单名称',
          prop: 'title',
          align: 'left',
          width: 160,
        },
        {
          label: '路由地址',
          prop: 'path',
          align: 'left',
          width: 140,
        },
        {
          label: '路由名称',
          prop: 'name',
          align: 'left',
          width: 140,
        },
        {
          label: '映射组件名称',
          prop: 'component',
          align: 'left',
          width: 140,
        },
        {
          label: '重定向路由',
          prop: 'redirect',
          align: 'left',
          width: 300,
        },
        {
          label: '显示根节点',
          prop: 'always_show',
          width: 120,
          component: (row) => {
            return row.always_show
              ? { is: 'custom-tag', type: 'success', title: '是' }
              : { is: 'custom-tag', type: 'danger', title: '否' }
          },
        },
        {
          label: '菜单隐藏路由',
          prop: 'hidden',
          width: 120,
          component: (row) => {
            return row.hidden
              ? { is: 'custom-tag', type: 'success', title: '是' }
              : { is: 'custom-tag', type: 'danger', title: '否' }
          },
        },
        {
          label: 'svg图标',
          prop: 'icon',
          align: 'left',
          width: 80,
        },
        {
          label: '菜单标签栏固定',
          prop: 'affix',
          width: 120,
          component: (row) => {
            return row.affix
              ? { is: 'custom-tag', type: 'success', title: '是' }
              : { is: 'custom-tag', type: 'danger', title: '否' }
          },
        },
        {
          label: '面包屑显示菜单',
          prop: 'breadcrumb',
          width: 120,
          component: (row) => {
            return row.breadcrumb
              ? { is: 'custom-tag', type: 'success', title: '是' }
              : { is: 'custom-tag', type: 'danger', title: '否' }
          },
        },
      ],
      params: {},
    }
  },
  async mounted() {
    await this.getRouteResourceList()
  },
  methods: {
    getRouteResourceList() {
      return routeResourceList(this.params)
        .then((res) => {
          this.List = res.data
        })
        .catch(() => {})
    },
    handleSelectionChange(val) {
      this.selectionId = val
    },
    handleRowDblClick(val) {
      this.$router.push({
        path: '/permission/route_resource/route_resource_edit',
        query: {
          id: val,
        },
      })
    },
    handleEdit(index, row) {
      this.handleRowDblClick(row.id)
    },
    handleDelete(index, row) {
      routeResourceDelete({ id: row.id })
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
      if (!this.selectionId) {
        this.$message({
          message: '请选择要删除的数据',
          type: 'error',
        })
        return false
      }
      routeResourceDelete({ id: this.selectionId })
        .then((res) => {
          //这里删除还可以使用逆向循环删除，删除以后还可以重新获取数据
          this.List = this.List.filter((item) => this.selectionId !== item.id)
          this.$message({
            message: '删除成功',
            type: 'success',
            onClose: function () {
              self.getRouteResourceList()
            },
          })
        })
        .catch(() => {})
    },
  },
}
</script>

<style></style>
