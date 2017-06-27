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

class searchContentClass extends globalContentAbstractClass
{
    public $title;
    public $meta_desc;
    public $meta_key;

    protected function getContent()
    {
        $searchText = !empty($this->data['search_text']) ? $this->data['search_text'] : '';
        $this->title = "Поиск: $searchText";
        $this->meta_desc = "Поиск: $searchText";
        $this->meta_key = preg_replace('/\s+/i', ', ', mb_strtolower($searchText));
        $order = isset($this->data['sort']) ? $this->data['sort'] : '';
        $desc = isset($this->data['up']) ? $this->data['up'] : '';
        $searchProductsDataList = $this->product->getSearchProductsData($searchText, $order, $desc) ? $this->product->getSearchProductsData($searchText, $order, $desc) : array();

        $this->template->setDataForReplace('table_products_title', 'Поиск');
        $this->template->setDataForReplace('link_price_up', $this->url->getLinkSort('price', 'up'));
        $this->template->setDataForReplace('link_price_down', $this->url->getLinkSort('price', 'down'));
        $this->template->setDataForReplace('link_title_up', $this->url->getLinkSort('title', 'up'));
        $this->template->setDataForReplace('link_title_down', $this->url->getLinkSort('title', 'down'));
        $this->template->setDataForReplace('search_text', $searchText);
        $this->template->setDataForReplace('products', $searchProductsDataList);

        return 'search';
    }
}