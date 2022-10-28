<?php
namespace app\common\services\guest;
use app\admin\library\Auth;
use GuzzleHttp\Client;
use think\Db;
use think\Env;
use think\Exception;
use think\Lang;

/**
 * Class DyServices
 * @package app\common\services\guest
 */
class DyServices
{
    /**
     * 获取授权AccessToken
     */
    public static function getAccessToken()
    {
        //  加载语言包
        Lang::load(APP_PATH . config('lang.path'));
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
            'Host' => 'https://'.config('guests.douyin')['base_uri']
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
        if ($response->getStatusCode() !== 200) return ['code'=>0,'msg'=>'failed','data'=>$response];
        //  将响应结果输出
        $result = json_decode($response->getBody(), true);
        return ['code'=>1,'msg'=>'success','data'=>$result];
    }

    /**
     * 关键字搜索视频
     */
    public static function keywordsSearchVideo($access_token, $keywords)
    {
        //  加载语言包
        Lang::load(APP_PATH . config('lang.path'));
        //  GET参数
        $params = http_build_query([
            'open_id'=>'ba253642-0590-40bc-9bdf-9a1334b94059',
            'cursor'=>0,
            'count'=>10,
            'keyword'=>$keywords,
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
        if ($response->getStatusCode() !== 200) return ['code'=>0,'msg'=>'failed','data'=>$response];
        //  将响应结果输出
        $result = json_decode($response->getBody(), true);
        return ['code'=>1,'msg'=>'success','data'=>$result];
    }
}

