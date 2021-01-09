<?php
declare (strict_types = 1);

namespace app\admin\validate;

use think\Validate;

class User extends Validate {
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'username' => 'require',
        'password' => 'require',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名' =>  '错误信息'
     *
     * @var array
     */
    protected $message = [
        'username.require' => '请输入账号名',
        'password.require' => '请输入密码',
    ];

    protected $scene = [
        'login' => [
            'username',
            'password',
        ],
    ];
}
