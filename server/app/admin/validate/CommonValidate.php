<?php
declare (strict_types = 1);

namespace app\admin\validate;

use think\Validate;

class CommonValidate extends Validate {
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'page'     => 'require',
        'pageSize' => 'require',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名' =>  '错误信息'
     *
     * @var array
     */
    protected $message = [
        'page.require'     => '缺少page字段',
        'pageSize.require' => '缺少pageSize字段',
    ];

    protected $scene = [
        'role_list' => [
            'page',
            'pageSize',
        ],
        'admin_list' => [
            'page',
            'pageSize',
        ],
    ];
}
