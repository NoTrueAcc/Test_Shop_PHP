<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 24.05.2017
 * Time: 7:19
 */

namespace admin\database;

require_once "dataBaseClass.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/library/config/configClass.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/library/helper/urlClass.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/library/helper/checkerClass.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/library/helper/formatClass.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/library/messages/systemMessageClass.php";

use admin\config\configClass;
use admin\helper\urlClass;
use admin\helper\checkerClass;
use admin\helper\formatClass;
use admin\messages\systemMessageClass;

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
    public function selectAll($order = false, $desc = false, $limit = false, $offset = false)
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
    protected function selectOrderOrLimit($order, $desc, $limit = false, $offset = false)
    {
        $order = $order ? " ORDER BY `$order`" : ' ORDER BY `id`';
        $desc = $desc ? " DESC" : '';
        $offset = $this->checker->isIntNumber($offset) ? " $offset ," : '';
        $limit = $this->checker->checkNumberIntMoreOrZero($limit, true) ? " LIMIT $offset $limit" : '';

        return $order . $desc . $limit;
    }

    protected function selectColumnOnFieldValue($field, $value, $column)
    {
        $query = "SELECT `$column` FROM " . $this->tableName . " WHERE `$field` = " . $this->config->symQuery;

        return $this->dataBaseConnect->selectCell($query, array($value));
    }

    public function getSearchDataList($searchText, $searchFields, $order = false, $up = false)
    {
        $searchText = trim($searchText);

        if(empty($searchFields) || ($searchText == ''))
        {
            return false;
        }

        $searchText = preg_replace('/\s+/i', ' ', $searchText);
        $searchData = explode(' ', $searchText);
        $where = '';
        $logic = ' AND ';
        $params = array();
        $desc = ($up == 'up') ? true : false;
        $order = $this->selectOrderOrLimit($order, $desc);

        for($i = 0; $i < count($searchData); $i++)
        {
            $where .= '(';
            for($j = 0; $j < count($searchFields); $j++)
            {
                $where .= (($j+1) == count($searchFields)) ? '`' . $searchFields[$j] . '` LIKE ' . $this->config->symQuery : '`' . $searchFields[$j] . '` LIKE ' . $this->config->symQuery . ' OR ';
                $params[] = '%' . $searchData[$i] . '%';
            }
            $where .= (($i + 1) == count($searchData)) ? ')' : ')' . $logic;
        }

        $query = "SELECT * FROM " . $this->tableName . " WHERE ($where) $order";

        return $this->dataBaseConnect->selectData($query, $params);
    }

    /**
     * Метод для добавления записи
     *
     * @param $data
     * @return bool
     */
    public function insertData($data)
    {
        if(!$this->check($data))
        {
            return false;
        }

        $fields = '';
        $values = '';
        foreach ($data as $field => $value)
        {
            $fields .= "`$field`,";
            $values .= $this->config->symQuery . ",";

        }

        $fields = substr($fields, 0, -1);
        $values = substr($values, 0, -1);
        $query = "INSERT INTO `" . $this->tableName . "` ($fields) VALUES ($values)";

        return $this->dataBaseConnect->sendQuery($query, array_values($data));
    }

    public function updateAllData($id, $data)
    {
       if(!$this->check($data))
       {
           return false;
       }

       $query = 'UPDATE `' . $this->tableName . '` SET ';

       foreach ($data as $field => $value)
       {
           $query .= "`$field` = " . $this->config->symQuery . ',';
       }

       $query = substr($query, 0, -1);
       $query .= ' WHERE `id` = ' . $this->config->symQuery;
       $data['id'] = $id;

       return $this->dataBaseConnect->sendQuery($query, array_values($data));
    }

    public function updateFieldsOnId($id, $newData)
	{
		if(!$this->checkFields($newData))
		{
			return false;
		}

		$query = 'UPDATE `' . $this->tableName . '` SET ';

		foreach ($newData as $field => $value)
		{
			$query .= "`$field` = " . $this->config->symQuery . ',';
		}

		$query = substr($query, 0, -1);
		$query .= ' WHERE `id` = ' . $this->config->symQuery;
		$newData['id'] = $id;

		return $this->dataBaseConnect->sendQuery($query, array_values($newData));
	}

    public function deleteData($id)
    {
        $query = 'DELETE FROM `' . $this->tableName . '` WHERE `id` = ' . $this->config->symQuery;

        return $this->dataBaseConnect->sendQuery($query, array($id));
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function getRowsCount()
    {
        $query = 'SELECT COUNT(`id`) as count FROM ' . $this->tableName;
        $result = $this->dataBaseConnect->selectCell($query);

        return $result['count'];
    }

    /**
     * Проверка данных на корректность. Для каждого класса своя.
     *
     * @param $data
     * @return bool
     */
    private function check($data)
    {
        $result = $this->checkData($data);

        if($result === true)
        {
            return true;
        }
        else
        {
            $systemMessage = new systemMessageClass();

            return $systemMessage->getMessage($result);
        }
    }

	/**
	 * Проверка данных на корректность. Для каждого класса своя.
	 *
	 * @param $data
	 * @return bool
	 */
	private function checkFields($data)
	{
		$result = $this->checkFieldsData($data);

		if($result === true)
		{
			return true;
		}
		else
		{
			$systemMessage = new systemMessageClass();

			return $systemMessage->getMessage($result);
		}
	}

    /**
     * По умолчанию проверка возвращает false
     *
     * @param $data
     * @return bool
     */
    protected function checkData($data)
    {
        return false;
    }

	/**
	 * По умолчанию проверка возвращает false
	 *
	 * @param $data
	 * @return bool
	 */
	protected function checkFieldData($data)
	{
		return false;
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