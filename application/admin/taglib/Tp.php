<?php

namespace app\admin\taglib;

use app\admin\model\Power;
use app\admin\model\Role;
use app\admin\model\RolePower;
use app\admin\model\User;
use think\facade\Session;
use think\template\TagLib;
use think\facade\Request;

/**
 * 自定义扩展标签，实现功能权限
 * @author RonaldoC
 * @version 1.0.0
 * @datetime 2019-9-4 17:37:24
 */
class Tp extends TagLib
{
    protected $tags = [
        'access' => ['attr' => 'module,controller,action', 'close' => 1]
    ];

    public function tagAccess($tag, $content)
    {
        if (Session::has('user')) {
            $module = empty($tag['module']) ? Request::module() : $tag['module'];
            $controller = empty($tag['controller']) ? Request::controller() : $tag['controller'];
            $action = $tag['action'];
            $mPower = new Power();
            $user = Session::get('user');
            if ($user['id'] == 1) {
                $power_list = $mPower->listByCondition([['module', '=', $module], ['controller', '=', $controller], ['action', '=', $action], ['enabled', '=', 1]]);
                if (count($power_list) > 0) {
                    return $content;
                }
                return "<!-- $content -->";
            } else {
                $mUser = new User();
                $user = $mUser->getById($user['id']);
                if (!$user) {
                    return "<!-- $content -->";
                }

                $role_id = $user['role_id'];
                if (!$role_id) {
                    return "<!-- $content -->";
                }

                $mRole = new Role();
                $role = $mRole->getById($role_id);
                if (!$role) {
                    return "<!-- $content -->";
                }

                if ($role['enabled'] == 0) {
                    return "<!-- $content -->";
                }

                $mRolePower = new RolePower();
                $rolePower = $mRolePower->listByCondition(['rp.role_id' => $role_id]);
                if (count($rolePower) == 0) {
                    return "<!-- $content -->";
                }

                $power_ids = implode(',', array_column($rolePower->toArray(), 'power_id'));
                $mPower = new Power();
                $power_list = $mPower->listByCondition([['id', 'in', $power_ids], ['enabled', '=', '1']]);
                if (count($power_list) == 0) {
                    return "<!-- $content -->";
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
                    return "<!-- $content -->";
                }
                return $content;
            }
        }
    }
}