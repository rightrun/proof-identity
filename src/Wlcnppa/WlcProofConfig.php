<?php
declare(strict_types=1);

/**
 * This file is part of a07sky/proof-identify
 *
 * @link     https://github.com/a07sky/proof-identify
 * @contact  a07sky@126.com
 * @license  https://github.com/proof-identify/blob/master/LICENSE
 */

namespace Onetrue\ProofIdentity\Wlcnppa;

use Onetrue\ProofIdentity\Contracts\ConfigInterface;

class WlcProofConfig implements ConfigInterface
{
    /**
     * appid
     * @var string|null
     */
    private $appId = '';

    /**
     * secret key
     * @var string|null
     */
    private $secretKey = '';

    /**
     * 业务权限标识-与游戏备案识别码一致
     * @var string|null
     */
    private $bizId = '';


    /**
     * 实名认证接口
     * @var string
     */
    private $verifyUrl = 'https://api.wlc.nppa.gov.cn/idcard/authentication/check';
    /**
     * 查询实名结果URL
     * @var string
     */
    private $queryUrl = 'http://api2.wlc.nppa.gov.cn/idcard/authentication/query';

    /**
     * 行为上报接口
     * @var string
     */
    private $behaviorUrl = 'http://api2.wlc.nppa.gov.cn/behavior/collection/loginout';


    public function __construct($appId, $secretKey, $bizId)
    {
        $this->appId = $appId;
        $this->secretKey = $secretKey;
        $this->bizId = $bizId;
    }

    /**
     * 获取应用ID
     * @return null
     */
    public function getAppId()
    {
        return $this->appId;
    }


    /**
     * 获取应用密钥
     * @return null
     */
    public function getSecretKey()
    {
        return $this->secretKey;
    }

    /**
     * 获取备案号
     * @return null
     */
    public function getBizId()
    {
        return $this->bizId;
    }


    public function getVerifyUrl()
    {
        return $this->verifyUrl;
    }

    public function getQueryUrl()
    {
        return $this->queryUrl;
    }

    public function getBehaviorUrl()
    {
        return $this->behaviorUrl;
    }
}