<?php

declare(strict_types=1);

/**
 * This file is part of a07sky/proof-identify
 *
 * @link     https://github.com/a07sky/proof-identify
 * @contact  a07sky@126.com
 * @license  https://github.com/proof-identify/blob/master/LICENSE
 */

namespace Onetrue\ProofIdentity\Aliyun;


use Onetrue\ProofIdentity\Contracts\ResultInterface;
use Onetrue\ProofIdentity\Exception\ProofIdentifyException;
use Onetrue\ProofIdentity\ProofIdentifyResult;
use Onetrue\ProofIdentity\Provider\ProofIdentifyProvider;
use Onetrue\ProofIdentity\Utils\IdCardParser;
use GuzzleHttp\Client;


/**
 * https://market.aliyun.com/products/56928004/cmapi00062284.html?spm=5176.730005.result.22.56ca123eheH5pJ&innerSource=search#sku=yuncode5628400005
 */
class AliyunProofIdentify extends ProofIdentifyProvider
{
    /**
     * 获取阿进而配置
     * @return \Onetrue\ProofIdentity\Aliyun\AliyunProofConfig
     */
    public function getConfig(): AliyunProofConfig
    {
        return $this->config;
    }


    public function behaviorLoginout($authPi, $behaviorType, $time, $deviceImei): bool
    {
        // TODO: Implement behaviorLoginout() method.
    }


    public function verify(string $realname, string $idcard): ?ResultInterface
    {
        $this->validIdCard($idcard);
        $config = $this->getConfig();

        $verifyKey = $config->getVerifyKey();
        $appCode = $config->getAppCode();

        $requestUrl = "https://sxidcheck.market.alicloudapi.com/idcard/check";
        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded',
            "Authorization" => "APPCODE " . $appCode,
        ];
        try {
            //http
            $client = new Client([
                'timeout' => 3,
                'headers' => $headers,
                'http_errors' => false,
            ]);
            //发送请求
            $requestData = [
                'idCard' => $idcard,
                'name' => $realname,
            ];
            $response = $client->post($requestUrl . '?' . http_build_query($requestData), []);
            $statusCode = $response->getStatusCode();
            $proofResult = new ProofIdentifyResult();
            $proofResult->setStatusCode($statusCode);
            $proofResult->setErrorMessage('阿里云网络异常！');
            $respBody = $response->getBody()->getContents();
            $respBody = json_decode($respBody, true);
            if ($statusCode == 200) {
                //{"code":"0","msg":"成功","isFee":1,"seqNo":"ktsxu8zpuvoutb3nptg727pkl8q6lna7","data":{"birthday":"19581028","gender":1,"age":64,"province":"四川","result":1}}"
                $proofResult->setStatusCode(intval($respBody['code']));
                $proofResult->setErrorMessage($respBody['msg']);
                //"result": 2 //核查结果（1:一致，2:不一致，3:无记录）
                if (!empty($respBody['data']) && $respBody['data']['result'] == 1) {
                    $proofResult->setStatusCode(0); //成功状态
                    $idCardParser = new IdCardParser($idcard);
                    $idCardParser->setPi($authResult['pi'] ?? '');
                    $proofResult->setIdCardParser($idCardParser);
                } else if (!empty($respBody['data'])) {
                    $proofResult->setStatusCode($respBody['body']['result']);
                }
            } else {
                $proofResult->setStatusCode(intval($respBody['code']));
                $proofResult->setErrorMessage($respBody['msg']);
            }
            return $proofResult;
        } catch (\Throwable $exception) {
            print_r(get_class($exception));
            throw new ProofIdentifyException($exception->getMessage());
        }
    }
}