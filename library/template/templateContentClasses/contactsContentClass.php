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

class contactsContentClass extends globalContentAbstractClass
{
    protected $title;
    protected $meta_key;
    protected $meta_desc;

    protected function getContent()
    {
        $this->title = 'Контакты';
        $this->meta_key = 'Контакты в интернет магазине';
        $this->meta_desc = 'наши контакты, контакты в интернет магазине';

        return 'contacts';
    }
}