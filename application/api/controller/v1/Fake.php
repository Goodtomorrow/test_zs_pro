<?php

namespace app\api\controller\v1;

use app\common\controller\Api;
use app\common\services\guest\DyServices;
use GuzzleHttp\Client;
use think\Lang;
use think\Request;

/**
 * 首页接口 Fake
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
        $id = $request->post('id', '');
        if (!$id) $this->error('参数必传！');
        $keyswords = "";
        //  1：获取授权
        $getAccessToken = DyServices::getAccessToken();
        //  2：判断授权是否成功
        switch ($getAccessToken['code']){
            case "0":       //  授权失败
                $this->error('授权失败，请重试！');
            break;
            case "1":       //  授权成功    开始关键字搜索
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

    public function test2()
    {
        //  加载语言包
        Lang::load(APP_PATH . config('lang.path'));
        //  获取access_token
        $access_token = "1sdad";
        //  GET参数
        $params = http_build_query([
            'open_id'=>'ba253642-0590-40bc-9bdf-9a1334b94059',
            'cursor'=>0,
            'count'=>10,
            'keyword'=>'多少钱,金额',
        ]);
        //  查询关键字视频Api接口地址
        $url = config('guests.douyin')['keywords-video-search-url']."/?".$params;
        //  请求header头
        $header = [
            'Accept' => '*/*',
            'Connection' => 'keep-alive',
            'Host' => 'https://'.config('guests.douyin')['base_uri'],
            'access-token' => $access_token,
        ];
        //  开始实例化类
        $client = new Client([
            'base_uri' => config('guests.douyin')['base_uri'],
            'timeout' => 5.0,
            'headers' => $header
        ]);
        //  开始发送请求
        $response = $client->request('GET', $url);
        //  判断请求是否成功
        if ($response->getStatusCode() !== 200) $this->error(__('request error'));
        //  将响应结果输出
        $result = json_decode($response->getBody(), true);
        $this->success($result);
    }
}
