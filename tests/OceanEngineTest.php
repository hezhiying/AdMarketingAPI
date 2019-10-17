<?php

namespace AdMarketingAPI\Tests;

use AdMarketingAPI\Factory;
use Symfony\Component\Cache\Simple\RedisCache;

class OceanEngineTest extends TestCase
{
    public function testStaticCall()
    {
        $config = [
            'account_id' => xxxxxx,
            'app_id' => xxxxx,
            'secret' => xxxx,
            'oauth' => [
                /*
                 *
                 * 可选，授权的权限范围，不传时代表当前应用拥有的所有权限。
                 * 注意，权限范围只能在自己的应用拥有的权限范围之内。
                 * 格式例如：scope=[1, 2, 3, 41, 421]。
                 * 具体权限范围取值见最下方scope权限范围取值关系，每个权限后面的数字记为该权限的表示数值。
                 */
                'scopes' => [],
                // 回调链接
                'redirect_uri' => 'oceanengine/oauth/callback',
            ],
            'log' => [ // optional
                'file' => './logs/oceanengine.log',
                'level' => 'info', // 建议生产环境等级调整为 info，开发环境为 debug
                'type' => 'single', // optional, 可选 daily.
                'max_file' => 30, // optional, 当 type 为 daily 时有效，默认 30 天
            ],
            'http' => [ // optional
                'timeout' => 5.0,
                'connect_timeout' => 5.0,
                // 更多配置项请参考 [Guzzle](https://guzzle-cn.readthedocs.io/zh_CN/latest/request-options.html)
            ],
            'mode' => 'dev',
        ];
        $app = Factory::oceanEngine($config);

        $redis = new \Redis();
        $redis->connect('redis_node', 6379);
        // 创建缓存实例
        $cache = new RedisCache($redis);
        // 替换应用中的缓存
        $app->rebind('cache', $cache);

        // 获取账户日预算.
        // $advertiser_ids = [505397556292476];
        // $res = $app['account']->budget($advertiser_ids);
        // dump($res);
        

        // 拉取广告组列表
        $advertiser_id = 505397556292476;
        // $res = $app['campaign']->fields([
        //         "id", "name", "budget", "budget_mode", "landing_type","status","modify_time"
        //     ])->filter([
        //         'status' => 'CAMPAIGN_STATUS_ALL',
        //     ])->list($advertiser_id);

        // 广告组数据表
        $res = $app['campaign']->dateRange("2019-10-16", "2019-10-16")->filter([
                'status' => 'CAMPAIGN_STATUS_ALL',
            ])->list($advertiser_id, 1, 100);

        dump($res);
        
        
        die;

        // $this->assertInstanceOf(\EasyAdm\OceanEngine\Application::class, $oceanEngine);
    }
}