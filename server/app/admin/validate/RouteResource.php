<?php
declare (strict_types = 1);

namespace app\admin\validate;

use think\Validate;

class RouteResource extends Validate {
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'id'        => 'require',
        'title'     => 'require|max:6',
        'path'      => 'require',
        'name'      => 'require',
        'component' => 'require',
        'parent_id' => 'require',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名' =>  '错误信息'
     *
     * @var array
     */
    protected $message = [
        'id.require'        => 'id不能为空',
        'title.require'     => '请输入菜单名称',
        'title.max'         => '菜单名称最多6个字',
        'path.require'      => '请输入路由地址',
        'name.require'      => '请输入路由名称',
        'component.require' => '请输入映射组件名称',
        'parent_id.require' => '请选择上级路由',
    ];

    protected $scene = [
        'add'    => [
            'title',
            'path',
            'name',
            'component',
            'parent_id',
        ],
        'detail' => [
            'id',
        ],
        'edit'   => [
            'id',
            'title',
            'path',
            'name',
            'component',
            'parent_id',
        ],
        'delete' => [
            'id',
        ],
    ];
}
