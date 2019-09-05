<?php


namespace app\admin\controller;

use think\facade\Session;

/**
 * 主页控制器
 * @package app\admin\controller
 * @author RonaldoC
 * @version 1.0.0
 * @date 2019-8-3 21:39:09
 */
class Index extends Base
{
    /**
     * 主页查询
     * @author RonaldoC
     * @version 1.0.0
     * @date 2019-8-3 22:17:30
     */
    public function index()
    {
        $this->getMenu();
        return view();
    }

    /**
     * 欢迎页查询
     * @author RonaldoC
     * @version 1.0.0
     * @date 2019-8-3 22:18:03
     */
    public function welcome()
    {
        return view();
    }
}


