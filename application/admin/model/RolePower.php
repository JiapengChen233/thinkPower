<?php


namespace app\admin\model;


use Exception;
use think\Collection;

/**
 * 角色权限实体类
 * @package app\admin\model
 * @author RonaldoC
 * @version 1.0.0
 * @date 2019-8-29 20:11:12
 */
class RolePower extends Base
{

    /**
     * 新增
     * @param array $data         [角色信息]
     * @return Collection         [返回成功的条数]
     * @throws Exception
     * @version 1.0.0
     * @date 2019-8-29 20:13:25
     * @author RonaldoC
     */

    public function addAll($data)
    {
        return $this->saveAll($data);
    }

}