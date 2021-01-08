<?php
declare (strict_types = 1);

namespace app\admin\validate;

use think\Validate;

class Admin extends Validate {
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'id'          => 'require',
        'username'    => 'require|min:2|max:20',
        'password'    => 'require',
        'role_ids'    => 'require',
        'ids.require' => '请选择要删除的数据',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名' =>  '错误信息'
     *
     * @var array
     */
    protected $message = [
        'id.require'       => 'id不能为空',
        'username.require' => '请输入账号名',
        'username.min'     => '账号名最少2个字',
        'username.max'     => '账号名最多20个字',
        'role_ids.require' => '请选择角色',
        'ids.require'      => '请选择要删除的数据',
    ];

    protected $scene = [
        'add'    => [
            'username',
            'role_ids',
        ],
        'detail' => [
            'id',
        ],
        'edit'   => [
            'id',
            'username',
            'role_ids',
        ],
        'delete' => [
            'ids',
        ],
    ];
}
