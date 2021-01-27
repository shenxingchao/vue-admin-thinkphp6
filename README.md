# vue-admin-thinkphp6
## 基于vue-admin-elementui 和 thinkphp6.0 的 前后端分离项目模板 ——登录 增删改查 权限管理
[在线预览](http://demo.o8o8o8.com/vue-admin-thinkphp6/#/dashboard)
用户名admin 密码admin


### 目录结构
```
├── client/#前端代码
|  ├── build/#预览入口
|  ├── public/#首页
|  └── src/#资源根目录
|     ├── api/#接口目录
|     |  └── permission/#权限接口
|     ├── assets/#资源目录
|     |  ├── 404_images/
|     |  └── login/
|     ├── components/#组件目录
|     |  ├── Breadcrumb/#面包屑
|     |  ├── CustomTable/#自定义表格
|     |  ├── CustomTag/#自定义标签
|     |  ├── Hamburger/
|     |  ├── HeaderSearch/#搜索
|     |  ├── QuillEditor/#编辑器
|     |  ├── RightPanel/#右下角面板
|     |  ├── Screenfull/#全屏
|     |  ├── SvgIcon/#图标
|     |  ├── ToolBar/#工具栏
|     |  └── Upload/#上传
|     ├── directive/#指令
|     |  └── el-drag-dialog/#对话框可拖拽
|     ├── icons/#图标
|     |  └── svg/
|     ├── layout/#后台模板
|     |  ├── components/
|     |  |  ├── Settings/#设置
|     |  |  ├── Sidebar/#侧边栏
|     |  |  └── TagsView/#标签栏
|     |  └── mixin/#混入
|     ├── mock/#mockjs
|     ├── router/#路由
|     ├── store/#状态
|     |  └── modules/#模块
|     ├── styles/#样式
|     ├── utils/#工具类
|     └── views/#视图
|        ├── curd-template/#增删改查demo
|        ├── dashboard/#控制台
|        ├── login/#登录
|        ├── permission/#权限
|        |  ├── admin/#管理员
|        |  ├── role/#角色
|        |  └── route_resource/#路由资源
|        └── redirect/#重定向
├── server/#后端目录
|   ├── apidoc-template/#api接口文档生成模板
|   ├── app/#应用目录
|   |  └── admin/#后台应用
|   |     ├── config/#配置
|   |     ├── controller/#控制器
|   |     ├── middleware/#中间件
|   |     ├── route/#路由
|   |     └── validate/#验证器
|   ├── config/#全局配置
|   ├── extend/#扩展类
|   ├── public/#公共
|   |  └── static/
|   ├── runtime/#运行产生的文件
|   └── vendor/#composer类库
└──vue_admin_thinkphp6.sql#数据库文件
```