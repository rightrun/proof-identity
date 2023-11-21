<?php
declare(strict_types=1);

/**
 * This file is part of a07sky/proof-identify
 *
 * @link     https://github.com/a07sky/proof-identify
 * @contact  a07sky@126.com
 * @license  https://github.com/proof-identify/blob/master/LICENSE
 */

namespace Onetrue\ProofIdentity\Provider;

use Onetrue\ProofIdentity\Contracts\ConfigInterface;
use Onetrue\ProofIdentity\Contracts\ProofIdentifyProviderInterface;
use Onetrue\ProofIdentity\Contracts\ResultInterface;
use Onetrue\ProofIdentity\Exception\ProofIdentifyException;


abstract class ProofIdentifyProvider implements ProofIdentifyProviderInterface
{
    /**
     * @var ConfigInterface;
     */
    protected $config;

    public function __construct($config = null)
    {
        $this->config = $config;
    }

    public function setConfig(ConfigInterface $config)
    {
        $this->config = $config;
    }

    public function getConfig(): ConfigInterface
    {
        return $this->config;
    }

    protected function validIdCard($idCard)
    {
        if (!preg_match('/[1-9]\d{5}(18|19|([23]\d))\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}[0-9X]/', $idCard)) {
            throw new ProofIdentifyException('身份证格式不正确，如果最后1位是X，输入大写字母X');
        }
        return true;
    }

    public abstract function verify(string $realname, string $idcard): ?ResultInterface;

}