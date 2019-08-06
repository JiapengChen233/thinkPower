<?php


namespace app\admin\model;

use think\Model;

/**
 * 基础实体类
 * @package app\admin\model
 * @author RonaldoC
 * @version 1.0.0
 * @date 2019-8-4 00:18:38
 */
class Base extends Model
{
    protected $autoWriteTimestamp = 'datetime'; // 开启自动时间戳

    protected static function init()
    {
        // 新增前设置
        self::beforeInsert(function ($user) {
            $user['creator'] = 1;
            $user['updater'] = 1;
        });
    }

}