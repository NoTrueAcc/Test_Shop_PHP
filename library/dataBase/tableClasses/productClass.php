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