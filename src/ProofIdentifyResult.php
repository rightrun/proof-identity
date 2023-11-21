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


use Onetrue\ProofIdentity\Contracts\ResultInterface;
use Onetrue\ProofIdentity\Utils\IdCardParser;


/**
 * 实名认证结果
 */
class ProofIdentifyResult implements ResultInterface
{

    /**
     * 成功枚举
     */
    const REALAUTH_SUCCEED = 0;

    /**
     * 实名状态： 0表示成功
     * @var integer
     */
    protected $statusCode = -1;


    /**
     *
     * 错误信息
     * @var string
     */
    protected $errorMessage = '';

    /**
     * @var IdCardParser null
     */
    protected $idCardParser = null;


    /**
     * @param IdCardParser $parser
     * @return mixed|void
     */
    public function setIdCardParser($parser)
    {
        $this->idCardParser = $parser;
    }

    /**
     * 身份证解析器
     * @return IdCardParser|null
     */
    public function getIdCardParser()
    {
        return $this->idCardParser;
    }

    public function setStatusCode($statusCode)
    {
        // TODO: Implement setStatus() method.
        $this->statusCode = $statusCode;
    }

    public function getStatusCode()
    {
        // TODO: Implement getStatus() method.
        return $this->statusCode;
    }

    /**
     * @return string
     */
    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

    /**
     * @param string $errorMessage
     */
    public function setErrorMessage(string $errorMessage): void
    {
        $this->errorMessage = $errorMessage;
    }

}