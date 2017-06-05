<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 20.05.2017
 * Time: 17:49
 */

namespace helper;

require_once $_SERVER['DOCUMENT_ROOT'] . "/library/config/configClass.php";

use config\configClass;

/**
 * Класс помошник для проверки форматов данных а так же для получения данных в нужном формате
 * @package helper
 */
class formatClass
{
    private $config;

    public function __construct()
    {
        $this->config = new configClass();
    }

    /**
     * Возвращает текущую дату и время в формате UNIXTIMESTAMP
     *
     * @return UNIXTIMESTAMP
     */
    public function getTimeStamp()
    {
        return time();
    }

    /**
     * Проверяет данные на спецсимволы и преобразует их в html сущности
     *
     * @param данные_для_проверки
     * @return преобразованные_данные в виде массива
     */
    public function checkDataFromXSS($data)
    {
        if(is_array($data))
        {
            $checkedData = array();

            foreach ($data as $key => $value)
            {
                $checkedData[$key] = $this->checkDataFromXSS($value);
            }

            return $checkedData;
        }

        return htmlspecialchars($data);
    }
}