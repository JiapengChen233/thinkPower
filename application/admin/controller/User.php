<?php


namespace app\admin\controller;

use Exception;
use think\facade\Request;
use think\facade\Validate;
use think\response\Json;
use think\response\View;

/**
 * 用户控制器
 * @package app\admin\controller
 * @author RonaldoC
 * @version 1.0.0
 * @date 2019-8-3 21:21:57
 */
class User extends Base
{
    /**
     * 用户列表查询
     * @throws Exception
     */
    public function index()
    {
        // 请求参数
        $params = $this->request->param();

        // 查询数据
        $user = new \app\admin\model\User();
        $list = $user->listByPage($params);
        $page = $list->render();

        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('params', $params);
        return view();
    }

    /**
     * 用户新增
     * @return Json|View          [GET请求返回页面，POST请求返回JSON]
     * @throws Exception
     */
    public function add()
    {
        if (Request::isAjax()) {
            if (Request::isGet()) {
                return $this->returnJson(-1, '非法请求！');
            }

            $params = input('post.');

            // 参数校验
            $validate = new \app\admin\validate\User();
            $result = $validate->check($params);
            if (!$result) {
                return $this->returnJson(-1, $validate->getError());
            }

            // 数据校验
            $mUser = new \app\admin\model\User();
            $user_list = $mUser->listByCondition([]);
            if (count($user_list) > 0) {
                foreach ($user_list as $v) {
                    if ($v['account'] == $params['account']) {
                        return $this->returnJson(-1, '登录名已存在！');
                    }
                }
            }

            // 密码校验
            $rule = [
                'password' => ['require', 'regex' => '^(?=.*[A-Za-z])(?=.*\d)(?=.*[_~!@#$%^&*]).{6,16}$'],
                'repass' => ['require', 'confirm' => 'password', 'regex' => '^(?=.*[A-Za-z])(?=.*\d)(?=.*[_~!@#$%^&*]).{6,16}$'],
            ];
            $message = [
                'password.require' => '密码必须！',
                'password.regex' => '请输入6到16个字符，要求数字、字母和特殊符号！',
                'repass.require' => '确认密码必须！',
                'repass.confirm' => '两次输入的密码不一致！',
                'repass.regex' => '请输入6到16个字符，要求数字、字母和特殊符号！',
            ];
            $validate = Validate::make($rule, $message);
            $result = $validate->check($params);
            if (!$result) {
                return $this->returnJson(-1, $validate->getError());
            }

            // 加密密码
            // 生成加密用盐
            $salt = gen_random_str();
            $params['salt'] = $salt;
            // md5加密
            $params['password'] = md5($params['password'] . $salt);

            // 启动事物
            $Db = $mUser->db(false);
            $Db->startTrans();
            try {
                $res_flag = $mUser->add($params);
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
            return view();
        }
    }

    /**
     * 用户停用
     * @return Json|void          [GET请求返回页面，POST请求返回JSON]
     * @throws Exception
     */
    public function userStop() {
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
            $mUser = new \app\admin\model\User();
            $user = $mUser->getById($params['id']);
            if (!$user) {
                return $this->returnJson(-1, '请求参数错误！');
            }

            // 启用
            if ($user['locked'] === 1) {
                $user['locked'] = 0;

            } else { // 停用
                $user['locked'] = 1;
            }

            // 启动事物
            $Db = $mUser->db(false);
            $Db->startTrans();
            try {
                $res_flag = $mUser->edit($user);
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
}


