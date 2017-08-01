<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 24.07.2017
 * Time: 6:28
 */

namespace admin\template;

require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/library/template/globalContentAbstractClass.php';

abstract class pageFormAbstractClass extends globalContentAbstractClass
{
    protected function getContent()
    {
        $formData = $this->getFormData();
        $form = false;
        $fieldsDataList = array();
        $func = isset($this->data['func']) ? $this->data['func'] : '';

        if($func == 'add')
        {
            $form = true;

            for($i = 0; $i < count($formData['fields']); $i++)
            {
                $fieldsDataList[$formData['fields'][$i]] = isset($_SESSION[$formData['fields'][$i]]) ? $_SESSION[$formData['fields'][$i]] : '';
            }

            $this->template->setDataForReplace('func', $formData['func_add']);
            $this->template->setDataForReplace('form_title', $formData['title_add']);
        }
        elseif ($func == 'edit')
        {
            $form = true;

            if(!($editedData = $formData['product_data'][0]))
            {
             $this->url->redirectAdminNotFound();
            }


            $fieldsDataList = array();
            $formData['id'] = $this->data['id'];

            for($i = 0; $i < count($formData['fields']); $i++)
            {
                $fieldsDataList[$formData['fields'][$i]] = $editedData[$formData['fields'][$i]];
            }

            $this->template->setDataForReplace('func', $formData['func_edit']);
            $this->template->setDataForReplace('id', $formData['id']);
            $this->template->setDataForReplace('title_edit', $formData['title_edit']);
        }

        if($form)
        {
            foreach ($fieldsDataList as $field => $data)
            {
                $this->template->setDataForReplace($field, $data);
            }

            return $formData['form_template'];
        }
        else
        {
            $tableData = $formData['table_data'];

            $this->template->setDataForReplace('link_add', $this->url->getFuncLink('add'));
            $this->template->setDataForReplace('', $this->url->getFuncLink('add'));
            $this->template->setDataForReplace('table_data', $tableData);
            $this->template->setDataForReplace('paginator', $this->paginator->getPagesData($formData['count_rows'], 10));

            return $formData['template'];
        }


    }

    abstract protected function getFormData();
}