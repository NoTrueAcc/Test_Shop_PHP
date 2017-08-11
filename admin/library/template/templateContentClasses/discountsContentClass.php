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

class discountsContentClass extends pageFormAbstractClass
{
    protected $title = 'Купоны';
    protected $meta_desc = 'Страница с купонами';
    protected $meta_key = 'купоны,список купонов';

    protected function getFormData()
    {
        $formData['fields'] = array('code', 'value');
        $formData['func_add'] = 'add_discount';
        $formData['func_edit'] = 'edit_discount';
        $formData['title_add'] = 'Добавление купона';
        $formData['title_edit'] = 'Редактирование купона';
        $formData['get_data'] = isset($this->data['id']) ? $this->discount->getDiscountDataOnId($this->data['id']) : '';
        $formData['form_template'] = 'discounts_form';
        $formData['template'] = 'discounts';
        $formData['table_data'] = $this->discount->getTableData(10, $this->getOffset());
        $formData['count_rows'] = $this->discount->getRowsCount();

        return $formData;
    }
}