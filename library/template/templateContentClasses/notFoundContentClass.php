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

class notFoundContentClass extends globalContentAbstractClass
{
    protected $title;
    protected $meta_key;
    protected $meta_desc;

    protected function getContent()
    {
        header("HTTP/1.0 404 Not Found");

        $this->title = 'Страница не найдена';
        $this->meta_key = '404 Страница не найдена';
        $this->meta_desc = 'страница не найдена, страница не найдена 404, страницы не существует';

        return "notfound";
    }
}