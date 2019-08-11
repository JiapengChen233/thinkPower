<?php


namespace app\admin\model;


use Exception;
use think\Collection;
use think\Db;
use think\Paginator;

/**
 * 角色实体类
 * @package app\admin\model
 * @author RonaldoC
 * @version 1.0.0
 * @date 2019-8-10 12:19:47
 */
class Role extends Base
{
    /**
     * 根据查询条件分页查询数据
     * @param $params array          [查询条件]
     * @return Paginator             [分页对象]
     * @throws Exception
     * @author RonaldoC
     * @version 1.0.0
     * @date 2019-8-10 13:46:1
     */
    public function listByPage($params)
    {
        $condition = [];
        if (isset($params['name']) && !empty($params['name'])) {
            $condition[] = ['r.name', 'like', '%' . $params['name'] . '%'];
        }
        if (isset($params['start']) && !empty($params['start'])) {
            $condition[] = ['r.create_time', '>', $params['start'] . ' 00:00:00'];
        }
        if (isset($params['end']) && !empty($params['end'])) {
            $condition[] = ['r.create_time', '<= time', $params['end'] . '23:59:59'];
        }
        // 过滤超级管理员
        $condition[] = ['r.id', '<>', 1];
        return $this->alias('r')
            ->field('r.id,r.name,r.desc,r.enabled,r.create_time')
            ->where($condition)
            ->order('r.id desc')
            ->paginate(0, false, ['query' => $params]); // listRows为0则从配置文件中获取
    }

    /**
     * 根据条件查询角色信息
     * @param $params array          [查询条件]
     * @return Collection            [集合对象]
     * @throws Exception
     * @author RonaldoC
     * @version 1.0.0
     * @date 2019-8-10 13:46:40
     */
    public function listByCondition($params)
    {
        // 过滤超级管理员
        $params[] = ['r.id', '<>', 1];
        return $this->alias('r')
            ->field('r.id,r.name,r.desc,r.enabled,r.create_time')
            ->where($params)
            ->select();
    }

    /**
     * 根据id查询角色信息
     * @param $id int          [角色id]
     * @return mixed           [存在返回Role对象，不存在返回null]
     * @author RonaldoC
     * @version 1.0.0
     * @date 2019-8-10 13:47:38
     */
    public function getById($id)
    {
        return $this::get($id);
    }

    /**
     * 新增
     * @param array $role          [角色信息]
     * @return bool                [成功返回true，失败返回false]
     * @author RonaldoC
     * @version 1.0.0
     * @date 2019-8-10 13:47:24
     */
    public function add($role)
    {
        return $this->save($role);
    }

    /**
     * 编辑
     * @param array $role          [角色信息]
     * @return bool                [成功返回true，失败返回false]
     * @author RonaldoC
     * @version 1.0.0
     * @date 2019-8-10 13:47:43
     */
    public function edit($role)
    {
        return $role->save();
    }

    /**
     * 批量删除
     * @param $ids string          [ID集合]
     * @return int                 [返回成功删除的条数]
     * @throws Exception
     * @author RonaldoC
     * @version 1.0.0
     * @date 2019-8-10 13:47:48
     */
    public function batchDelete($ids)
    {
        return Db::name('role')
            ->where('id', 'in', $ids)
            ->useSoftDelete('state', date('Y-m-d G:i:s'))
            ->delete();
    }

}