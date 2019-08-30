<?php


namespace app\admin\controller;

use Exception;
use think\facade\Request;
use think\facade\Validate;
use think\response\Json;
use think\response\View;

/**
 * 权限控制器
 * @package app\admin\controller
 * @author RonaldoC
 * @version 1.0.0
 * @date 2019-8-26 19:19:17
 */
class Power extends Base
{
    /**
     * 权限列表查询
     * @throws Exception
     * @author RonaldoC
     * @version 1.0.0
     * @date 2019-8-28 18:58:08
     */
    public function index()
    {
        // 请求参数
        $params = $this->request->param();

        // 查询数据
        $user = new \app\admin\model\Power();
        $list = $user->listByCondition([]);

        // 按父子级顺序排序
        if (count($list) > 0) {
            $arr = $list->toArray();
            $arr = $this->sortByParAndChild($arr);
            $list = $arr;
        }

        $this->assign('list', $list);
        $this->assign('params', $params);
        return view();
    }

    /**
     * 父子级排序
     * @param $arr array          [待排序的数据]
     * @return array              [已排序的数据]
     * @author RonaldoC
     * @version 1.0.0
     * @date 2019-8-28 18:58:08
     */
    private function sortByParAndChild($arr)
    {
        $new_arr = [];
        $arr1 = array_filter($arr, function ($v) {
            return $v['par_id'] == 0;
        });
        if (count($arr1) > 0) {
            foreach ($arr1 as $v) {
                $new_arr[] = $v;
                $par_id = $v['id'];
                $arr2 = array_filter($arr, function ($v) use ($par_id) {
                    return $v['par_id'] == $par_id;
                });
                if (count($arr2) > 0) {
                    foreach ($arr2 as $v2) {
                        $new_arr[] = $v2;
                        $par_id2 = $v2['id'];
                        $arr3 = array_filter($arr, function ($v) use ($par_id2) {
                            return $v['par_id'] == $par_id2;
                        });
                        if (count($arr3) > 0) {
                            foreach ($arr3 as $v3) {
                                $new_arr[] = $v3;
                            }
                        }
                    }
                }
            }
        }
        return $new_arr;
    }

    /**
     * 权限新增
     * @return Json|View          [GET请求返回页面，POST请求返回JSON]
     * @throws Exception
     * @author RonaldoC
     * @version 1.0.0
     * @date 2019-8-28 18:34:42
     */
    public function add()
    {
        if (Request::isAjax()) {
            if (Request::isGet()) {
                return $this->returnJson(-1, '非法请求！');
            }

            $params = input('post.');

            // 参数校验
            $validate = new \app\admin\validate\Power();
            $result = $validate->check($params);
            if (!$result) {
                return $this->returnJson(-1, $validate->getError());
            }

            // 数据校验
            $mPower = new \app\admin\model\Power();
            $power_list = $mPower->listByCondition([]);
            if (count($power_list) > 0) {
                foreach ($power_list as $v) {
                    if ($v['type'] == 1 && $v['name'] == $params['name']) {
                        return $this->returnJson(-1, '权限名称已存在！');
                    }
                }
            }

            $par_id = $params['par_id'];
            if ($par_id == 0) {
                $params['type'] = 1; // 一级菜单
            }
            if (count($power_list) > 0) {
                $power_arr = $power_list->toArray();
                $power_arr = array_filter($power_arr, function ($v) use ($par_id) {
                    return $v['id'] == $par_id;
                });
                if (count($power_arr) > 0) {
                    $power_arr = array_values($power_arr);
                    $type = $power_arr[0]['type'];
                    if ($type == 1) {
                        $params['type'] = 2; // 二级菜单
                    } else if ($type == 2) {
                        $params['type'] = 3; // 按钮
                    }
                }
            }
            $params['enabled'] = 1;

            // 启动事物
            $Db = $mPower->db(false);
            $Db->startTrans();
            try {
                $res_flag = $mPower->add($params);
                if (!$res_flag) {
                    // 回滚事物
                    $Db->rollback();
                    return $this->returnJson(-1, "新增失败！");
                }

                // 提交事物
                $Db->commit();
            } catch (Exception $e) {
                // 回滚事物
                $Db->rollback();
                return $this->returnJson(-1, "新增失败！");
            }

            return $this->returnJson(1, '新增成功！');
        } else {
            if (Request::isPost()) {
                return $this->returnJson(-1, '非法请求！');
            }

            // 父级下拉框选项查询
            $mPower = new \app\admin\model\Power();
            $power_list = $mPower->listByCondition(['par_id' => 0]);
            if (count($power_list) > 0) {
                foreach ($power_list as &$v) {
                    $power_list2 = $mPower->listByCondition(['par_id' => $v['id']]);
                    $v['childrens'] = $power_list2;
                }
            }
            $this->assign('power_list', $power_list);

            return view();
        }
    }

    /**
     * 权限编辑
     * @return Json|View          [GET请求返回页面，POST请求返回JSON]
     * @throws Exception
     * @author RonaldoC
     * @version 1.0.0
     * @date 2019-8-28 18:59:21
     */
    public function edit()
    {
        if (Request::isAjax()) {
            if (Request::isGet()) {
                return $this->returnJson(-1, '非法请求！');
            }

            $params = input('post.');

            // 参数校验
            if (!isset($params['id'])) {
                return $this->returnJson(-1, '缺少请求参数！');
            }
            $validate = new \app\admin\validate\Power();
            $result = $validate->check($params);
            if (!$result) {
                return $this->returnJson(-1, $validate->getError());
            }


            // 数据校验
            $mPower = new \app\admin\model\Power();
            $power = $mPower->getById($params['id']);
            if (!$power) {
                return $this->returnJson(-1, '请求参数错误！');
            }
            $power_list = $mPower->listByCondition([]);
            if (count($power_list) > 0) {

                $par_id = $params['par_id'];
                if ($par_id == 0) {
                    $params['type'] = 1; // 一级菜单
                }
                if (count($power_list) > 0) {
                    $power_arr = $power_list->toArray();
                    $power_arr = array_filter($power_arr, function ($v) use ($par_id) {
                        return $v['id'] == $par_id;
                    });
                    if (count($power_arr) > 0) {
                        $power_arr = array_values($power_arr);
                        $type = $power_arr[0]['type'];
                        if ($type == 1) {
                            $params['type'] = 2; // 二级菜单
                        } else if ($type == 2) {
                            $params['type'] = 3; // 按钮
                        }
                    }
                }

                foreach ($power_list as $v) {
                    if ($v['id'] != $params['id'] && $params['type'] == 1 && $v['name'] == $params['name']) {
                        return $this->returnJson(-1, '权限名称已存在！');
                    }
                }
            }

            $power['par_id'] = $params['par_id'];
            $power['name'] = $params['name'];
            $power['module'] = $params['module'];
            $power['controller'] = $params['controller'];
            $power['action'] = $params['action'];
            $power['icon'] = $params['icon'];

            // 启动事物
            $Db = $mPower->db(false);
            $Db->startTrans();
            try {
                $res_flag = $mPower->edit($power);
                if (!$res_flag) {
                    // 回滚事物
                    $Db->rollback();
                    return $this->returnJson(-1, "编辑失败！");
                }

                // 提交事物
                $Db->commit();
            } catch (Exception $e) {
                // 回滚事物
                $Db->rollback();
                return $this->returnJson(-1, "编辑失败！");
            }

            return $this->returnJson(1, '编辑成功！');
        } else {
            if (Request::isPost()) {
                return $this->returnJson(-1, '非法请求！');
            }

            $params = input('get.');

            // 参数校验
            if (!isset($params['id'])) {
                return $this->returnJson(-1, '缺少请求参数！');
            }

            // 数据校验
            $mPower = new \app\admin\model\Power();
            $power = $mPower->getById($params['id']);
            if (!$power) {
                return $this->returnJson(-1, '请求参数错误！');
            }

            // 父级下拉框选项查询
            $power_list = $mPower->listByCondition(['par_id' => 0]);
            if (count($power_list) > 0) {
                foreach ($power_list as &$v) {
                    $power_list2 = $mPower->listByCondition(['par_id' => $v['id']]);
                    $v['childrens'] = $power_list2;
                }
            }

            // 返回参数
            $this->assign('power', $power);
            $this->assign('power_list', $power_list);
            return view('add');
        }
    }

    /**
     * 权限停用
     * @return Json|void          [GET请求返回页面，POST请求返回JSON]
     * @throws Exception
     * @author RonaldoC
     * @version 1.0.0
     * @date 2019-8-28 18:34:54
     */
    public function powerStop()
    {
        if (Request::isAjax()) {
            if (Request::isGet()) {
                return $this->returnJson(-1, '非法请求！');
            }

            $params = input('post.');

            // 参数校验
            if (!isset($params['id'])) {
                return $this->returnJson(-1, '缺少请求参数！');
            }

            // 数据校验
            $mPower = new \app\admin\model\Power();
            $power = $mPower->getById($params['id']);
            if (!$power) {
                return $this->returnJson(-1, '请求参数错误！');
            }

            // 停用
            if ($power['enabled'] === 1) {
                $power['enabled'] = 0;

                $power_list = $mPower->listByCondition(['par_id' => $power['id'], 'enabled' => 1]);
                if (count($power_list) > 0) {
                    return $this->returnJson(-1, '请先停用该权限的子级再进行停用操作！');
                }

            } else { // 启用
                $power['enabled'] = 1;
            }

            // 启动事物
            $Db = $mPower->db(false);
            $Db->startTrans();
            try {
                $res_flag = $mPower->edit($power);
                if (!$res_flag) {
                    // 回滚事物
                    $Db->rollback();
                    return $this->returnJson(-1, "操作失败！");
                }

                // 提交事物
                $Db->commit();
            } catch (Exception $e) {
                // 回滚事物
                $Db->rollback();
                return $this->returnJson(-1, "操作失败！");
            }

            return $this->returnJson(1, '操作成功！');
        } else {
            return $this->error('非法请求！');
        }
    }

    /**
     * 权限删除
     * @return Json|void          [GET请求返回页面，POST请求返回JSON]
     * @throws Exception
     * @author RonaldoC
     * @version 1.0.0
     * @date 2019-8-28 18:35:04
     */
    public function delete()
    {
        if (Request::isAjax()) {
            if (Request::isGet()) {
                return $this->returnJson(-1, '非法请求！');
            }

            $params = input('post.');

            if (isset($params['ids'])) { // 批量删除

                // 数据校验
                $id_arr = explode(',', $params['ids']);
                $condition[] = ['p.id', 'in', $params['ids']];
                $mPower = new \app\admin\model\Power();
                $power_list = $mPower->listByCondition($condition);
                if (count($id_arr) != count($power_list)) {
                    return $this->returnJson(-1, '请求参数错误！');
                }

                foreach ($power_list as $v) {
                    $temp_list = $mPower->listByCondition(['par_id' => $v['id'], 'enabled' => 1]);
                    if (count($temp_list) > 0) {
                        return $this->returnJson(-1, '请先停用该权限的子级再进行删除操作！');
                    }
                }

                // 启动事物
                $Db = $mPower->db(false);
                $Db->startTrans();
                try {
                    $count = $mPower->batchDelete($params['ids']);
                    if ($count != count($power_list)) {
                        // 回滚事物
                        $Db->rollback();
                        return $this->returnJson(-1, "删除失败！");
                    }

                    // 提交事物
                    $Db->commit();
                } catch (Exception $e) {
                    // 回滚事物
                    $Db->rollback();
                    return $this->returnJson(-1, "删除失败！");
                }
            } else { // 删除单个

                // 参数校验
                if (!isset($params['id'])) {
                    return $this->returnJson(-1, '缺少请求参数！');
                }

                // 数据校验
                $mPower = new \app\admin\model\Power();
                $power = $mPower->getById($params['id']);
                if (!$power) {
                    return $this->returnJson(-1, '请求参数错误！');
                }
                $power_list = $mPower->listByCondition(['par_id' => $power['id'], 'enabled' => 1]);
                if (count($power_list) > 0) {
                    return $this->returnJson(-1, '请先停用该权限的子级再进行删除操作！');
                }

                // 启动事物
                $Db = $mPower->db(false);
                $Db->startTrans();
                try {
                    $res_flag = $power->delete();
                    if (!$res_flag) {
                        // 回滚事物
                        $Db->rollback();
                        return $this->returnJson(-1, "删除失败！");
                    }

                    // 提交事物
                    $Db->commit();
                } catch (Exception $e) {
                    // 回滚事物
                    $Db->rollback();
                    return $this->returnJson(-1, "删除失败！");
                }
            }

            return $this->returnJson(1, '删除成功！');
        } else {
            return $this->error('非法请求！');
        }
    }

}


