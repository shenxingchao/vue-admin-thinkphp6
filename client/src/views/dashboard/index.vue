<template>
  <div class="dashboard-container">
    <el-alert class="dashboard-container-text" title="vue-admin-elelmentui 发布啦" type="success">
    </el-alert>
    <el-row :gutter="24" class="item-box">
      <el-col :span="6">
        <div class="item one">
          <div class="item-left">
            <div class="number">999+</div>
            <div class="sm">产品</div>
          </div>
          <i class="icon el-icon-goods"></i>
        </div>
      </el-col>
      <el-col :span="6">
        <div class="item two">
          <div class="item-left">
            <div class="number">999+</div>
            <div class="sm">文章</div>
          </div>
          <i class="icon el-icon-document"></i>
        </div>
      </el-col>
      <el-col :span="6">
        <div class="item three">
          <div class="item-left">
            <div class="number">999+</div>
            <div class="sm">图片</div>
          </div>
          <i class="icon el-icon-picture-outline"></i>
        </div>
      </el-col>
      <el-col :span="6">
        <div class="item four">
          <div class="item-left">
            <div class="number">999+</div>
            <div class="sm">链接</div>
          </div>
          <i class="icon el-icon-link"></i>
        </div>
      </el-col>
    </el-row>
    <el-row :gutter="24" class="dashboard-container-other">
      <el-col :span="24">
        <el-calendar>
          <!-- 这里使用的是 2.5 slot 语法，对于新项目请使用 2.6 slot 语法-->
          <template slot="dateCell" slot-scope="{data}">
            <p :class="data.isSelected ? 'is-selected' : ''">
              {{ data.day.split('-').slice(1).join('-') }} {{ data.isSelected ? '✔️' : ''}}
            </p>
          </template>
        </el-calendar>
      </el-col>
    </el-row>
  </div>
</template>

<script>
export default {
  name: 'Dashboard'
}
</script>

<style lang="scss" scoped>
.dashboard {
  &-container {
    margin: 10px 30px;
    &-text {
      margin-bottom: 10px;
    }
    .item-box {
      .one {
        background: rgba($color: #f57e7e, $alpha: 0.8);
        &:hover {
          background: rgba($color: #f57e7e, $alpha: 1);
        }
      }
      .two {
        background: rgba($color: #75b9e6, $alpha: 0.8);
        &:hover {
          background: rgba($color: #75b9e6, $alpha: 1);
        }
      }
      .three {
        background: rgba($color: #71d398, $alpha: 0.8);
        &:hover {
          background: rgba($color: #71d398, $alpha: 1);
        }
      }
      .four {
        background: rgba($color: #e65097, $alpha: 0.8);
        &:hover {
          background: rgba($color: #e65097, $alpha: 1);
        }
      }
      .item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: #ffffff;
        padding: 20px 10px;
        cursor: pointer;
        border-radius: 4px;
        .item-left {
          display: flex;
          flex-direction: column;
          align-items: center;
          margin-left: 20px;
          .number {
            font-size: 50px;
          }
          .sm {
            margin-top: 10px;
          }
        }
        .icon {
          font-size: 80px;
        }
      }
    }
    &-other {
      margin-top: 10px;
    }
  }
}
</style>
