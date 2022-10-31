<?php

namespace app\api\controller;

use app\common\controller\Api;
use app\common\services\guest\DyServices;
use think\Request;

/**
 * 测试接口
 */
class Fake extends Api
{
    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];

    /**
     * 测试接口【授权流程】
     * 1：抖音静默获取授权码：https://developer.open-douyin.com/docs/resource/zh-CN/dop/develop/openapi/account-permission/douyin-get-permission-code
     * 2：获取的code可以用来调用https://open.douyin.com/oauth/access_token/ 换取用户open_id：https://developer.open-douyin.com/docs/resource/zh-CN/dop/develop/openapi/account-permission/get-access-token
     */
    public function test(Request $request)
    {
        //  获取参数
        if (!$id = $request->post('id', [],'trim')) $this->error('参数必传！');
        //  1：获取授权
        $getAccessToken = DyServices::getCode();
        halt($getAccessToken);
        //  2：判断授权是否成功
        switch ($getAccessToken['code']){
            case "0":       //  授权失败
                $this->error('授权失败，请重试！');
            break;
            case "1":       //  授权成功    开始关键字搜索
                if (!$keyswords = $request->post('keyswords', '')) $this->error('关键字必传！');
                $result = DyServices::keywordsSearchVideo($getAccessToken['access_token'], $keyswords);
                switch ($result['code']){
                    case "0":       //  关键字搜索视频接口发起失败
                        $this->error($result['msg']);
                    break;
                    case "1":       //  关键字搜索视频接口发起成功
                        if (isset($result['data']['extra']['error_code']) && $result['data']['extra']['error_code'] != 0) $this->error($result['data']['extra']['description']);
                        $this->success($result['data']);
                    break;
                }
            break;
        }
    }
}
