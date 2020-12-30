import Vue from 'vue'

import 'normalize.css/normalize.css' // A modern alternative to CSS resets
import ElementUI from 'element-ui'
import '@/styles/index.scss' // global css

import App from './App'
import store from './store'
import router from './router'

import '@/icons' // icon
import '@/permission' // permission control

Vue.use(ElementUI)
Vue.config.productionTip = false

import './mock' //引入mock.js 模拟数据 开启这里 使用moke/index.js文件拦截
import elDragDialog from '@/directive/el-drag-dialog' //引入dialog组件可拖拽指令 使用方法 标签上加上v-el-drag-dialog即可
Vue.use(elDragDialog)

new Vue({
  el: '#app',
  router,
  store,
  render: h => h(App)
})
