<?php


namespace app\admin\validate;

use think\Validate;

/**
 * 权限验证类
 * @package app\admin\validate
 * @author RonaldoC
 * @version 1.0.0
 * @date 2019-8-27 18:28:33
 */
class Power extends Validate
{
    protected $rule = [
        'par_id' => ['require', 'min' => 0],
        'name' => ['require'],
        'module' => ['require'],
    ];

    protected $message = [
        'par_id.require' => '父级必须！',
        'par_id.regex' => '父级！',
        'name.require' => '权限名称必须！',
        'module.require' => '模块必须！',
    ];
}