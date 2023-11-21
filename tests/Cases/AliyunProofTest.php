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


use Onetrue\ProofIdentity\Aliyun\AliyunProofConfig;
use Onetrue\ProofIdentity\Aliyun\AliyunProofIdentify;
use Onetrue\ProofIdentity\ProofIdentifyManager;
use PHPUnit\Framework\TestCase;

/**
 * 测试类名
 * 1. 类名以Test后缀
 * 2. 继承 TestCase类
 */
class AliyunProofTest extends TestCase
{


    /**
     * 测试方法名以test作前缀
     * @return void
     */
    public function testRun(): void
    {

        $verifyKey = " ";
        $appCode = "";

        try {
            $providerConfig = new AliyunProofConfig($verifyKey, $appCode);
            $manager = new ProofIdentifyManager(AliyunProofIdentify::class, $providerConfig);

            $provider = $manager->getProvider();
            $result = $provider->verify('张三', '513030195810280076');

            print_r($result);
        } catch (\Exception $exception) {
            echo PHP_EOL;
            print_r($exception->getMessage());
            echo PHP_EOL;
            print_r($exception->getTraceAsString());
            echo PHP_EOL;
        }

        $stack = [];
        $this->assertSame(0, count($stack));
        array_push($stack, 'foo');
        $this->assertSame('foo', $stack[count($stack) - 1]);
        $this->assertSame(1, count($stack));
        $this->assertSame('foo', array_pop($stack));
        $this->assertSame(0, count($stack));
    }

}