<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 19.05.2017
 * Time: 17:25
 */

namespace admin\database;

require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/library/config/configClass.php";
/**
 * Используем класс конфигурации
 */
use admin\config\configClass;

/**
 * Класс подключения к базе данных
 * Class dataBaseClass
 * @package library\dataBaseClass
 */
class dataBaseClass
{
    private static $dataBaseConnect = null;
    private $config;
    private $mysqli;

    /**
     * dataBaseClass constructor.
     */
    private function __construct()
    {
        $this->config = new configClass();
        $this->mysqli = new \mysqli($this->config->dataBaseHost, $this->config->dataBaseLogin, $this->config->dataBasePassword, $this->config->dataBaseSchema);
        $this->mysqli->query("SET NAMES UTF8");
    }


    /**
     * Метод для создания объекта класса. Sinleton, если объукт класса уже существует,- то мы возвращаем его.
     *
     * @return object_dataBaseClass
     */
    public static function getConnection()
    {
        if(self::$dataBaseConnect == null)
        {
            self::$dataBaseConnect = new dataBaseClass();
        }

        return self::$dataBaseConnect;
    }

    /**
     * Метод для замены параметров в запросе.
     * Так же проводит проверку параметров
     *
     * @param запрос
     * @param bool параметры
     * @return измененный_запрос
     */
    private function getQuery($query, $params)
    {
        if(!empty($params))
        {
            for ($i = 0; $i < count($params); $i++)
            {
                $pos = strpos($query, $this->config->symQuery);
                $arg = "'" . $this->mysqli->real_escape_string($params[$i]) . "'";
                $query = substr_replace($query, $arg, $pos, strlen($this->config->symQuery));
            }
        }

        return $query;
    }

    /**
     * Метод для получения данных из таблицы.
     *
     * @param запрос
     * @param bool параметры
     * @return bool|массив - при ошибке false, иначе двумерный массив с данными
     */
    public function selectData($query, $params = false)
    {
        $resultData = $this->mysqli->query($this->getQuery($query, $params));

        return (empty($resultData)) ? false : $this->setResultToArray($resultData);
    }

    /**
     * Метод для получения одной строки из таблицы
     *
     * @param запрос
     * @param bool параметры
     * @return bool|массив данных
     */
    public function selectRow($query, $params = false)
    {
        $resultData = $this->mysqli->query($this->getQuery($query, $params));

        return (empty($resultData) || ($resultData->num_rows != 1)) ? false : $this->setResultToArray($resultData);
    }

    /**
     * Метод для получения значения ячейки
     *
     * @param запрос
     * @param bool параметры
     * @return bool
     */
    public function selectCell($query, $params = false)
    {
        $resultData = $this->mysqli->query($this->getQuery($query, $params));

        if(empty($resultData) || ($resultData->num_rows != 1))
        {
            return false;
        }

        $resultDataArray = $this->setResultToArray($resultData);

        return $resultDataArray[0];
    }

    public function sendQuery($query, $params)
    {
        return $this->mysqli->query($this->getQuery($query, $params));
    }

    /**
     * Преобразует полученные данные в массив
     *
     * @param $resultData - результирующий набор данных запроса
     * @return массив данных результирующего набора
     */
    private function setResultToArray($resultData)
    {
        $resultDataArray = array();
        while($row = $resultData->fetch_assoc())
        {
            array_push($resultDataArray, $row);
        }

        return $resultDataArray;
    }

    /**
     * Закрывает соединение с БД при уничтожении объекта
     */
    public function __destruct()
    {
        if($this->mysqli)
        {
            $this->mysqli->close();
        }
    }
}