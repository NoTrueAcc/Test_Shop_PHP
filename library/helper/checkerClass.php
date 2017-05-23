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
     * Метод для проверки id на корректность
     *
     * @param $id
     * @param может ли Id принимать значение === 0
     * @return bool
     */
    public function checkId($id, $zero = false)
    {
        if(!$this->isIntNumber($id) || $id < 0 || (!$zero && ($id === 0)))
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

    /**
     * Метод для проверки яявляется ли число целым
     *
     * @param $number
     * @return bool|int
     */
    private function isIntNumber($number)
    {
        if(is_int($number) || is_string($number))
        {
            return preg_match('/^-?(([1-9][0-9]*)|(0))$/', $number);
        }

        return false;
    }
}