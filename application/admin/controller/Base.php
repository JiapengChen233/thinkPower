<?php


namespace app\admin\controller;

use app\admin\model\RolePower;
use think\Controller;
use think\facade\Request;
use think\response\Json;
use think\facade\Session;

/**
 * 全局控制器
 * @package app\admin\controller
 * @author RonaldoC
 * @version 1.0.0
 * @date 2019-8-3 21:21:40
 */
class Base extends Controller
{
    protected $super_admin_id = 1; // 超级管理员ID

    private $app_menu_list = [];  // 当前登录用户菜单列表


    private $APP_NO_LOGIN = [ // 免登陆可访问路由
        'admin.login.login',
        'admin.login.logout',
    ];

    private $APP_NO_POWER = [ // 无需权限可访问路由
        'admin.login.login',
        'admin.login.logout',
        'admin.index.index',
        'admin.index.welcome',
    ];

    /**
     * 路由拦截
     */
    protected function initialize()
    {
        // 登陆校验
        if (!$this->checkLogin()) {
            // ajax请求
            if (Request::isAjax()) {
                return $this->returnJson(400, '登录失效！');
            } else {
                $this->Redirect('admin/login/login');
            }
        }

        // 权限校验
        if (!$this->checkPower()) {
            // ajax请求
            if (Request::isAjax()) {
                return $this->returnJson(400, '您无权限进行此操作！');
            } else {
                return $this->error('您无权限进行此操作！', '', '');
            }
        }

    }

    /**
     * 校验登录
     */
    private function checkLogin()
    {
        $module = strtolower(Request::module());
        $controller = strtolower(Request::controller());
        $action = strtolower(Request::action());
        $route = $module . '.' . $controller . '.' . $action;
        if (in_array($route, $this->APP_NO_LOGIN)) {
            return true;
        }

        if (!Session::has('user')) {
            return false;
        }
        $user = Session::get('user');
        $mUser = new \app\admin\model\User();
        $user = $mUser->getById($user['id']);
        if (!$user) {
            return false;
        }
        if ($user['locked'] == 1) {
            Session::clear();
            return false;
        }
        $this->assign('nickname', $user['nickname']);
        $this->assign('profile', config('file_access_url') . $user['profile']);

        // 更新Session
        Session::set('user', $user);

        return true;
    }

    /**
     * 权限校验
     */
    private function checkPower()
    {
        $module = strtolower(Request::module());
        $controller = strtolower(Request::controller());
        $action = strtolower(Request::action());
        $route = $module . '.' . $controller . '.' . $action;
        if (in_array($route, $this->APP_NO_POWER)) {
            return true;
        }

        if (!Session::has('user')) {
            return false;
        }
        $user = Session::get('user');

        // 超级管理员不校验权限
        if ($user['id'] == $this->super_admin_id) {
            return true;
        }

        $mUser = new \app\admin\model\User();
        $user = $mUser->getById($user['id']);
        if (!$user) {
            return false;
        }

        $role_id = $user['role_id'];
        if (!$role_id) {
            return false;
        }

        $mRole = new \app\admin\model\Role();
        $role = $mRole->getById($role_id);
        if (!$role) {
            return false;
        }

        if ($role['enabled'] == 0) {
            return false;
        }

        $mRolePower = new RolePower();
        $rolePower = $mRolePower->listByCondition(['rp.role_id' => $role_id]);
        if (count($rolePower) == 0) {
            return false;
        }

        $power_ids = implode(',', array_column($rolePower->toArray(), 'power_id'));
        $mPower = new \app\admin\model\Power();
        $power_list = $mPower->listByCondition([['id', 'in', $power_ids], ['enabled', '=', '1']]);
        if (count($power_list) == 0) {
            return false;
        }

        $power_arr = $power_list->toArray();
        $power = array_filter($power_arr, function ($v) use ($module, $controller, $action) {
            $m = strtolower($v['module']);
            $c = strtolower($v['controller']);
            $a = strtolower($v['action']);
            if ($m == $module && $c == $controller && $a == $action) {
                return $v;
            }
        });
        if (!$power) {
            return false;
        }

        return true;
    }

    /**
     * 获取当前登录用户菜单列表
     */
    protected function getMenu()
    {
        if (Session::has('user')) {
            $user = Session::get('user');
            if ($user['id'] == $this->super_admin_id) {
                $mPower = new \app\admin\model\Power();
                $power_list = $mPower->listByCondition([['p.enabled', '=', 1], ['p.type', '<', 3]]);
                $menu_list = $power_list;
            } else {
                $mUser = new \app\admin\model\User();
                $user = $mUser->getById($user['id']);
                if (!$user) {
                    return;
                }

                $role_id = $user['role_id'];
                if (!$role_id) {
                    return;
                }

                $mRole = new \app\admin\model\Role();
                $role = $mRole->getById($role_id);
                if (!$role) {
                    return;
                }

                if ($role['enabled'] == 0) {
                    return;
                }

                $mRolePower = new RolePower();
                $rolePower = $mRolePower->listByCondition(['rp.role_id' => $role_id]);
                if (count($rolePower) == 0) {
                    return;
                }

                $power_ids = implode(',', array_column($rolePower->toArray(), 'power_id'));
                $mPower = new \app\admin\model\Power();
                $power_list = $mPower->listByCondition([['id', 'in', $power_ids], ['enabled', '=', '1']]);
                if (count($power_list) == 0) {
                    return;
                }

                $menu_list = $power_list->toArray();
                $menu_list = array_filter($menu_list, function ($v) {
                    if ($v['type'] < 3) {
                        return $v;
                    }
                });
            }
            if (count($menu_list) > 0) {
                foreach ($menu_list as &$v) {
                    $v['url'] = url($v['module'] . '/' . $v['controller'] . '/' . $v['action']);
                }
            }
            $this->assign('menu', $menu_list);
        }
    }


    /**
     * 返回JSON数据
     * @param int $state 状态码：1代表成功，0代表失败
     * @param string $message 结果信息
     * @param array $data 返回的数据
     * @return Json                JSON对象
     * @author RonaldoC
     * @version 1.0.0
     * @date 2019-8-4 10:41:49
     */
    protected function returnJson($state = 1, $message = "", $data = [])
    {
        $arr = [
            'state' => $state,
            'message' => $message,
            'data' => $data,
        ];
        return json($arr);
    }

}


