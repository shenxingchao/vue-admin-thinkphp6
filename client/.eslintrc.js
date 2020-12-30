module.exports = {
  root: true,
  env: {
    node: true
  },
  extends: ['plugin:vue/recommended', 'eslint:recommended', 'prettier'], //prettier放最后解决冲突
  parserOptions: {
    parser: 'babel-eslint'
  },
  rules: {
    'vue/max-attributes-per-line': [
      'error', //不符合报错
      {
        singleline: 100, //单行最多属性数量
        multiline: {
          max: 100, //多行最多属性数量
          allowFirstLine: true //是否允许属性在第一行 标签行
        }
      }
    ],

    'vue/html-closing-bracket-newline': [
      //结束标签换行 > 配置为不换行
      'error',
      {
        singleline: 'never',
        multiline: 'never'
      }
    ],
    'vue/html-indent': [
      'error',
      2,
      {
        attribute: 1,
        baseIndent: 1,
        closeBracket: 0,
        alignAttributesVertically: true,
        ignores: []
      }
    ],
    'vue/mustache-interpolation-spacing': 0, //{{}}之间有没有空格
    'vue/html-self-closing': [
      //html标签闭合规则
      'error',
      {
        html: {
          void: 'always',
          normal: 'any',
          component: 'any'
        },
        svg: 'always',
        math: 'always'
      }
    ],
    'vue/singleline-html-element-content-newline': 'off',
    'vue/multiline-html-element-content-newline': 'off',
    'vue/name-property-casing': ['error', 'PascalCase'],
    'vue/no-v-html': 'off', //以上几个与vetur冲突
    'no-console': process.env.NODE_ENV === 'production' ? 'error' : 'off',
    'no-debugger': process.env.NODE_ENV === 'production' ? 'error' : 'off',
    quotes: ['error', 'single'], //强制使用单引号
    semi: ['error', 'never'], //强制不使用分号结尾
    'no-unused-vars': 0 //变量未定义不提示
  }
}
