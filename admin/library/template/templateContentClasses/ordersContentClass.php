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

class ordersContentClass extends pageFormAbstractClass
{
    protected $title = 'Заказы';
    protected $meta_desc = 'Страница с заказами';
    protected $meta_key = 'заказы,список заказов';

    protected function getFormData()
    {
        $formData['fields'] = array('delivery', 'product_ids', 'price', 'name', 'phone', 'email', 'address', 'notice', 'date_send', 'date_pay');
        $formData['func_add'] = 'add_order';
        $formData['func_edit'] = 'edit_order';
        $formData['title_add'] = 'Добавление заказа';
        $formData['title_edit'] = 'Редактирование заказа';
        $formData['get_data'] = isset($this->data['id']) ? $this->order->getOrderDataOnId($this->data['id']) : '';
        $formData['form_template'] = 'orders_form';
        $formData['template'] = 'orders';
        $formData['table_data'] = $this->order->getTableData(10, $this->getOffset());
        $formData['count_rows'] = $this->order->getRowsCount();



        if (isset($this->data['func']) && ($this->data['func'] == 'add'))
        {
            $productIds = isset($_SESSION['product_ids']) ? $_SESSION['product_ids'] : array();
        }
        elseif (isset($this->data['func']) && ($this->data['func'] == 'edit'))
        {
            $formData['all_products'] = $this->product->getAllTitlesAndIds();
			$this->template->setDataForReplace('admin_delete_position', $this->url->getLinkAdminDeleteOrderPosition($this->data['id']));
			$this->template->setDataForReplace('admin_add_position', $this->url->getLinkAdminAddOrderPosition($this->data['id']));
        }

        return $formData;
    }
}