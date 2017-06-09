<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 24.05.2017
 * Time: 7:19
 */

namespace database;

require_once "dataBaseClass.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/library/config/configClass.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/library/helper/urlClass.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/library/helper/checkerClass.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/library/helper/formatClass.php";

use config\configClass;
use helper\urlClass;
use helper\checkerClass;
use helper\formatClass;

/**
 * Абстрактный класс для выполнения запросов в БД, их обработки и проверки
 *
 * Class globalDataBaseAbstractClass
 * @package database
 */
abstract class globalDataBaseAbstractClass
{
    protected $dataBaseConnect;
    protected $config;
    protected $format;
    protected $url;
    protected $checker;
    protected $tableName;

    public function __construct($tableName)
    {
        $this->dataBaseConnect = dataBaseClass::getConnection();
        $this->config = new configClass();
        $this->format = new formatClass();
        $this->url = new urlClass();
        $this->checker = new checkerClass();
        $this->tableName = $this->config->dataBasePrefix . $tableName;
    }

    /**
     * Возвращает значение всех полей по условиям
     *
     * @param bool сортировка
     * @param bool обратная_сортировка?
     * @param bool Лимит
     * @param bool Смещение
     * @return Двумерный_массив_с_данными_запроса
     */
    protected function selectAll($order = false, $desc = false, $limit = false, $offset = false)
    {
        $orderOrLimit = $this->selectOrderOrLimit($order, $desc, $limit, $offset);

        $query = "SELECT * FROM `" . $this->tableName . "`$orderOrLimit";

        return $this->dataBaseConnect->selectData($query);
    }

    /**
     * Возвращает значение роля по id
     *
     * @param $field
     * @param $id
     * @return mixed
     */
    protected function selectFieldOnId($field, $id)
    {
        $query = "SELECT `" . $field . "` FROM " . $this->tableName . " WHERE `id` = $id";
        $arrayData = $this->dataBaseConnect->selectData($query);

        return $arrayData[0][$field];
    }

    /**
     * Возврщает данные запроса по условию поле == значение
     *
     * @param поле
     * @param значение
     * @param bool сортировка
     * @param bool обратная_сортировка?
     * @param bool Лимит
     * @param bool Смещение
     * @return Двумерный_массив_с_данными_запроса
     */
    protected function selectAllOnField($field, $value, $order = false, $desc = false, $limit = false, $offset = false)
    {
        $orderOrLimit = $this->selectOrderOrLimit($order, $desc, $limit, $offset);
        $query = "SELECT * FROM `" . $this->tableName . "` WHERE `$field` = " . $this->config->symQuery . "$orderOrLimit";

        return $this->dataBaseConnect->selectData($query, array($value));
    }

    /**
     * Проверяет наличие и корректность полученных данных по сортировке, лимиту, смещению
     *
     * @param bool сортировка
     * @param bool обратная_сортировка?
     * @param bool Лимит
     * @param bool Смещение
     * @return данные_к_запросу
     */
    protected function selectOrderOrLimit($order, $desc, $limit, $offset)
    {
        $order = $order ? " ORDER BY `$order`" : '';
        $desc = $desc ? " DESC" : '';
        $offset = $this->checker->isIntNumber($offset) ? " `$offset` ," : '';
        $limit = $this->checker->checkNumberIntMoreOrZero($limit, true) ? " LIMIT $offset $limit" : '';

        return $order . $desc . $limit;
    }

    /**
     * Преобразует поступающие данные и добавляет новые элементы массиву
     *
     * @param данные_в_виде_массива
     * @return преобразованные данные в виде массива
     */
    protected function transformData($dataElement)
    {
        if(is_array($dataElement) && !empty($dataElement))
        {
            for($i = 0; $i < count($dataElement); $i++)
            {
                $dataElement[$i] = $this->transformElement($dataElement[$i]);
            }

            return $dataElement;
        }
        elseif(!empty($dataElement))
        {
            return $this->transformElement($dataElement);
        }

        return false;
    }
}