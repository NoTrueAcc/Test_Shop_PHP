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

class sectionContentClass extends globalContentAbstractClass
{
    private $sectionInfo;

    protected function getContent()
    {
        $sortColumn = isset($this->data['sort']) ? $this->data['sort'] : '';
        $desc = isset($this->data['up']) ? $this->data['up'] : '';
        $this->sectionInfo = $this->section->getSectionDataOnId($this->data['id']);
        $this->title = $this->sectionInfo[0]['title'];
        $this->meta_key = 'Список фильмов из раздела ' . $this->sectionInfo[0]['title'];
        $this->meta_desc = mb_strtolower('список фильмов, списокфильмов жанр, список фильмо жанр ' . $this->sectionInfo[0]['title']);

        $this->template->setDataForReplace('table_products_title', 'Новинки');
        $this->template->setDataForReplace('link_price_up', $this->url->getLinkSort('price', 'up'));
        $this->template->setDataForReplace('link_price_down', $this->url->getLinkSort('price', 'down'));
        $this->template->setDataForReplace('link_title_up', $this->url->getLinkSort('title', 'up'));
        $this->template->setDataForReplace('link_title_down', $this->url->getLinkSort('title', 'down'));
        $this->template->setDataForReplace('table_products_title', $this->sectionInfo[0]['title']);
        $this->template->setDataForReplace('products', $this->product->getAllSortDataOnSectionId($this->sectionInfo[0]['id'], $sortColumn, $desc));

        return 'index';
    }
}