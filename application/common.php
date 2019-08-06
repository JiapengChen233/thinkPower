<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

/**
 * 生成随机字符串
 * @param int $length [长度，默认6]
 * @return string
 * @author RonaldoC
 * @version 1.0.0
 * @date 2019-8-4 18:31:51
 */
function gen_random_str($length = 6)
{
    $pattern = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ';
    $pattern_length = strlen($pattern) - 1;
    $output = '';
    for ($i = 0; $i < $length; $i++) {
        $output .= $pattern[mt_rand(0, $pattern_length)];
    }
    return $output;
}
