<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 20.07.2017
 * Time: 7:35
 */

namespace admin\assets;

use admin\config\configClass;
use admin\helper\formatClass;

require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/library/helper/formatClass.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/library/config/configClass.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/library/helper/urlClass.php";

class paginationClass
{
    private $__format;
    private $__url;
    private $__config;

    public function __construct()
    {
        $this->__format = new formatClass();
        $this->__url = new \admin\helper\urlClass();
        $this->__config = new configClass();

    }

    public function getPagesData($rowsCount, $countOnPage)
    {
        $pagesCount = ceil($rowsCount/$countOnPage);
        $resultPages = array();

        for($i = 1; $i <= $pagesCount; $i++)
        {
            $resultPages[$i] = self::__getPageUrl($i);
        }

        return $resultPages;
    }

    private function __getPageUrl($pageNumber)
    {
        $pageGetData = array('page' => $pageNumber);
        $pageDeleteGet = array('page' => '');
        $url = $this->__url->getThisUrl();
        $url = $this->__url->deleteOrSetGet($url, $pageDeleteGet);
        $resultPageUrl = $this->__url->addGet($url, $pageGetData);

        return $resultPageUrl;
    }
}