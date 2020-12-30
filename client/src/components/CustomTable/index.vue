<template>
  <div>
    <div v-if="showFilter||showSearch" class="check-cloumn-container">
      <slot v-if="showSearch" name="searchBar"></slot>
      <el-dropdown v-if="showFilter" class="filter">
        <el-button type="primary" size="mini">
          筛选<i class="el-icon-arrow-down el-icon--right"></i>
        </el-button>
        <el-dropdown-menu slot="dropdown">
          <el-checkbox v-model="checkAll" class="check-column-item" :indeterminate="isIndeterminate"
                       @change="handleCheckAllChange">全选
          </el-checkbox>
          <el-checkbox-group v-model="checkedColumn" @change="handleCheckedColumnChange">
            <div v-for="(item,index) in tableHead" :key="index">
              <el-checkbox class="check-column-item" :label="item.label">{{item.label}}</el-checkbox>
            </div>
          </el-checkbox-group>
        </el-dropdown-menu>
      </el-dropdown>
    </div>
    <el-table :id="id" :key="key" ref="multipleTable" :data="data" :row-key="hanldeRowKey" border fit size="mini"
              tooltip-effect="dark" style="width: 100%" @header-dragend="handleHeaderDrag" @row-click="handleRowClick"
              @selection-change="handleSelectionChange" @row-dblclick="handleRowDblClick">
      <!-- 多选框checkbox -->
      <el-table-column v-if="showSelection" type="selection" width="55">
      </el-table-column>
      <el-table-column v-for="(item,index) in tableHeadOptions" :key="index" :width="item.width ? item.width : ''"
                       :align="item.align||'center'" :label="item.label" :prop="item.prop"
                       :sortable="item.sortable ? 'custom' : false" show-overflow-tooltip>
        <template slot-scope="scope">
          <!-- 单元格渲染html代码 -->
          <template v-if="item.render"><span v-html="item.render(scope.row)"></span></template>
          <!-- 渲染动态组件,这里只用到了tag组件,其他组件自行实现 -->
          <template v-else-if="item.component">
            <component :is="item.component(scope.row).is" :type="item.component(scope.row).type"
                       :title="item.component(scope.row).title"></component>
          </template>
          <!-- 渲染普通文字 -->
          <template v-else>{{scope.row[item.prop]}}</template>
        </template>
      </el-table-column>
      <!-- 操作列 -->
      <el-table-column v-if="showOpt" align="center" label="操作" :min-width="optWidth">
        <template slot-scope="scope">
          <el-button size="mini" icon="el-icon-edit" type="primary" @click.stop="handleEdit(scope.$index, scope.row)">
          </el-button>
          <el-button size="mini" icon="el-icon-delete" type="danger"
                     @click.stop="handleDelete(scope.$index, scope.row)"></el-button>
          <slot :scope="scope" name="opt">
          </slot>
        </template>
      </el-table-column>
    </el-table>
    <div v-if="showPage" class="block">
      <el-pagination :current-page="params.page" :total="params.total" :page-sizes="params.pageSizes"
                     :page-size="params.pageSize" background layout="total, sizes, prev, pager, next, jumper"
                     @size-change="handleSizeChange" @current-change="handleCurrentChange" />
    </div>
  </div>
</template>
<script>
import CustomTag from '@/components/CustomTag'
import Sortable from 'sortablejs' //拖拽插件
export default {
  name: 'CustomTable',
  components: {
    CustomTag,
    Sortable
  },
  props: {
    //表格id
    id: {
      type: String,
      default: ''
    },
    //显示单选框
    showSelection: {
      type: Boolean,
      default: true
    },
    //表头数据
    tableHead: {
      type: Array,
      default: () => {
        return []
      }
    },
    //表格数据
    data: {
      type: Array,
      default: () => {
        return []
      }
    },
    //显示分页
    showPage: {
      type: Boolean,
      default: true
    },
    //分页参数
    params: {
      type: Object,
      default: null
    },
    //显示筛选
    showFilter: {
      type: Boolean,
      default: true
    },
    //显示搜索
    showSearch: {
      type: Boolean,
      default: true
    },
    //显示操作
    showOpt: {
      type: Boolean,
      default: true
    },
    //操作列最小宽度
    optWidth: {
      type: Number,
      default: 120
    }
  },
  data() {
    return {
      key: 0, //table的key
      tableHeadOptions: {}, //实际显示的tableHead
      selectionList: [], //选中行的id数组 1,2,3,4...
      checkAll: true, //全选
      checkedColumn: [], //字段筛选列表
      isIndeterminate: false //全选按钮 样式  - 或者是 √
    }
  },
  watch: {
    checkedColumn(val) {
      this.tableHeadOptions = this.tableHead.filter(i => {
        return val.indexOf(i.label) >= 0
      })
      this.key += 1 //fix 抖动 bug
      setTimeout(() => {
        this.rowDrop() //每次重绘表格在执行拖动
      }, 100)
    }
  },
  mounted() {
    this.tableHead.forEach(element => {
      this.checkedColumn.push(element.label)
    })
    this.getTableColWidth()
  },
  methods: {
    //行key
    hanldeRowKey(row) {
      return row.id
    },
    //分页
    handleSizeChange(val) {
      this.$emit('handleSizeChange', val)
    },
    //分页
    handleCurrentChange(val) {
      this.$emit('handleCurrentChange', val)
    },
    //单击一行 选中
    handleRowClick(row) {
      let multipleTable = this.$refs.multipleTable
      multipleTable.toggleRowSelection(row)
    },
    //选中行状态改变
    handleSelectionChange(val) {
      let selectionIdList = []
      val.forEach(element => {
        selectionIdList.push(element.id)
      })
      this.$emit('handleSelectionChange', selectionIdList)
    },
    //双击打开编辑
    handleRowDblClick(val) {
      let id = val.id
      this.$emit('handleRowDblClick', id)
    },
    //编辑操作
    handleEdit(index, row) {
      this.$emit('handleEdit', index, row)
    },
    //删除操作
    handleDelete(index, row) {
      this.$emit('handleDelete', index, row)
    },
    //拖动表头 改变宽度 保存到localstorage
    handleHeaderDrag(newWidth, oldWidth, column, event) {
      setTimeout(() => {
        let table_key = this.id
        let applyTableColWidths = []
        let applyTable = document.getElementById(table_key)
        let applyTableColgroup = applyTable.getElementsByTagName('colgroup')[0]
        let applyTableCol = applyTableColgroup.getElementsByTagName('col')
        for (
          let i = this.showSelection ? 1 : 0;
          i < applyTableCol.length;
          i++
        ) {
          applyTableColWidths.push(applyTableCol[i].width)
        }
        localStorage.setItem(table_key, JSON.stringify(applyTableColWidths))
      }, 100)
    },
    //获取浏览器缓存的列宽
    getTableColWidth() {
      let tableWidth = localStorage.getItem(this.id)
      if (tableWidth) {
        tableWidth = JSON.parse(tableWidth)
        for (let i = 0, length = this.tableHead.length; i < length; i++) {
          this.tableHead[i].width = tableWidth[i]
        }
      }
    },
    //行拖动交换 触发函数参数为交换记录行的id值
    rowDrop() {
      const tbody = document.querySelector('.el-table__body-wrapper tbody')
      const _this = this
      Sortable.create(tbody, {
        onEnd({ newIndex, oldIndex }) {
          const currRow = _this.data.splice(oldIndex, 1)[0]
          _this.data.splice(newIndex, 0, currRow)
          _this.$emit('handleRowRrop', _this.data) //当前页新的排序数据
        }
      })
    },
    //全选
    handleCheckAllChange(val) {
      this.checkedColumn = []
      if (val) {
        this.tableHead.forEach(element => {
          this.checkedColumn.push(element.label)
        })
      }
      this.isIndeterminate = false
    },
    //切换筛选
    handleCheckedColumnChange(value) {
      let checkedCount = value.length
      this.checkAll = checkedCount === this.tableHead.length
      this.isIndeterminate =
        checkedCount > 0 && checkedCount < this.tableHead.length
    }
  }
}
</script>

<style lang="scss" scoped>
.check-cloumn-container {
  display: flex;
  justify-content: space-between;
  align-items: top;
  .filter {
    width: 73px;
    margin-bottom: 18px;
  }
}
.check-column-item {
  padding: 4px 8px;
}
</style>