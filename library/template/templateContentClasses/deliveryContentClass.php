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

class deliveryContentClass extends globalContentAbstractClass
{
    protected $title;
    protected $meta_key;
    protected $meta_desc;

    protected function getContent()
    {
        $this->title = 'Оплата и доставка';
        $this->meta_key = 'Оплата и доставка в интернет магазине';
        $this->meta_desc = 'оплата и доставка, оплата и доставка в интернет магазине';

        return 'delivery';
    }
}