<?php


namespace app\admin\model;


use Exception;
use think\Collection;
use think\Db;
use think\Paginator;

/**
 * 用户实体类
 * @package app\admin\model
 * @author RonaldoC
 * @version 1.0.0
 * @date 2019-8-4 00:19:26
 */
class User extends Base
{
    /**
     * 根据查询条件分页查询数据
     * @param $params array          [查询条件]
     * @return Paginator             [分页对象]
     * @throws Exception
     * @author RonaldoC
     * @version 1.0.0
     * @date 2019-8-6 19:54:21
     */
    public function listByPage($params)
    {
        $condition = [];
        if (isset($params['account']) && !empty($params['account'])) {
            $condition[] = ['u.account', 'like', '%' . $params['account'] . '%'];
        }
        if (isset($params['nickname']) && !empty($params['nickname'])) {
            $condition[] = ['u.nickname', 'like', '%' . $params['nickname'] . '%'];
        }
        if (isset($params['start']) && !empty($params['start'])) {
            $condition[] = ['u.create_time', '>=', $params['start']];
        }
        if (isset($params['end']) && !empty($params['end'])) {
            $condition[] = ['u.create_time', '<=', $params['end']];
        }
        // 过滤超级管理员
        $condition[] = ['u.id', '<>', 1];
        return $this->alias('u')
            ->field('u.id,u.name,u.nickname,u.account,u.phone,u.email,u.last_login_time,u.locked,u.create_time')
            ->field('r.name as role_name')
            ->leftJoin(['__ROLE__' => 'r'], 'u.role_id=r.id')
            ->where($condition)
            ->order('u.id desc')
            ->paginate(0, false, ['query' => $params]); // listRows为0则从配置文件中获取
    }

    /**
     * 根据条件查询用户信息
     * @param $params array          [查询条件]
     * @return Collection            [集合对象]
     * @throws Exception
     * @author RonaldoC
     * @version 1.0.0
     * @date 2019-8-6 19:57:04
     */
    public function listByCondition($params)
    {
        return $this->alias('u')
            ->field('u.id,u.name,u.nickname,u.account,u.phone,u.email,u.last_login_time,u.locked')
            ->where($params)
            ->select();
    }

    /**
     * 根据id查询用户信息
     * @param $id int          [用户id]
     * @return mixed           [存在返回User对象，不存在返回null]
     */
    public function getById($id)
    {
        return $this::get($id);
    }

    /**
     * 新增
     * @param array $user          [用户信息]
     * @return bool                [成功返回true，失败返回false]
     * @author RonaldoC
     * @version 1.0.0
     * @date 2019-8-6 19:54:36
     */
    public function add($user)
    {
        return $this->save($user);
    }

    /**
     * 编辑
     * @param array $user          [用户信息]
     * @return bool                [成功返回true，失败返回false]
     * @author RonaldoC
     * @version 1.0.0
     * @date 2019-8-6 19:54:36
     */
    public function edit($user)
    {
        return $user->save();
    }

    /**
     * 批量删除
     * @param $ids string          [ID集合]
     * @return int                 [返回成功删除的条数]
     * @throws Exception
     * @author RonaldoC
     * @version 1.0.0
     * @date 2019-8-9 00:06:50
     */
    public function batchDelete($ids)
    {
        return Db::name('user')
            ->where('id', 'in', $ids)
            ->useSoftDelete('state', date('Y-m-d G:i:s'))
            ->delete();
    }

}