<?php

namespace app\api\controller\v1;

use app\common\controller\Api;
use GuzzleHttp\Client;
use think\Lang;

/**
 * 首页接口 Fake
 */
class Fake extends Api
{
    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];

    /**
     * 测试接口
     *
     */
    public function test()
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
