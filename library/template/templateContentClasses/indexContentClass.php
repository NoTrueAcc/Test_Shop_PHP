<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 29.05.2017
 * Time: 6:58
 */

namespace template\templateContentClasses;

require_once $_SERVER['DOCUMENT_ROOT'] . '/library/template/globalContentAbstractClass.php';

use template\globalContentAbstractClass;

class indexContentClass extends globalContentAbstractClass
{
    protected $title = 'Интернет-магазин';
    protected $meta_key = 'Интернет-магазин по продаже DVD-дисков';
    protected $meta_desc = 'интернет магазин, интернет магазин dvd, интернет магазин dvd диски';

    protected function getContent()
    {
        $sortColumn = isset($this->data['sort']) ? $this->data['sort'] : '';
        $desc = isset($this->data['up']) ? $this->data['up'] : '';

        $this->template->setDataForReplace('table_products_title', 'Новинки');
        $this->template->setDataForReplace('link_price_up', $this->url->getLinkSort('price', 'up'));
        $this->template->setDataForReplace('link_price_down', $this->url->getLinkSort('price', 'down'));
        $this->template->setDataForReplace('link_title_up', $this->url->getLinkSort('title', 'up'));
        $this->template->setDataForReplace('link_title_down', $this->url->getLinkSort('title', 'down'));
        $this->template->setDataForReplace('table_products_title', 'Новинки');
        $this->template->setDataForReplace('products', $this->product->getAllSortData($sortColumn, $desc, $this->config->productLimitDataOnPage));

        return 'index';
    }
}