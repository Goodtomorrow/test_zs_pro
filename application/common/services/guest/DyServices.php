<?php
namespace app\common\services\guest;
use GuzzleHttp\Client;
use think\Exception;

/**
 * Class DyServices
 * @package app\common\services\guest
 */
class DyServices
{
    /**
     * 定义接口的Code
     */
    const statusCode = 200;

    /**
     * 定义接口返回的成功Code
     */
    const successCode = 1;

    /**
     * 定义接口返回的错误Code
     */
    const errorCode = 0;

    /**
     * 获取授权Code
     */
    public static function getCode()
    {
        //  GET参数
        $params = http_build_query([
            'client_key'=>config('guests.douyin')['client_key'],
            'response_type'=>'code',
            'scope'=>'user_info',
            'redirect_uri'=>config('guests.douyin')['redirect_uri'],
            'state'=>'STATE',
        ]);
        //  获取code的Api地址
        $url = config('guests.douyin')['code_url']."/?".$params;
        //  实例化Client参数
        $clientParams = ['base_uri'=>config('guests.douyin')['base_uri']];
        //  开始发送请求
        return self::commonRequest($url, 'GET', $clientParams);
    }

    /**
     * 获取授权AccessToken
     */
    public static function getAccessToken()
    {
        //  GET参数
        $params = http_build_query([
            'open_id'=>'ba253642-0590-40bc-9bdf-9a1334b94059',
            'cursor'=>0,
            'count'=>10,
            'keyword'=>'多少钱,金额',
        ]);
        //  查询关键字视频Api接口地址
        $url = config('guests.douyin')['access-token-url']."/?".$params;
        //  实例化Client参数
        $clientParams = ['base_uri'=>config('guests.douyin')['base_uri']];
        //  开始发送请求
        return self::commonRequest($url, 'GET', $clientParams);
    }

    /**
     * 获取刷新后的AccessToken
     */
    public static function getRefreshAccessToken()
    {
        //  GET参数
        $params = http_build_query([
            'open_id'=>'ba253642-0590-40bc-9bdf-9a1334b94059',
            'cursor'=>0,
            'count'=>10,
            'keyword'=>'多少钱,金额',
        ]);
        //  查询关键字视频Api接口地址
        $url = config('guests.douyin')['refresh-access-token-url']."/?".$params;
        //  实例化Client参数
        $clientParams = ['base_uri'=>config('guests.douyin')['base_uri']];
        //  开始发送请求
        return self::commonRequest($url, 'GET', $clientParams);
    }

    /**
     * 关键字搜索视频
     */
    public static function keywordsSearchVideo($access_token, $keywords)
    {
        //  GET参数
        $params = http_build_query([
            'open_id'=>'ba253642-0590-40bc-9bdf-9a1334b94059',
            'cursor'=>0,
            'count'=>10,
            'keyword'=>$keywords,
        ]);
        //  查询关键字视频Api接口地址
        $url = config('guests.douyin')['keywords_video_search_url']."/?".$params;
        //  实例化Client参数
        $clientParams = ['base_uri'=>config('guests.douyin')['base_uri']];
        //  开始发送请求
        return self::commonRequest($url, 'GET', $clientParams);
    }

    /**
     * 公共发起请求接口
     * @param $url          接口地址
     * @param $method       请求类型
     * @param $clientParams 实例化参数
     * @param $params       发起请求参数
     * @return array        JSON
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function commonRequest($url, $method, $clientParams, $params='')
    {
        //  开始实例化类
        $client = new Client([
            'base_uri' => $clientParams['base_uri'],
            'timeout' => isset($clientParams['timeout']) ? $clientParams['timeout'] : 5.0,
            'headers' => [
                'Accept' => '*/*',
                'Connection' => 'keep-alive',
                'Host' => 'https://'.$clientParams['base_uri']
            ]
        ]);
        //  开始发送请求
        $response = $client->request($method, $url);
        //  判断请求是否成功
        if ($response->getStatusCode() != self::statusCode) return ['code'=>self::errorCode,'msg'=>'failed','data'=>$response];
        //  将响应结果输出
        $result = json_decode($response->getBody(), true);
        return ['code'=>self::successCode,'msg'=>'success','data'=>$result];
    }
}
