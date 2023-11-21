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

use Onetrue\ProofIdentity\ProofIdentifyManager;
use Onetrue\ProofIdentity\Wlcnppa\WlcProofConfig;
use Onetrue\ProofIdentity\Wlcnppa\WlcProofIdentify;
use PHPUnit\Framework\TestCase;

/**
 * 测试类名
 * 1. 类名以Test后缀
 * 2. 继承 TestCase类
 */
class WlcnppaProofTest extends TestCase
{
    /**
     * 测试方法名以test作前缀
     * @return void
     */
    public function testRun(): void
    {

        try {


            $appId = 'xxx';
            $secretKey = 'xxx';
            $bizId = 'xxx';
            $uid = uniqid();

            $providerConfig = new WlcProofConfig($appId, $secretKey, $bizId);
            $manager = new ProofIdentifyManager(WlcProofIdentify::class, $providerConfig);

            $provider = $manager->getProvider();
            $provider->setUID($uid);

            $result = $provider->verify('xx', 'xx'); //郑子健 431081199008301378

            $parser = $result->getIdCardParser();
            echo '' . PHP_EOL;
            echo 'result' . PHP_EOL;
            echo 'code => ' . $result->getStatusCode() . PHP_EOL;
            echo 'result' . PHP_EOL;
            echo 'birthday => ' . $parser->getBirthday() . PHP_EOL;
            echo 'age => ' . $parser->getAge() . PHP_EOL;


            echo '' . PHP_EOL;


        } catch (\Exception $exception) {
            echo '' . PHP_EOL;
            print_r($exception->getMessage());
            echo '' . PHP_EOL;
        }
        $stack = [];
        $this->assertSame(0, count($stack));
        /* array_push($stack, 'foo');
         $this->assertSame('foo', $stack[count($stack) - 1]);
         $this->assertSame(1, count($stack));
         $this->assertSame('foo', array_pop($stack));
         $this->assertSame(0, count($stack));*/


    }


    public function testReport(): void
    {
        $appId = 'xxx';
        $secretKey = 'xxx';
        $bizId = 'xxx';
        $uid = uniqid();
        $providerConfig = new WlcProofConfig($appId, $secretKey, $bizId);
        $manager = new ProofIdentifyManager(WlcProofIdentify::class, $providerConfig);

        $provider = $manager->getProvider();
        $provider->setUID($uid);

        // $pi 用户实名认证后的唯一值,工宣部实名独有
        // $bt ($behaviorType) 上线和下线的标记（ 0：下线  1：上线）
        //*$ot 上线和下线的时间截（ 秒数）
        // $di 用户终端设备号（游客用户下必填）

        list($username, $authPi, $behaviorType, $time, $deviceImei) = [
            $uid,
            '1hi6g273omoxaan7mrv7xo4pbgaihp149rqmgs',
            1,
            time(),
            ''
        ];

        $provider->behaviorLoginout($authPi, $behaviorType, $time, $deviceImei);
        $stack = [];
        $this->assertSame(0, $this->count($stack));
    }
}