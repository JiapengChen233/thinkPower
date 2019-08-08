<?php


namespace app\admin\model;

use think\Model;
use think\model\concern\SoftDelete;

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

    use SoftDelete; // 设置软删除
    protected $deleteTime = 'state';
    protected $defaultSoftDelete = 1;

    protected static function init()
    {
        // 新增前设置
        self::beforeInsert(function ($user) {
            $user['creator'] = 1;
            $user['updater'] = 1;
        });

        // 更新前设置
        self::beforeUpdate(function ($user) {
            $user['creator'] = 1;
            $user['updater'] = 1;
        });
    }

}