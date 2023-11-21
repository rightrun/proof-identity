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


use Onetrue\ProofIdentity\Utils\IdCardParser;

/**
 * 实名认证结果
 */
interface ResultInterface
{


    /**
     * 设置解析器
     * @param $parser
     * @return mixed
     */
    public function setIdCardParser($parser);

    /**
     * 身份证解析器
     * @return IdCardParser|null
     */
    public function getIdCardParser();

    /**
     * 设置状态码
     * @param int $statusCode
     * @return mixed
     */
    public function setStatusCode($statusCode);

    /**
     * 实名状态码
     * @return int
     */
    public function getStatusCode();

    /**
     * @return string
     */
    public function getErrorMessage(): string;

    /**
     * @param string $errorMessage
     */
    public function setErrorMessage(string $errorMessage);
}