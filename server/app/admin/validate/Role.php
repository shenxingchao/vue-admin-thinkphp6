<?php
declare (strict_types = 1);

namespace app\admin\validate;

use think\Validate;

class Role extends Validate {
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'id'                      => 'require',
        'role_name'               => 'require|max:6',
        'route_resource_ids'      => 'require',
        'temp_route_resource_ids' => 'require',
        'ids'                     => 'require',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名' =>  '错误信息'
     *
     * @var array
     */
    protected $message = [
        'id.require'                      => 'id不能为空',
        'role_name.require'               => '请输入角色名称',
        'role_name.max'                   => '角色名称最多6个字',
        'route_resource_ids.require'      => '权限不能为空',
        'temp_route_resource_ids.require' => '权限不能为空',
        'ids.require'                     => '请选择要删除的数据',
    ];

    protected $scene = [
        'add'    => [
            'role_name',
            'route_resource_ids',
            'temp_route_resource_ids',
        ],
        'detail' => [
            'id',
        ],
        'edit'   => [
            'id',
            'role_name',
            'route_resource_ids',
            'temp_route_resource_ids',
        ],
        'delete' => [
            'ids',
        ],
    ];
}
