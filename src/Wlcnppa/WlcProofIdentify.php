<?php
declare(strict_types=1);

/**
 * This file is part of hyperf-ext/jwt
 *
 * @link     https://github.com/a07sky/proof-identify
 * @contact  a07sky@126.com
 * @license  https://github.com/proof-identify/blob/master/LICENSE
 */


namespace Onetrue\ProofIdentity\Wlcnppa;

use Onetrue\ProofIdentity\Exception\ProofIdentifyException;
use Onetrue\ProofIdentity\Utils\IdCardParser;
use Onetrue\ProofIdentity\ProofIdentifyResult;
use Onetrue\ProofIdentity\Wlcnppa\WlcProofConfig;
use Onetrue\ProofIdentity\Contracts\ResultInterface;
use Onetrue\ProofIdentity\Provider\ProofIdentifyProvider;
use GuzzleHttp\Client;

class WlcProofIdentify extends ProofIdentifyProvider
{

    /**
     * 唯一值,用于查询实名结果
     * @var string
     */
    protected $uid;

    public function getConfig(): WlcProofConfig
    {
        return $this->config;
    }

    public function verify(string $realname, string $idcard): ?ResultInterface
    {
        // TODO: Implement verify() method.
        $this->validIdCard($idcard);
        $config = $this->getConfig();
        $ai = $this->getAi();
        $paramValue = [
            'ai' => $ai,
            'name' => $realname,
            'idNum' => $idcard
        ];
        return $this->requestVerify($config->getVerifyUrl(), $paramValue, $idcard);
    }


    public function query(): ?ResultInterface
    {
        $result = new ProofIdentifyResult();
        return $result;
    }


    /**
     * 行为上报
     * @param string $authPi
     * @param int $behaviorType
     * @param int $time
     * @param string $deviceImei
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function behaviorLoginout($authPi, $behaviorType, $time, $deviceImei): bool
    {
        try {
            $no = 1;
            $si = $this->getSi($no);
            $defaultArr = [
                'no' => $no,
                'si' => $si, //一个会话标识只能对应唯一的实名用户，一个实名用户可以拥有多个会话标识；同一用户单次游戏会话中，上下线动作必须使用同一会话标识上报备注：会话标识仅标识一次用户会话，生命周期仅为一次上线和与之匹配的一次下线，不会对生命周期之外的任何业务有任何影响
                'bt' => $behaviorType,//0：下线1：上线
                'ot' => $time, //'行为发生时间戳，单位秒',
                'ct' => 0,//用户行为数据上报类型0：已认证通过用户2：游客用户
                'di' => $deviceImei,//游客模式设备标识，由游戏运营单位生成，游客用户下必填
                'pi' => $authPi,//已通过实名认证用户的唯一标识，已认证通过用户必填
            ];
            $param = [
                'collections' => [
                    $defaultArr
                ]
            ];

            $requestBody = $this->createBody($param);
            $sign = $this->createSign($requestBody);
            $headers = $this->getHeaders($sign);


            $requestUrl = $this->getConfig()->getBehaviorUrl();
            $client = new Client([
                'timeout' => 3,
                'headers' => $headers
            ]);

            $response = $client->post($requestUrl, ['body' => $requestBody]);
            $statusCode = $response->getStatusCode();
            $respBody = $response->getBody()->getContents();
            try {
                $result = json_decode($respBody, true);
                if ($result['errcode'] != 0) {
                    throw  new \Exception($result['errcode'] . '-' . $result['errmsg']);
                }
            } catch (\Exception $exception) {
                throw  new \Exception($exception->getMessage());
            }
        } catch (\Exception $exception) {
            throw  new \Exception($exception->getMessage());
        }
        return true;
    }


    public function requestVerify($requestUrl, $param, $idcard)
    {
        $requestBody = $this->createBody($param);
        $sign = $this->createSign($requestBody);
        $headers = $this->getHeaders($sign);

        try {
            $client = new Client([
                'timeout' => 3,
                'headers' => $headers
            ]);
            $response = $client->post($requestUrl, ['body' => $requestBody]);
            $statusCode = $response->getStatusCode();

            $proofResult = new ProofIdentifyResult();
            $proofResult->setStatusCode($statusCode);
            $proofResult->setErrorMessage('工宣部网络异常！');
            if ($statusCode == 200) {
                //{"errcode":1011,"errmsg":"SYS REQ PARTNER AUTH ERROR"} 请求错误
                //{"errcode":0,"errmsg":"OK","data":{"result":{"status":2,"pi":null}}} 实名中
                //{"errcode":0,"errmsg":"OK","data":{"result":{"status":0,"pi":"1hd2k123pd9i9sh7rz15hekim9wf0ko8ty49ha"}} 实名成功
                $respBody = $response->getBody()->getContents();
                $respBody = json_decode($respBody, true);
                $authResult = null;
                if (isset($respBody['data']) && !empty($authResult = $respBody['data']['result'])) {
                    $proofResult->setStatusCode($authResult['status']);
                    $proofResult->setErrorMessage($respBody['errmsg']);
                    if ($proofResult->getStatusCode() == ProofIdentifyResult::REALAUTH_SUCCEED) {
                        $idCardParser = new IdCardParser($idcard);
                        $idCardParser->setPi($authResult['pi'] ?? '');
                        $proofResult->setIdCardParser($idCardParser);
                    }
                } else {
                    $proofResult->setStatusCode($respBody['errcode']);
                    $proofResult->setErrorMessage($respBody['errmsg']);
                }
            }
            return $proofResult;
        } catch (\Throwable $exception) {
            throw new ProofIdentifyException($exception->getMessage());
        }
    }


    public function setUID($uid)
    {
        $this->uid = $uid;
    }

    public function getUID(): ?string
    {
        return $this->uid;
    }

    /**
     * 账号标记
     * @param string $username 用户名
     * @return string
     * @throws \Exception
     */
    public function getAi()
    {
        return md5($this->uid);
    }


    /**
     * 生成账号si
     * @param string $username 用户名
     * @param string $no 编号
     * @throws \Exception
     */
    public function getSi($no)
    {
        if (empty($this->getUID())) {
            throw  new  \Exception('未设置uid');
        }

        return md5($no . "|" . $this->getUID());
    }

    /**
     * 获取请求header
     * @param string|null $sign
     * @return array
     */
    public function getHeaders($sign = NULL)
    {
        $config = $this->getConfig();
        return [
            'Content-Type' => "application/json;charset=utf-8",
            'appId' => $config->getAppId(),
            'bizId' => $config->getBizId(),
            'timestamps' => $this->milistime(),
            'sign' => $sign,
        ];
    }


    /**
     * 生成签名数据
     * @param $rawBody
     * @return string
     */
    public function createSign($rawBody)
    {
        $config = $this->getConfig();
        $data = $this->getHeaders();
        if (is_array($rawBody)) $data += $rawBody;
        ksort($data);
        $source = [$config->getSecretKey()];
        foreach ($data as $key => $value) {
            if ($key !== 'sign' && $key != 'Content-Type') {
                $source[] = "{$key}{$value}";
            }
        }
        if (!is_array($rawBody)) $source[] = $rawBody;
        $presign = implode("", $source);
        return hash("sha256", $presign);
    }


    /**
     * 生成请求body
     * @param string|mixed $string 请求内容
     * @return false|string
     */
    public function createBody($string, $json = true)
    {
        $encString = $this->aesEncrypt($string);
        //JSON需要不经过转义数据
        $json = json_encode(['data' => $encString], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        return $json;
    }

    /**
     * 单次程序执行返回同一个时间戳
     * @return NULL|string
     */
    public function milistime()
    {
        $timestr = explode(' ', microtime());
        $milistime = strval(sprintf('%d%03d', $timestr[1], $timestr[0] * 1000));
        return $milistime;
    }

    /**
     * AES解密
     * @param string $content 加密密文
     * @return string
     */
    public function aesDecrypt($content)
    {
        $config = $this->getConfig();
        $ciphertextwithiv = bin2hex(base64_decode($content));
        $iv = substr($ciphertextwithiv, 0, 24);
        $tag = substr($ciphertextwithiv, -32, 32);
        $ciphertext = substr($ciphertextwithiv, 24, strlen($ciphertextwithiv) - 24 - 32);
        $cipher = strtolower('AES-128-GCM');
        return openssl_decrypt(hex2bin($ciphertext), $cipher, hex2bin($config->getSecretKey()), OPENSSL_RAW_DATA, hex2bin($iv), hex2bin($tag));
    }


    /**
     * AES加密
     * @param string|mixed $string 数据原文
     * @return string
     */
    public function aesEncrypt($string)
    {
        $config = $this->getConfig();
        $cipher = strtolower('AES-128-GCM');
        if (is_array($string)) $string = json_encode($string, JSON_UNESCAPED_UNICODE);
        //二进制key
        $skey = hex2bin($config->getSecretKey());
        //二进制iv
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));
        $tag = '';
        $content = openssl_encrypt($string, $cipher, $skey, OPENSSL_RAW_DATA, $iv, $tag);
        $str = bin2hex($iv) . bin2hex($content) . bin2hex($tag);
        return base64_encode(hex2bin($str));
    }
}