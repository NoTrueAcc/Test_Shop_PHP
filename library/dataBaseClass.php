<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 19.05.2017
 * Time: 17:25
 */

namespace library\dataBaseClass;

/**
 * Используем класс конфигурации
 */
use config;

class dataBaseClass
{
    private static $dataBaseConnect = null;
    private $config;
    private $mysqli;

    /**
     * Метод для создания объекта класса. Sinleton, если объукт класса уже существует,- то мы возвращаем его.
     *
     * @return dataBaseClass object
     */
    private function getConnection()
    {
        if(self::$dataBaseConnect == null)
        {
            self::$dataBaseConnect = new dataBaseClass();
        }
        else
        {
            return self::$dataBaseConnect;
        }
    }

    private function __construct()
    {
        $this->config = new config\configClass();
        $this->mysqli = new \mysqli($this->config->dataBaseHost, $this->config->dataBaseLogin, $this->config->dataBasePassword, $this->config->dataBaseSchema);
        $this->mysqli->query("SET NAME 'UTF8'");
    }
}