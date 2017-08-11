<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 23.05.2017
 * Time: 7:23
 */

namespace admin\helper;

require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/library/config/configClass.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/library/messages/systemMessageClass.php";

use admin\config\configClass;
use admin\messages\systemMessageClass;

/**
 * Класс помошник для проверки данных на корректность
 *
 * Class checkerClass
 * @package helper
 */

class checkerClass
{
    private $config;
    private $__systemMessage;

    public function __construct()
    {
        $this->config = new configClass();
        $this->__systemMessage = new systemMessageClass();
    }

    /**
     * Метод для проверки числа
     *
     * @param числ
     * @param может ли принимать значение === 0
     * @return bool Булево значение условия число целое, больше 0 и соответствует ли оно условию === 0
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
        return preg_match('/^(\d)+(,\d+)*$/', $ids);
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

    public function checkTitle($title, $empty)
    {
        if($empty && empty($title))
        {
            return true;
        }

        if(is_string($title) && $this->checkLength($title, $this->config->titleMinLength, $this->config->titleMaxLength))
        {
            return true;
        }

        return false;
    }

    public function checkCode($code)
    {
        return preg_match('/^[0-9a-z]+$/i', $code);
    }

    public function checkDiscountValue($floatNumber)
    {
        if(!is_float($floatNumber) && !is_string($floatNumber))
        {
            return false;
        }

        return preg_match('/^0\.[\d]+$/i', $floatNumber);
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

    public function checkYear($year)
    {
        if(!$this->isIntNumber($year))
        {
            return false;
        }

        return $year > 0;
    }

    public function checkPlay($play)
    {
        return preg_match('/^\d{2}:\d{2}:\d{2}$/', $play);
    }

    public function checkImage($img)
    {
        $badExpansions = array('.php', '.phtml', '.php3', '.php4', '.html', '.htm');
        $goodTypes = array('image/jpg', 'image/jpeg', 'image/png');

        foreach ($badExpansions as $expansion)
        {
            if(preg_match("/$expansion\$/i", $img['name']))
            {
                return $this->__systemMessage->getMessage('ERROR_IMAGE_EXPANSION');
            }
        }

        $imageType = $img['type'];
        $imageSize = $img['size'];

        if(!in_array($imageType, $goodTypes))
        {
            return $this->__systemMessage->getMessage('ERROR_IMAGE_TYPE');
        }

        if($imageSize > $this->config->maxImageSize)
        {
            return $this->__systemMessage->getMessage('ERROR_IMAGE_SIZE');
        }

        return true;
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