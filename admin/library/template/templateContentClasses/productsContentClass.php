<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 25.07.2017
 * Time: 6:11
 */

namespace admin\template\templateContentClasses;

require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/library/template/pageFormAbstractClass.php';

use admin\template\pageFormAbstractClass;

class productsContentClass extends pageFormAbstractClass
{
    protected $title = 'Товары';
    protected $meta_desc = 'Страница с товарами';
    protected $meta_key = 'товары,список товаров';

    protected function getFormData()
    {
        $this->template->setDataForReplace('sections', $this->section->getAllData());
        $formData['fields'] = array('section_id', 'prod_title', 'price', 'year', 'country', 'director', 'play', 'cast', 'description');
        $formData['func_add'] = 'add_product';
        $formData['func_edit'] = 'edit_product';
        $formData['title_add'] = 'Добавление товара';
        $formData['title_edit'] = 'Редактирование товара';
        $formData['product_data'] = isset($this->data['id']) ? $this->product->getAllProductDataOnId($this->data['id'], $this->section->getTableName()) : '';
        $formData['form_template'] = 'products_form';
        $formData['template'] = 'products';
        $formData['table_data'] = $this->product->getTableData($this->section->getTableName(), 10, false);
        $formData['count_rows'] = $this->product->getProductsCount();

        return $formData;
    }
}