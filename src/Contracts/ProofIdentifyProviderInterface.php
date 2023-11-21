<?php
declare(strict_types=1);

/**
 * This file is part of a07sky/proof-identify
 *
 * @link     https://github.com/a07sky/proof-identify
 * @contact  a07sky@126.com
 * @license  https://github.com/proof-identify/blob/master/LICENSE
 */

namespace Onetrue\ProofIdentity\Contracts;

interface ProofIdentifyProviderInterface
{
    public function verify(string $realname, string $idcard): ?ResultInterface;


    /**
     * @param string $authPi 实名认证pi值，工宣部独有
     * @param integer $behaviorType 行为类型: 上线和下线的标记（ 0：下线  1：上线）
     * @param integer $time 上线和下线的时间截（ 秒数）
     * @param string $deviceImei 设备号，游客模式下需要传设备号
     * @return bool
     */
    public function behaviorLoginout($authPi, $behaviorType, $time, $deviceImei): bool;
}