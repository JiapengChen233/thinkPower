<?php


namespace app\admin\controller;

use think\Controller;

/**
 * 全局控制器
 * @package app\admin\controller
 * @author RonaldoC
 * @version 1.0.0
 * @date 2019-8-3 21:21:40
 */
class Base extends Controller
{
    /**
     * 返回JSON数据
     * @param int $state          状态码：1代表成功，0代表失败
     * @param string $message     结果信息
     * @param array $data         返回的数据
     * @return string             JSON对象
     * @author RonaldoC
     * @version 1.0.0
     * @date 2019-8-4 10:41:49
     */
    public function returnJson($state = 1, $message = "", $data = []) {
        $arr = [
            'state' => $state,
            'message' => $message,
            'data' => $data,
        ];
        return json($arr);
    }

}

?>
