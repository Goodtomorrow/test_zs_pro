<?php

namespace app\api\controller\v1;

use app\common\controller\Api;
use GuzzleHttp\Client;
use think\Exception;

/**
 * 首页接口 Fake
 */
class Fake extends Api
{
    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];

    /**
     * 首页
     *
     */
    public function test()
    {
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
        $url = "https://open.douyin.com/video/search/?".$params;
        //  开始实例化类
        $client = new Client([
            'base_uri' => 'open.douyin1.com',
            'timeout' => 5.0,
            'headers' => [
                'Accept' => '*/*',
                'Connection' => 'keep-alive',
                'Host' => 'https://open.douyin1.com',
                'access-token' => $access_token,
            ]
        ]);
        //  开始发起请求
        try {
            $response = $client->request('GET', $url);
            //  判断是否成功
            if ($response->getStatusCode() !== 200) $this->error();
            //  将响应结果输出
            $result = json_decode($response->getBody(), true);
            if (!(isset($result['data']) && $result['data']['error_code'] === 0 && $result['data']['error_code'] === 0)) $this->error($result['extra']['description'],$result,$result['extra']['error_code']);
        }catch (Exception $e){
            $this->error($e->getMessage());
        }
    }
}
