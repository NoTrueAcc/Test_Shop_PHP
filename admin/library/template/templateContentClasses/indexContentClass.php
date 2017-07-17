<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 29.05.2017
 * Time: 6:58
 */

namespace admin\template\templateContentClasses;

require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/library/template/globalContentAbstractClass.php';

use admin\template\globalContentAbstractClass;

class indexContentClass extends globalContentAbstractClass
{
    protected $title = 'Страница администратора';
    protected $meta_key = 'Управление интернет-магазином';
    protected $meta_desc = 'интернет магазин, интернет магазин управление, интернет магазин панель администратора';

    protected function getContent()
    {
        return 'index';
    }
}