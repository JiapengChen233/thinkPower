<?php


namespace app\admin\model;


use Exception;
use think\Collection;
use think\exception\PDOException;
use think\Model;

/**
 * 角色权限实体类
 * @package app\admin\model
 * @author RonaldoC
 * @version 1.0.0
 * @date 2019-8-29 20:11:12
 */
class RolePower extends Model
{

    /**
     * 新增
     * @param array $data [角色信息]
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

    /**
     * 根据条件查询角色权限信息
     * @param $params array          [查询条件]
     * @return Collection            [集合对象]
     * @throws Exception
     * @author RonaldoC
     * @version 1.0.0
     * @date 2019-9-2 17:14:28
     */
    public function listByCondition($params)
    {
        return $this->alias('rp')
            ->field('rp.id,rp.role_id,rp.power_id')
            ->where($params)
            ->select();
    }

    /**
     * 根据角色ID删除角色权限信息
     * @param $role_id string          [角色ID]
     * @return int                     [成功删除的条数]
     * @throws Exception
     * @throws PDOException
     * @author RonaldoC
     * @version 1.0.0
     * @date 2019-9-2 17:29:04
     */
    public function deleteByRoleId($role_id)
    {
        return RolePower::where('role_id', $role_id)->delete();
    }

    /**
     * 根据角色ID集合删除角色权限信息
     * @param $ids string               [角色ID集合]
     * @return int                     [成功删除的条数]
     * @throws Exception
     * @throws PDOException
     * @author RonaldoC
     * @version 1.0.0
     * @date 2019-9-3 15:32:16
     */
    public function deleteByRoleIds($ids)
    {
        return RolePower::where('role_id', 'in', $ids)->delete();
    }

}