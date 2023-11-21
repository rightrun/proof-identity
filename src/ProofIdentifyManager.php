<?php
declare(strict_types=1);

/**
 * This file is part of a07sky/proof-identify
 *
 * @link     https://github.com/a07sky/proof-identify
 * @contact  a07sky@126.com
 * @license  https://github.com/proof-identify/blob/master/LICENSE
 */


namespace Onetrue\ProofIdentity;

use Onetrue\ProofIdentity\Contracts\ConfigInterface;
use Onetrue\ProofIdentity\Contracts\ProofIdentifyProviderInterface;
use Onetrue\ProofIdentity\Exception\ProofIdentifyException;

class ProofIdentifyManager
{

    /**
     * @var ProofIdentifyProviderInterface
     */
    protected $provider = null;

    /**
     * @var ConfigInterface
     */
    protected $providerConfig;

    public function __construct($provider, $providerConfig)
    {
        $this->provider = new $provider();
        $this->providerConfig = $providerConfig;
    }

    public function getProvider()
    {
        if (is_null($this->provider)) {
            throw new ProofIdentifyException('选择实名方式');
        }
        $this->provider->setConfig($this->providerConfig);
        return $this->provider;
    }
}