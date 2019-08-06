<?php


namespace app\admin\controller;

use think\facade\Request;
use think\response\Json;
use think\response\View;

/**
 * 上传控制器
 * @package app\admin\controller
 * @author RonaldoC
 * @version 1.0.0
 * @date 2019-8-4 14:04:44
 */
class Upload extends Base
{
    /**
     * 上传头像
     * @return Json|View          [GET请求返回页面，POST请求返回JSON]
     */
    public function uploadImage()
    {
        if (Request::isAjax()) {
            if (Request::isGet()) {
                return $this->returnJson(-1, '非法请求！');
            }

            // 请求校验
            // 获取表单上传文件
            $file = request()->file('file');
            // 移动到框架应用根目录/uploads/目录下
            $info = $file->validate(['size' => 2 * 1024 * 1024, 'ext' => 'jpeg,jpg,png,gif'])->move('../uploads');
            if (!$info) {
                // 上传失败返回错误信息
                return $this->returnJson(-1, $file->getError());
            } else {
                $access_url = config('app.file_access_url') . $info->getSaveName();
                return $this->returnJson(1, '上传成功！', ['url' => $info->getSaveName(), 'access_url' => $access_url]);
            }
        } else {
            return $this->error("非法请求！");
        }
    }

}

?>
