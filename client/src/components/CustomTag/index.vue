<template>
  <el-tag :type="type" size="mini">{{title}}</el-tag>
</template>

<script>
export default {
  name: 'CustomTag',
  props: {
    type: {
      type: String,
      default: ''
    },
    title: {
      type: String,
      default: ''
    }
  }
}
</script>

<style>
</style>