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

use Onetrue\ProofIdentity\Contracts\ConfigInterface;

class AliyunProofConfig implements ConfigInterface
{

    protected $verifyKey = "";

    protected $appCode = '';


    public function __construct($verifyKey, $appCode)
    {
        $this->verifyKey = $verifyKey;
        $this->appCode = $appCode;
    }


    public function getVerifyKey()
    {
        return $this->verifyKey;
    }

    public function getAppCode()
    {
        return $this->appCode;
    }
}