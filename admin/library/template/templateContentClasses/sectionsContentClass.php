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

class sectionsContentClass extends pageFormAbstractClass
{
    protected $title = 'Разделы';
    protected $meta_desc = 'Страница с раззделами';
    protected $meta_key = 'разделы,список разделов';

    protected function getFormData()
    {
        $formData['fields'] = array('section_title');
        $formData['func_add'] = 'add_section';
        $formData['func_edit'] = 'edit_section';
        $formData['title_add'] = 'Добавление секции';
        $formData['title_edit'] = 'Редактирование секции';
        $formData['get_data'] = isset($this->data['id']) ? $this->section->getSectionDataOnId($this->data['id']) : '';
        $formData['form_template'] = 'sections_form';
        $formData['template'] = 'sections';
        $formData['table_data'] = $this->section->getTableData(10, $this->getOffset());
        $formData['count_rows'] = $this->section->getRowsCount();

        return $formData;
    }
}