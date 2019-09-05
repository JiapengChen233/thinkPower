<?php


namespace app\admin\model;


use Exception;
use think\Collection;
use think\Db;
use think\Paginator;

/**
 * 权限实体类
 * @package app\admin\model
 * @author RonaldoC
 * @version 1.0.0
 * @date 2019-8-26 19:52:06
 */
class Power extends Base
{
    /**
     * 根据查询条件分页查询数据
     * @param $params array          [查询条件]
     * @return Paginator             [分页对象]
     * @throws Exception
     * @author RonaldoC
     * @version 1.0.0
     * @date 2019-8-28 19:20:06
     */
    public function listByPage($params)
    {
        $condition = [];
        if (isset($params['name']) && !empty($params['name'])) {
            $condition[] = ['p.name', 'like', '%' . $params['name'] . '%'];
        }
        if (isset($params['module']) && !empty($params['module'])) {
            $condition[] = ['p.module', 'like', '%' . $params['module'] . '%'];
        }
        if (isset($params['controller']) && !empty($params['controller'])) {
            $condition[] = ['p.controller', 'like', '%' . $params['controller'] . '%'];
        }
        if (isset($params['action']) && !empty($params['action'])) {
            $condition[] = ['p.action', 'like', '%' . $params['action'] . '%'];
        }
        if (isset($params['start']) && !empty($params['start'])) {
            $condition[] = ['p.create_time', '>=', $params['start']];
        }
        if (isset($params['end']) && !empty($params['end'])) {
            $condition[] = ['p.create_time', '<=', $params['end']];
        }
        return $this->alias('p')
            ->field('p.id,p.name,p.module,p.controller,p.action,p.type,p.par_id,p.enabled,p.create_time')
            ->field('(select count(*) from t_power p2 where p2.par_id=p.id) hasSub')
            ->where($condition)
            ->order('p.id asc')
            ->paginate(0, false, ['query' => $params]); // listRows为0则从配置文件中获取
    }

    /**
     * 根据条件查询权限信息
     * @param $params array          [查询条件]
     * @return Collection            [集合对象]
     * @throws Exception
     * @author RonaldoC
     * @version 1.0.0
     * @date 2019-8-26 19:17:10
     */
    public function listByCondition($params)
    {
        return $this->alias('p')
            ->field('p.id,p.name,p.module,p.controller,p.action,p.icon,p.type,p.par_id,p.enabled,p.create_time')
            ->field('(select count(*) from t_power p2 where p2.par_id=p.id) hasSub')
            ->where($params)
            ->select();
    }

    /**
     * 新增
     * @param array $power         [权限信息]
     * @return bool                [成功返回true，失败返回false]
     * @author RonaldoC
     * @version 1.0.0
     * @date 2019-8-27 18:33:52
     */
    public function add($power)
    {
        return $this->save($power);
    }

    /**
     * 编辑
     * @param array $power          [权限信息]
     * @return bool                 [成功返回true，失败返回false]
     * @author RonaldoC
     * @version 1.0.0
     * @date 2019-8-28 18:37:52
     */
    public function edit($power)
    {
        return $power->save();
    }

    /**
     * 批量删除
     * @param $ids string          [ID集合]
     * @return int                 [返回成功删除的条数]
     * @throws Exception
     * @author RonaldoC
     * @version 1.0.0
     * @date 2019-8-28 19:19:44
     */
    public function batchDelete($ids)
    {
        return Db::name('power')
            ->where('id', 'in', $ids)
            ->useSoftDelete('state', date('Y-m-d G:i:s'))
            ->delete();
    }
}