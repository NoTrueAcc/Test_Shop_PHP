<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 23.05.2017
 * Time: 7:23
 */

namespace helper;

require_once $_SERVER['DOCUMENT_ROOT'] . "/library/config/configClass.php";

use config\configClass;

/**
 * Класс помошник для проверки данных на корректность
 *
 * Class checkerClass
 * @package helper
 */

class checkerClass
{
    private $config;

    public function __construct()
    {
        $this->config = new configClass();
    }

    /**
     * Метод для проверки числа
     *
     * @param числ
     * @param может ли принимать значение === 0
     * @return Булево значение условия число целое, больше 0 и соответствует ли оно условию === 0
     */
    public function checkNumberIntMoreOrZero($number, $zero = false)
    {
        if(!$this->isIntNumber($number) || $number < 0 || (!$zero && ($number === 0)))
        {
            return false;
        }

        return true;
    }

    /**
     * Метод для проверки числа на равенство 0 или 1
     *
     * @param $number
     * @return bool
     */
    public function oneOrZero($number)
    {
        if(!$this->isIntNumber($number))
        {
            return false;
        }

        return (($number == 0) || ($number == 1));
    }

    public function checkIds($ids)
    {
        return preg_match('/^\d+(,\d+)*$/', $ids);
    }

    public function checkPrice($price)
    {
        return ($this->isDouble($price) && ($price >= 0));
    }

    public function checkName($name)
    {
        if($this->checkLength($name, $this->config->nameMinLength, $this->config->nameMaxLength))
        {
            return preg_match('/^(([-a-z]+(\s[-a-z]+)?(\s[-a-z]+)?)|([-а-я]+(\s[-а-я]+)?(\s[-а-я]+)?))$/ui', $name);
        }

        return false;
    }

    public function checkPhone($phone)
    {
        if($this->checkLength($phone, $this->config->phoneMinLength, $this->config->phoneMaxLength))
        {
            return preg_match('/^[\+]?[\d]+[-\d\s]+$/', $phone);
        }

        return false;
    }

    public function checkEmail($email)
    {
        if($this->checkLength($email, $this->config->emailMinLength, $this->config->emailMaxLength))
        {
            return preg_match('/^(([0-9a-z][0-9a-z\.\-_]*?[0-9a-z_]+@([0-9a-z]+[0-9a-z\-]*[0-9a-z]+\.)+?[a-z]+)|([0-9а-я][0-9а-я\.\-_]*?[0-9а-я_]+@([0-9а-я]+[0-9а-я\-]*[0-9а-я]+\.)+?[а-я]+))$/ui', $email);
        }

        return false;
    }

    public function checkText($text, $empty)
    {
        if($empty && empty($text))
        {
            return true;
        }

        if(is_string($text) && $this->checkLength($text, $this->config->textMinLength, $this->config->textMaxLength))
        {
            return true;
        }

        return false;
    }

    public function checkTimeStamp($timeStamp)
    {
        return $this->checkNumberIntMoreOrZero($timeStamp, true);
    }

    /**
     * Метод для проверки яявляется ли число целым
     *
     * @param $number
     * @return bool|int
     */
    public function isIntNumber($number)
    {
        if(is_int($number) || is_string($number))
        {
            return preg_match('/^((-?[1-9][0-9]*)|(0))$/', $number);
        }

        return false;
    }

    private function isDouble($number)
    {
        return is_numeric($number);
    }

    private function checkLength($string, $minLength, $maxLength)
    {
        return ((strlen($string) >= $minLength) && (strlen($string) <= $maxLength));
    }
}