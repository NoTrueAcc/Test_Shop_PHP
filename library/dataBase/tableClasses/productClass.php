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

    public function selectAllOnSectionId($sectionIdValue)
    {
        return $this->selectAllOnField('section_id', $sectionIdValue);
    }

    public function getAllData($limit)
    {
        return $this->transformData($this->selectAll('date', true, $limit));
    }

    protected function transformElement($productDataElement)
    {
        $productDataElement['img'] = $this->config->productImagesDir . $productDataElement['img'];
        $productDataElement['link'] = $this->url->productDataElementLink($productDataElement['id']);
        $productDataElement['link_cart'] = $this->url->addDataElementToCart($productDataElement['id']);

        return $productDataElement;
    }
}