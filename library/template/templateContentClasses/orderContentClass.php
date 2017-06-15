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

class orderContentClass extends globalContentAbstractClass
{
    protected $title = 'Ваш заказ';
    protected $meta_key = 'Оформление заказа';
    protected $meta_desc = 'заказ, оформление заказа';

    protected function getContent()
    {
        $this->template->setDataForReplace('name', (isset($_SESSION['name']) ? $_SESSION['name'] : ''));
        $this->template->setDataForReplace('name', (isset($_SESSION['phone']) ? $_SESSION['phone'] : ''));
        $this->template->setDataForReplace('name', (isset($_SESSION['email']) ? $_SESSION['email'] : ''));
        $this->template->setDataForReplace('name', (isset($_SESSION['delivery']) ? $_SESSION['delivery'] : ''));
        $this->template->setDataForReplace('name', (isset($_SESSION['address']) ? $_SESSION['address'] : ''));
        $this->template->setDataForReplace('name', (isset($_SESSION['notice']) ? $_SESSION['notice'] : ''));

        return 'order';
    }
}