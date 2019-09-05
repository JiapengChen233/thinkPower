<?php


namespace app\admin\controller;

use Exception;
use think\facade\Request;
use think\facade\Session;
use think\response\Json;
use think\response\Redirect;
use think\response\View;

/**
 * 登录控制器
 * @package app\admin\controller
 * @author RonaldoC
 * @version 1.0.0
 * @date 2019-9-3 15:42:25
 */
class Login extends Base
{

    /**
     * 用户登录
     * @return Json|View          [GET请求返回页面，POST请求返回JSON]
     * @throws Exception
     * @author RonaldoC
     * @version 1.0.0
     * @date 2019-9-3 15:49:37
     */
    public function login()
    {
        if (Request::isAjax()) {
            if (Request::isGet()) {
                return $this->returnJson(-1, '非法请求！');
            }

            $params = input('post.');

            $mUser = new \app\admin\model\User();
            $user = $mUser->getByAccount($params['account']);

            if (!$user) {
                return $this->returnJson(-1, '用户不存在！');
            }

            $login_password = md5($params['password'] . $user['salt']);
            if ($login_password != $user['password']) {
                return $this->returnJson(-1, '用户名或密码错误！');
            }

            if ($user['locked'] == 1) {
                return $this->returnJson(-1, '用户已锁定！');
            }

            // 更新用户登录时间和登录IP
            $user['last_login_time'] = date('y-m-d H:i:s');
            $user['last_login_ip'] = Request::ip();

            // 启动事物
            $Db = $mUser->db(false);
            $Db->startTrans();
            try {
                $res_flag = $mUser->edit($user);
                if (!$res_flag) {
                    // 回滚事物
                    $Db->rollback();
                    return $this->returnJson(-1, "登录失败！");
                }

                // 提交事物
                $Db->commit();
            } catch (Exception $e) {
                // 回滚事物
                $Db->rollback();
                return $this->returnJson(-1, "登录失败！");
            }

            // 登录成功，设置用户Session
            session('user', $user);

            return $this->returnJson(1, '登录成功！');
        } else {
            if (Request::isPost()) {
                return $this->returnJson(-1, '非法请求！');
            }
            return view();
        }
    }

    /**
     * 用户登出
     * @return Redirect
     * @throws Exception
     * @author RonaldoC
     * @version 1.0.0
     * @date 2019-9-3 19:03:58
     */
    public function logout()
    {
        Session::clear();
        return redirect('admin/login/login');
    }
}


