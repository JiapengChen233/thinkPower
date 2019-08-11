<?php


namespace app\admin\validate;

use think\Validate;

/**
 * 角色验证类
 * @package app\admin\validate
 * @author RonaldoC
 * @version 1.0.0
 * @date 2019-8-10 13:43:22
 */
class Role extends Validate
{
    protected $rule = [
        'name' => ['require'],
        'desc' => ['require'],
    ];

    protected $message = [
        'name.require' => '角色名称必须！',
        'desc.require' => '描述必须！',
    ];
}