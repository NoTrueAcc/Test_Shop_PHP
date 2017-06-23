<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 24.05.2017
 * Time: 18:29
 */

namespace database\tableClasses;

require_once $_SERVER['DOCUMENT_ROOT'] . "/library/dataBase/globalDataBaseAbstractClass.php";

use database\globalDataBaseAbstractClass;

/**
 * Класс для работы с таблицей Products
 *
 * Class productClass
 * @package database\tableClasses
 */
class productClass extends globalDataBaseAbstractClass
{
    public function __construct()
    {
        parent::__construct('products');
    }

    /**
     * Возвращает данные из БД по разделу
     *
     * @param id_раздела
     * @return Двумерный_массив_с_данными_запроса
     */
    public function selectAllOnSectionId($sectionId)
    {
        return $this->selectAllOnField('section_id', $sectionId);
    }

    public function selectAllOnProductId($productId)
    {
        return $this->transformData($this->selectAllOnField('id', $productId));
    }

    /**
     * ПРоверяет существование id
     *
     * @param $id
     * @return bool
     */
    public function isExistsId($id)
    {
        if(!$this->checker->checkNumberIntMoreOrZero($id) || !$this->isExistsFieldValue('id', $id))
        {
            return false;
        }

        return true;
    }

    /**
     * Проверяет существование значения для поля
     *
     * @param $field
     * @param $value
     * @return bool
     */
    private function isExistsFieldValue($field, $value)
    {
        $result = $this->selectAllOnField($field, $value);

        return !empty($result) ? true : false;
    }

    /**
     * Получить все данные
     *
     * @param количество
     * @param bool сортировка
     * @return преобразованные_данные_в_виде_массива_через_transformElement
     */
    public function getAllData($limit, $up = true)
    {
        return $this->transformData($this->selectAll('date', $up, $limit));
    }

    /**
     * Получить сортированные данные
     * Возможности сортировки: по Цене и по Названию (up | down)
     *
     * @param столбец_по_которому_сортируем
     * @param up_down
     * @param лимит
     */
    public function getAllSortData($sortColumn, $desc, $limit)
    {
        if(!$this->checkSortDesc($sortColumn, $desc))
        {
            return $this->getAllData($limit);
        }
        else
        {
            $desc = ($desc === 'up') ? 'DESC' : '';
            $query = "SELECT * FROM (SELECT * FROM `" . $this->tableName . "` ORDER BY `date` DESC LIMIT $limit) as t ORDER BY `$sortColumn` $desc";

             return $this->transformData($this->dataBaseConnect->selectData($query));
        }
    }

    /**
     * Получает сортированные данные с определенным id секции
     *
     * @param $id
     * @param $sortColumn
     * @param $desc
     */
    public function getAllSortDataOnSectionId($sectionId, $sortColumn, $desc)
    {
        if(!$this->checkSortDesc($sortColumn, $desc))
        {
            return $this->transformData($this->selectAllOnField('section_id', $sectionId));
        }
        else
        {
            $desc = ($desc === 'up') ? '' : 'DESC';

            return $this->transformData($this->selectAllOnField('section_id', $sectionId, $sortColumn, $desc));
        }
    }

    public function getOtherProducts($sectionId, $productId)
    {
        $query = "SELECT * FROM " . $this->tableName . " WHERE `section_id` = " . $this->config->symQuery . " AND `id` != $productId ORDER BY RAND() LIMIT " . $this->config->othersLimit;

        return $this->transformData($this->dataBaseConnect->selectData($query, array($sectionId)));
    }

    public function getAllOnIds($ids)
    {
        if(!count($ids))
        {
            return false;
        }

        $queryIds = array();
        $queryIdsData = array();

        foreach($ids as $key => $value)
        {
            $queryIds[] = $this->config->symQuery;
            $queryIdsData[] =  $value;
        }

        $queryIds = implode(',', $queryIds);
        $query = "SELECT * FROM `" . $this->tableName . "` WHERE `id` IN ($queryIds)";

        return $this->transformData($this->dataBaseConnect->selectData($query, $queryIdsData));
    }

    public function getCartPriceOnIds($cartProductIds)
    {
        $productDataList = $this->getAllOnIds($cartProductIds);
        $productPriceOnIds = array();
        $fullPriceOnIds = 0;

        for($i = 0; $i < count($productDataList); $i++)
        {
            $productPriceOnIds[$productDataList[$i]['id']] = $productDataList[$i]['price'];
        }

        for($i = 0; $i < count($cartProductIds); $i++)
        {
            $fullPriceOnIds += $productPriceOnIds[$cartProductIds[$i]];
        }

        return $fullPriceOnIds;
    }

    public function getTitleAndCountOnIds($idsString)
    {
        $idsArray = explode(',', $idsString);
        $idsDataList = $this->getAllOnIds(array_unique($idsArray));
        $titleDataOnIds = array();

        for($i = 0; $i < count($idsDataList); $i++)
        {
            $titleDataOnIds[$i]['title'] = $idsDataList[$i]['title'];
            $titleDataOnIds[$i]['count'] = $this->__getCountValueInArray($idsDataList[$i]['id'], $idsArray);
        }

        return $titleDataOnIds;
    }

    /**
     * Находит количество совпадений в 2у-мерном массиве
     *
     * @param $id
     * @param $idDataList
     * @return int
     */
    private function __getCountValueInArray($value, $valueDataList)
    {
        $countValue = 0;

        for($i = 0; $i < count($valueDataList); $i++)
        {
            $countValue = ($valueDataList[$i] == $value) ? ($countValue + 1) : $countValue;
        }

        return $countValue;
    }

    /**
     * Проверка на корректность данных сортировки
     *
     * @param $sortColumn
     * @param $desc
     * @return bool
     */
    private function checkSortDesc($sortColumn, $desc)
    {
        return (($sortColumn === 'title' || $sortColumn === 'price') && ($desc === 'up' || $desc === 'down'));
    }

    protected function transformElement($productDataElement)
    {
        $productDataElement['img'] = $this->config->productImagesDir . $productDataElement['img'];
        $productDataElement['link'] = $this->url->productDataElementLink($productDataElement['id']);
        $productDataElement['link_cart'] = $this->url->addDataElementToCart($productDataElement['id']);

        return $productDataElement;
    }
}