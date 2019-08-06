<?php


namespace app\admin\validate;

use think\Validate;

/**
 * 用户验证类
 * @package app\admin\validate
 * @author RonaldoC
 * @version 1.0.0
 * @date 2019-8-4 00:18:38
 */
class User extends Validate
{
    protected $rule = [
        'account' => ['require', 'regex' => '^[A-Za-z][A-Za-z0-9_]{4,}$'],
        'nickname' => ['require'],
        'phone' => 'mobile',
        'email' => 'email',
    ];

    protected $message = [
        'account.require' => '登录名必须！',
        'account.regex' => '要求以字母开头，后跟字母或数字或下划线，不少于5位！',
        'nickname.require' => '昵称必须！',
        'phone' => '手机格式错误！',
        'email' => '邮箱格式错误！',
    ];
}