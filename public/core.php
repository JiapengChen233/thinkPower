<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 基础文件 ]
namespace think;

// HTTP类型
define('__MY_HTTP__', ((!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') || (!empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off') || (!empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) || (!empty($_SERVER['HTTP_FROM_HTTPS']) && $_SERVER['HTTP_FROM_HTTPS'] !== 'off') || (!empty($_SERVER['HTTP_X_CLIENT_SCHEME']) && $_SERVER['HTTP_X_CLIENT_SCHEME'] == 'https')) ? 'https' : 'http');

// 根目录
$my_root = empty($_SERVER['SCRIPT_NAME']) ? '' : substr($_SERVER['SCRIPT_NAME'], 1, strrpos($_SERVER['SCRIPT_NAME'], '/'));
define('__MY_ROOT__', defined('IS_ROOT_ACCESS') ? $my_root : str_replace('public' . '/', '', $my_root));
define('__MY_ROOT_PUBLIC__', defined('IS_ROOT_ACCESS') ? '/' . $my_root . 'public' . '/' : '/' . $my_root);

// 项目HOST
define('__MY_HOST__', empty($_SERVER['HTTP_HOST']) ? '' : $_SERVER['HTTP_HOST']);

// 项目URL地址
define('__MY_URL__', empty($_SERVER['HTTP_HOST']) ? '' : __MY_HTTP__ . '://' . __MY_HOST__ . '/' . substr($my_root, 0, strrpos($my_root, 'public')));

// 项目public目录URL地址
define('__MY_PUBLIC_URL__', empty($_SERVER['HTTP_HOST']) ? '' : __MY_HTTP__ . '://' . __MY_HOST__ . __MY_ROOT_PUBLIC__);
