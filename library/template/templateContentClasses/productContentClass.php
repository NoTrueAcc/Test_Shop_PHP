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

class productContentClass extends globalContentAbstractClass
{
    private $productInfo;

    protected function getContent()
    {
        $this->productInfo = $this->product->selectAllOnProductId($this->data['id']);

        if($this->productInfo == false)
        {
            $this->url->redirectNotFound();
        }

        $this->title = $this->productInfo[0]['title'];
        $this->meta_key = 'Описание и покупка фильма ' . $this->productInfo[0]['title'];
        $this->meta_desc = mb_strtolower('покупка фильма, купить фильм, описание фильм ' . $this->productInfo[0]['title']);

        $this->template->setDataForReplace('table_products_title', $this->productInfo[0]['title']);
        $this->template->setDataForReplace('section_name', $this->section->getSectionTitleOnId($this->productInfo[0]['section_id']));
        $this->template->setDataForReplace('link_section', $this->url->returnSectionUrl($this->productInfo[0]['section_id']));
        $this->template->setDataForReplace('products', $this->productInfo);
        $this->template->setDataForReplace('others', $this->product->getOtherProducts($this->productInfo[0]['section_id'], $this->productInfo[0]['id']));

        return 'product';
    }
}