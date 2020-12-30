//示例
// 'data|100': [
//   //生成100条数据 数组
//   {
//     'shopId|+1': 1, //生成商品id，自增1
//     shopMsg: '@ctitle(10)', //生成商品信息，长度为10个汉字
//     shopName: '@csentence', //生成商品名 ， 都是中国人的名字
//     shopTel: /^1(5|3|7|8)[0-9]{9}$/, //生成随机电话号
//     shopAddress: '@county(true)', //随机生成地址
//     'shopStar|1-5': '', //随机生成1-5个字符串
//     'salesVolume|30-1000': 30, //随机生成商品价格 在30-1000之间
//     shopLogo: '@Image("100x40","#c33", "#ffffff","小北鼻")', //生成随机图片，大小/背景色/字体颜色/文字信息
//     'food|7': [
//       //每个商品中再随机生成七个food
//       {
//         foodName: '@cname', //food的名字
//         foodPic: '@Image("100x40","#c33", "#ffffff","小可爱")', //生成随机图片，大小/背景色/字体颜色/文字信息
//         'foodPrice|1-100': 20, //生成1-100的随机数
//         'aname|14': [
//           {
//             aname: '@cname',
//             'aprice|30-60': 20
//           }
//         ]
//       }
//     ]
//   }
// ]
// mockjs 在线编辑器 http://mockjs.com/0.1/editor.html#help

const Mock = require('mockjs')

//用户登录
let login = Mock.mock({
  message: 'success',
  code: 20000,
  data: {
    token: '@word(32)'
  }
})
Mock.mock(/User\/login/, 'post', () => {
  return login
})

//获取用户信息
let getUserInfo = Mock.mock({
  message: 'success',
  code: 20000,
  data: {
    username: '@cword(5)',
    avatar:
      'https://wpimg.wallstcn.com/f778738c-e4f8-4870-b634-56703b4acafe.gif',
    roles: [1, 2, 3] //模拟返回角色id 1，2，3
  }
})
Mock.mock(/UserCenter\/getInfo/, 'get', () => {
  return getUserInfo
})

//用户退出
let logout = Mock.mock({
  message: 'success',
  code: 20000,
  data: {}
})
Mock.mock(/UserCenter\/logout/, 'post', () => {
  return logout
})

//根据用户角色获取权限
let getPermissionRouter = Mock.mock({
  message: 'success',
  code: 20000,
  data: [
    {
      path: '/',
      component: 'Layout',
      redirect: '/dashboard',
      children: [
        {
          path: 'dashboard',
          name: 'Dashboard',
          component: 'Dashboard',
          meta: {
            title: '控制台',
            icon: 'dashboard',
            affix: true
          }
        }
      ]
    },
    {
      path: '/article',
      name: 'Article',
      component: 'Layout',
      redirect: '/article/article-list',
      // redirect: 'noRedirect',
      alwaysShow: true,
      hidden: false,
      meta: {
        title: '文章管理',
        icon: 'table'
      },
      children: [
        {
          path: 'article-list',
          name: 'ArticleList',
          component: 'ArticleList',
          alwaysShow: false,
          hidden: false,
          meta: {
            title: '文章列表',
            icon: 'table'
          }
        },
        {
          path: 'article-add',
          name: 'ArticleAdd',
          alwaysShow: false,
          hidden: true,
          component: 'ArticleAdd',
          meta: {
            title: '文章添加'
          }
        },
        {
          path: 'article-edit',
          name: 'ArticleEdit',
          alwaysShow: false,
          hidden: true,
          component: 'ArticleEdit',
          meta: {
            title: '文章编辑'
          }
        }
      ]
    }
  ]
})
Mock.mock(/UserCenter\/getPermissionRouter/, 'post', () => {
  return getPermissionRouter
})

//单文件上传
let fileUpload = Mock.mock({
  message: 'success',
  code: 20000,
  data: {
    imgUrl:
      'https://iph.href.lu/400x300?text=%E4%B8%8A%E4%BC%A0%E8%BF%94%E5%9B%9E%E7%9A%84%E5%9B%BE%E7%89%87&fg=ffffff&bg=2986cc'
  }
})
Mock.mock(/Upload\/fileUpload/, 'post', () => {
  return fileUpload
})

//获取文章列表
let articleLst = Mock.mock({
  message: 'success',
  code: 20000,
  data: {
    total: 100,
    'data|10': [
      {
        'id|+1': 1,
        title: '@ctitle(6,50)',
        image:
          'https://wpimg.wallstcn.com/f778738c-e4f8-4870-b634-56703b4acafe.gif',
        detail: '@cparagraph()',
        author: '@cname()',
        recommend: true,
        top: false,
        status: true,
        addtime: '@datetime("yyyy-MM-dd HH:mm:ss")',
        updatetime: '@datetime("yyyy-MM-dd HH:mm:ss")'
      }
    ]
  }
})
Mock.mock(/Article\/articleLst/, 'get', () => {
  return articleLst
})

//文章批量删除
let articleDelete = Mock.mock({
  message: 'success',
  code: 20000,
  data: {}
})
Mock.mock(/Article\/articleDelete/, 'post', () => {
  return articleDelete
})

//文章添加
let articleAdd = Mock.mock({
  message: 'success',
  code: 20000,
  data: {}
})
Mock.mock(/Article\/articleAdd/, 'post', () => {
  return articleAdd
})

//获取文章详情
let articleDetail = Mock.mock({
  message: 'success',
  code: 20000,
  data: {
    'id|+1': 1,
    title: '@ctitle(6,50)',
    image:
      'https://wpimg.wallstcn.com/f778738c-e4f8-4870-b634-56703b4acafe.gif',
    image_list: [
      'https://wpimg.wallstcn.com/f778738c-e4f8-4870-b634-56703b4acafe.gif',
      'https://wpimg.wallstcn.com/f778738c-e4f8-4870-b634-56703b4acafe.gif',
      'https://wpimg.wallstcn.com/f778738c-e4f8-4870-b634-56703b4acafe.gif'
    ],
    detail: '@cparagraph()',
    author: '@cname()',
    recommend: true,
    top: true,
    status: true,
    addtime: '@datetime("yyyy-MM-dd HH:mm:ss")',
    updatetime: '@datetime("yyyy-MM-dd HH:mm:ss")'
  }
})
Mock.mock(/Article\/articleDetail/, 'get', () => {
  return articleDetail
})

//文章编辑
let articleEdit = Mock.mock({
  message: 'success',
  code: 20000,
  data: {}
})
Mock.mock(/Article\/articleEdit/, 'post', () => {
  return articleEdit
})
