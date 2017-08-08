<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 24.05.2017
 * Time: 18:29
 */

namespace admin\database\tableClasses;

require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/library/dataBase/globalDataBaseAbstractClass.php";

use admin\database\globalDataBaseAbstractClass;

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

    public function getSearchProductsData($searchText, $order, $desc)
    {
        $searchFields = array(
            'title',
            'country',
            'year',
            'director',
            'cast',
            'description'
        );

        return $this->transformData(parent::getSearchDataList($searchText, $searchFields, $order, $desc));
    }

    public function getAllTitlesAndIds()
    {
        $query = "SELECT `id`, `title` FROM " . $this->tableName;
        $result = $this->dataBaseConnect->selectData($query);
        $resultTitleAndIds = array();

        for($i = 0; $i < count($result); $i++)
        {
            $resultTitleAndIds[] = array($result[$i]['id'] => $result[$i]['title']);
        }

        return $resultTitleAndIds;


    }

    public function getDate($id)
    {
        return $this->selectFieldOnId('date', $id);
    }

    public function getImg($id)
    {
        return $this->selectFieldOnId('img', $id);
    }

    public function getAllOnIds($ids)
    {
        if(!count($ids))
        {
            return false;
        }

        $queryIds = array();
        $queryIdsData = array();

        foreach($ids as $value)
        {
            $queryIds[] = $this->config->symQuery;
            $queryIdsData[] =  $value;
        }

        $queryIds = implode(',', $queryIds);
        $query = "SELECT * FROM `" . $this->tableName . "` WHERE `id` IN ($queryIds)";

        return $this->dataBaseConnect->selectData($query, $queryIdsData);
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
            $titleDataOnIds[$i]['id'] = $idsDataList[$i]['id'];
            $titleDataOnIds[$i]['title'] = $idsDataList[$i]['title'];
            $titleDataOnIds[$i]['count'] = $this->__getCountValueInArray($idsDataList[$i]['id'], $idsArray);
        }

        return $titleDataOnIds;
    }

    /**
     * Выборка данных по определенному товару
     *
     * @param $id - айди товара
     * @param $sectionTable - название таблицы 'секции'
     * @return \admin\database\преобразованные|bool
     */
    public function getAllProductDataOnId($id, $sectionTable)
    {
        if(!$this->checker->checkIds($id))
        {
            return false;
        }

        $query = 'SELECT '
            . $this->tableName . '.`id` , '
            . $this->tableName . '.`section_id` , '
            . $this->tableName . '.`img` , '
            . $this->tableName . '.`title` as prod_title, '
            . $this->tableName . '.`price` , '
            . $this->tableName . '.`year` , '
            . $this->tableName . '.`country` , '
            . $this->tableName . '.`director` , '
            . $this->tableName . '.`play` , '
            . $this->tableName . '.`cast` , '
            . $this->tableName . '.`description` , '
            . $sectionTable    . '.`title` as section'
            . ' FROM ' . $this->tableName
            . ' INNER JOIN ' . $sectionTable . ' ON ' . $this->tableName . '.`section_id`' . ' = ' . $sectionTable . '.`id`'
            . ' WHERE ' . $this->tableName . '.`id` = ' . $this->config->symQuery;

        return $this->transformData($this->dataBaseConnect->selectRow($query, array($id)));
    }

    /**
     * Выборка данных для подстановки в таблицу с товарами
     *
     * @param $sectionTable - название таблицы 'секции'
     * @param $limit - лимит
     * @param $offset - смещение
     * @return \admin\database\преобразованные
     */
    public function getTableData($sectionTable, $limit, $offset)
    {
        $orderLimit = $this->selectOrderOrLimit(false, true, $limit, $offset);

        $query = 'SELECT '
            . $this->tableName . '.`id` , '
            . $this->tableName . '.`section_id` , '
            . $this->tableName . '.`img` , '
            . $this->tableName . '.`title` , '
            . $this->tableName . '.`price` , '
            . $this->tableName . '.`year` , '
            . $this->tableName . '.`country` , '
            . $this->tableName . '.`director` , '
            . $this->tableName . '.`play` , '
            . $this->tableName . '.`cast` , '
            . $this->tableName . '.`description` , '
            . $this->tableName . '.`date` , '
            . $sectionTable    . '.`title` as section'
            . ' FROM ' . $this->tableName
            . ' INNER JOIN ' . $sectionTable . ' ON ' . $this->tableName . '.`section_id`' . ' = ' . $sectionTable . '.`id`'
            . $orderLimit;

        return $this->transformData($this->dataBaseConnect->selectData($query));
    }

    public function getCountProductsOnImageName($imgName)
    {
        $query = 'SELECT COUNT(`id`) as count FROM ' . $this->tableName . ' WHERE `img` = ' . $this->config->symQuery;
        $result = $this->dataBaseConnect->selectCell($query, array($imgName));

        return $result['count'];
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

        if(isset($productDataElement['date']))
        {
            $productDataElement['date'] = gmdate('Y-m-d H:i:s', $productDataElement['date']);
        }

        $productDataElement['link_admin_edit'] = $this->url->getLinkAdminEditProduct($productDataElement['id']);
        $productDataElement['link_admin_delete'] = $this->url->getLinkAdminDeleteProduct($productDataElement['id']);

        return $productDataElement;
    }

    protected function checkData($data)
    {
        if(!$this->checker->checkIds($data['section_id']))          return 'UNKNOWN_ERROR';
        if(!$this->checker->checkTitle($data['img'], false))        return 'ERROR_IMAGE_NAME';
        if(!$this->checker->checkTitle($data['title'], false))      return 'ERROR_TITLE';
        if(!$this->checker->checkPrice($data['price']))             return 'ERROR_PRICE';
        if(!$this->checker->checkYear($data['year']))               return 'ERROR_YEAR';
        if(!$this->checker->checkTitle($data['country'], false))    return 'ERROR_COUNTRY';
        if(!$this->checker->checkTitle($data['director'], false))   return 'ERROR_DIRECTOR';
        if(!$this->checker->checkPlay($data['play']))               return 'ERROR_PLAY';
        if(!$this->checker->checkText($data['cast'], false))        return 'ERROR_CAST';
        if(!$this->checker->checkText($data['description'], false)) return 'ERROR_DESCRIPTION';
        if(!$this->checker->checkTimeStamp($data['date']))          return 'UNKNOWN_ERROR';

        return true;
    }
}