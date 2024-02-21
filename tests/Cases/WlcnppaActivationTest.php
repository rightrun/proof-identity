<?php
declare(strict_types=1);
/**
 * This file is part of a07sky/proof-identify
 *
 * @link     https://github.com/a07sky/proof-identify
 * @contact  a07sky@126.com
 * @license  https://github.com/hyperf-ext/jwt/blob/master/LICENSE
 */

namespace ProofTest\Cases;

use GuzzleHttp\Client;
use Onetrue\ProofIdentity\ProofIdentifyManager;
use Onetrue\ProofIdentity\Wlcnppa\WlcProofConfig;
use Onetrue\ProofIdentity\Wlcnppa\WlcProofIdentify;
use PHPUnit\Framework\TestCase;

/**
 * 国家游戏防沉迷账号激活单元测试
 * 1. 类名以Test后缀
 * 2. 继承 TestCase类
 */
class WlcnppaActivationTest extends TestCase
{

    //测试之前需要在防迷后台新增IP白名单
    //应用标识（APPID）
    protected $appId = '678c9166a4da47fd9ccc3a015e02ee51';
    //游戏备案识别码（bizId）
    protected $bizId = '1101999999';
    //用户密钥（Secret Key）：
    protected $secretKey = 'c66942f679e9cffaafe3231a475b1392';

    //防迷后台启用并且复制测试码
    //测试码列表
    protected $checkcodeList = [
        'testCase01' => 'BYkBCW',
        'testCase02' => 'NxVbgP',
        'testCase03' => 'YyV6dX',
        'testCase04' => 'rn3hRP',
        'testCase05' => 'X6N3aD',
        'testCase06' => 'kJtCaS',
        'testCase07' => 'h7FPtG',
        'testCase08' => 'WQ2p6n',
    ];

    public function testCase01(): void
    {
        $methodArr = explode("::", __METHOD__);
        $method = $methodArr[1] ?? 'testCase01';
        $checkcode = $this->checkcodeList[$method];

        $presetList = [
            //认证成功
            ['ai' => '100000000000000001', 'name' => '某一一', 'idNum' => '110000190101010001'],
            ['ai' => '100000000000000002', 'name' => '某一二', 'idNum' => '110000190101020007'],
            ['ai' => '100000000000000008', 'name' => '某一八', 'idNum' => '110000190101040016'],
        ];
        $url = 'https://wlc.nppa.gov.cn/test/authentication/check/' . $checkcode;
        $data = $presetList[mt_rand(0, count($presetList) - 1)];

        try {
            $response = $this->request($url, $data);
            $this->assertSame(0, $response['errcode'], "{$method} done");
        } catch (\Exception $exception) {
            $this->fail("{$method} Fail: " . $exception->getMessage() . '（' . $exception->getCode() . '）');
        }
    }

    public function testCase02(): void
    {
        $methodArr = explode("::", __METHOD__);
        $method = $methodArr[1] ?? 'testCase02';
        $checkcode = $this->checkcodeList[$method];

        $presetlist = [
            //认证中
            ['ai' => '200000000000000001', 'name' => '某二一', 'idNum' => '110000190201010009'],
            ['ai' => '200000000000000002', 'name' => '某二二', 'idNum' => '110000190201020004'],
            ['ai' => '200000000000000008', 'name' => '某二八', 'idNum' => '110000190201040013'],
        ];
        $url = 'https://wlc.nppa.gov.cn/test/authentication/check/' . $checkcode;
        $data = $presetlist[mt_rand(0, count($presetlist) - 1)];

        try {
            $response = $this->request($url, $data);
            $this->assertSame(0, $response['errcode'], "{$method} done");
        } catch (\Exception $exception) {
            $this->fail("{$method} Fail: " . $exception->getMessage() . '（' . $exception->getCode() . '）');
        }
    }


    public function testCase03(): void
    {
        $methodArr = explode("::", __METHOD__);
        $method = $methodArr[1] ?? 'testCase03';
        $checkcode = $this->checkcodeList[$method];

        $presetlist = [
            //随机
            ['ai' => '200000000000000001', 'name' => '某二一', 'idNum' => '110000190201020004'],
            ['ai' => '200000000000000008', 'name' => '某二七', 'idNum' => '110000190201040005'],
            ['ai' => '200000000000000004', 'name' => '某二二', 'idNum' => '110000190201040005'],
        ];
        $url = 'https://wlc.nppa.gov.cn/test/authentication/check/' . $checkcode;
        $data = $presetlist[mt_rand(0, count($presetlist) - 1)];

        try {
            $response = $this->request($url, $data);
            $this->assertSame(0, $response['errcode'], "{$method} done");
        } catch (\Exception $exception) {
            $this->fail("{$method} Fail: " . $exception->getMessage() . '（' . $exception->getCode() . '）');
        }
    }


    public function testCase04(): void
    {
        $methodArr = explode("::", __METHOD__);
        $method = $methodArr[1] ?? 'testCase04';
        $checkcode = $this->checkcodeList[$method];

        $presetlist = [
            ['ai' => '100000000000000001'],
            ['ai' => '100000000000000002'],
        ];
        $url = 'https://wlc.nppa.gov.cn/test/authentication/query/' . $checkcode;
        $data = $presetlist[mt_rand(0, count($presetlist) - 1)];

        try {
            $response = $this->request($url, $data, 'get');
            $this->assertSame(0, $response['errcode'], "{$method} done");
        } catch (\Exception $exception) {
            $this->fail("{$method} Fail: " . $exception->getMessage() . '（' . $exception->getCode() . '）');
        }
    }

    public function testCase05(): void
    {
        $methodArr = explode("::", __METHOD__);
        $method = $methodArr[1] ?? 'testCase05';
        $checkcode = $this->checkcodeList[$method];

        $presetlist = [
            ['ai' => '200000000000000001'],
            ['ai' => '200000000000000002'],
        ];
        $url = 'https://wlc.nppa.gov.cn/test/authentication/query/' . $checkcode;
        $data = $presetlist[mt_rand(0, count($presetlist) - 1)];

        try {
            $response = $this->request($url, $data, 'get');
            $this->assertSame(0, $response['errcode'], "{$method} done");
        } catch (\Exception $exception) {
            $this->fail("{$method} Fail: " . $exception->getMessage() . '（' . $exception->getCode() . '）');
        }
    }

    public function testCase06(): void
    {
        $methodArr = explode("::", __METHOD__);
        $method = $methodArr[1] ?? 'testCase06';
        $checkcode = $this->checkcodeList[$method];

        $presetlist = [
            ['ai' => '300000000000000001'],
            ['ai' => '300000000000000002'],
        ];
        $url = 'https://wlc.nppa.gov.cn/test/authentication/query/' . $checkcode;
        $data = $presetlist[mt_rand(0, count($presetlist) - 1)];

        try {
            $response = $this->request($url, $data, 'get');
            $this->assertSame(0, $response['errcode'], "{$method} done");
        } catch (\Exception $exception) {
            $this->fail("{$method} Fail: " . $exception->getMessage() . '（' . $exception->getCode() . '）');
        }
    }

    public function testCase07(): void
    {
        $methodArr = explode("::", __METHOD__);
        $method = $methodArr[1] ?? 'testCase07';
        $checkcode = $this->checkcodeList[$method];

        $url = 'https://wlc.nppa.gov.cn/test/collection/loginout/' . $checkcode;
        $data = [
            'collections' => [
                [
                    'no' => 1,
                    'si' => '95edkzei5exh47pk0z2twm6zpielesrd',//一个会话标识只能对应唯一的实名用户，一个实名用户可以拥有多个会话标识；同一用户单次游戏会话中，上下线动作必须使用同一会话标识上报备注：会话标识仅标识一次用户会话，生命周期仅为一次上线和与之匹配的一次下线，不会对生命周期之外的任何业务有任何影响
                    'bt' => 0,
                    'ot' => time(),
                    'ct' => 2,
                    'di' => 'ecvndx6r6xfwofmufs3lbimcr639r33t',
                ]
            ],
        ];

        try {
            $response = $this->request($url, $data);
            $this->assertSame(0, $response['errcode'], "{$method} done");
        } catch (\Exception $exception) {
            $this->fail("{$method} Fail: " . $exception->getMessage() . '（' . $exception->getCode() . '）');
        }
    }

    public function testCase08(): void
    {
        $methodArr = explode("::", __METHOD__);
        $method = $methodArr[1] ?? 'testCase08';
        $checkcode = $this->checkcodeList[$method];

        $presetlist = [
            ['pi' => '1fffbjzos82bs9cnyj1dna7d6d29zg4esnh99u'],
            ['pi' => '1fffbkmd9ebtwi7u7f4oswm9li6twjydqs7qjv'],
        ];
        $preset = $presetlist[mt_rand(0, count($presetlist) - 1)];
        $pi = $preset['pi'];
        $url = 'https://wlc.nppa.gov.cn/test/collection/loginout/' . $checkcode;
        $data = [
            'collections' => [
                [
                    'no' => 1,
                    'si' => '95edkzei5exh47pk0z2twm6zpielesrd',//一个会话标识只能对应唯一的实名用户，一个实名用户可以拥有多个会话标识；同一用户单次游戏会话中，上下线动作必须使用同一会话标识上报备注：会话标识仅标识一次用户会话，生命周期仅为一次上线和与之匹配的一次下线，不会对生命周期之外的任何业务有任何影响
                    'bt' => 0,
                    'ot' => time(),
                    'ct' => 0,
                    'di' => 'ecvndx6r6xfwofmufs3lbimcr639r33t',
                    'pi' => $pi,//已通过实名认证用户的唯一标识，已认证通过用户必填
                ]
            ],
        ];

        try {
            $response = $this->request($url, $data);
            $this->assertSame(0, $response['errcode'], "{$method} done");
        } catch (\Exception $exception) {
            $this->fail("{$method} Fail: " . $exception->getMessage() . '（' . $exception->getCode() . '）');
        }
    }

    protected function request($url, $data, $method = 'post')
    {
        $providerConfig = new WlcProofConfig($this->appId, $this->secretKey, $this->bizId);
        $manager = new ProofIdentifyManager(WlcProofIdentify::class, $providerConfig);

        $provider = $manager->getProvider();
        try {
            $client = new Client([
                'timeout' => 3
            ]);
            if (strtolower($method) == 'post') {
                $requestBody = $provider->createBody($data);
                $sign = $provider->createSign($requestBody);
                $headers = $provider->getHeaders($sign);
                $response = $client->post($url, ['body' => $requestBody, 'headers' => $headers]);
            } else if (strtolower($method) == 'get') {
                $sign = $provider->createSign($data);
                $headers = $provider->getHeaders($sign);
                $url = $url . (strpos($url, '?') === false ? '?' : ':') . http_build_query($data);
                $response = $client->get($url, ['headers' => $headers]);
            } else {
                throw new \Exception('unknow request method!');
            }

            $statusCode = $response->getStatusCode();
            $respBody = $response->getBody()->getContents();
            $respBody = json_decode($respBody, true);
            if ($respBody['errcode'] != 0) {
                throw new \Exception($respBody['errmsg'], $respBody['errcode']);
            }
            return $respBody;
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage(), $exception->getCode());
        }
    }
}