<?php
declare(strict_types=1);

/**
 * This file is part of a07sky/proof-identify
 *
 * @link     https://github.com/a07sky/proof-identify
 * @contact  a07sky@126.com
 * @license  https://github.com/proof-identify/blob/master/LICENSE
 */

namespace Onetrue\ProofIdentity\Utils;



class IdCardParser
{

    /**
     * 身份证号
     * @var string
     */
    protected $idCard = '';

    /**
     * 生日
     * @var string
     */
    protected $birthday = '';

    /**
     * 年龄
     * @var int
     */
    protected $age = 0;

    /**
     * 性别
     * @var int
     */
    protected $gender = 0;

    /**
     * 性别索引
     * @var int
     */
    protected $genderIndex = 0;


    /**
     * 身份证PI值, 工宣部独有
     * @var string
     */
    protected $pi = '';

    public function __construct($idCard)
    {
        $this->idCard = $idCard;
        $this->parse();
    }

    protected function parse()
    {
        //解析生日
        $year  = substr($this->idCard, 6, 4);
        $month = substr($this->idCard, 10, 2);
        $day   = substr($this->idCard, 12, 2);
        //生日字符
        $this->birthday = implode('-', [$year, $month, $day]);
        //计算年龄
        $age    = date('Y') - $year;
        $cMonth = date('m');
        $cDay   = date('m');
        if ($month > $cMonth || $month == $cMonth && $day > $cDay) {
            //如果出生月大于当前月或出生月等于当前月但出生日大于当前日则减一岁
            $age--;
        }
        $this->age = $age;
        //解析性别
        $gender_char = substr($this->idCard, -2, 1);
        if ($gender_char % 2 == 0) {
            $gender = 2; //女
            $this->gender = '女';
        } else {
            $gender = 1; //男
            $this->gender = '男';
        }
        $this->genderIndex = $gender;
    }

    /**
     * @return string
     */
    public function getPi(): string
    {
        return $this->pi;
    }

    /**
     * @param string $pi
     */
    public function setPi(string $pi): void
    {
        $this->pi = $pi;
    }


    /**
     * @return string
     */
    public function getBirthday(): string
    {
        return $this->birthday;
    }

    /**
     * @return int
     */
    public function getAge(): int
    {
        return $this->age;
    }

    /**
     * 获取性别
     * @return int
     */
    public function getGender(): int
    {
        return $this->gender;
    }

    /**
     * 性别索引
     * @return int
     */
    public function getGenderIndex()
    {
        return $this->genderIndex;
    }
}