<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 22.05.2017
 * Time: 6:35
 */

namespace admin\helper;

require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/library/config/configClass.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/library/helper/urlClass.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/library/manageClass.php";

use admin\config\configClass;

/**
 * Класс помошник для работы со ссылками
 *
 * Class urlClass
 * @package helper
 */
class urlClass extends \helper\urlClass
{
    protected $config;
    protected $manage;

    public function __construct()
    {
        parent::__construct();
        $this->config = new configClass();
        $this->manage = new \manageClass();
    }

    /**
     * Возвращает класс для отрисовки страницы.
     * Если URI пустое - возвращает главную страницу.
     * Иначе выводит NotFound
     *
     * @return mixed
     */
    public function getContentClass()
    {
        $templateContentDir = "admin\\template\\templateContentClasses\\";
        $contentClassNameShort = $this->manage->getView();

        if(file_exists($_SERVER['DOCUMENT_ROOT'] . $this->config->templateContentClassesDir . $contentClassNameShort . "ContentClass.php"))
        {
        require_once $_SERVER['DOCUMENT_ROOT'] . $this->config->templateContentClassesDir . $contentClassNameShort . "ContentClass.php";

            $contentClassNameFull = $templateContentDir . $contentClassNameShort . "ContentClass";

            return new $contentClassNameFull;
        }
        elseif(empty($contentClassNameShort))
        {
            require_once $_SERVER['DOCUMENT_ROOT'] . $this->config->templateContentClassesDir . "indexContentClass.php";

            $contentClassNameFull = $templateContentDir . "indexContentClass";

            return new $contentClassNameFull;
        }
        else
        {
            require_once $_SERVER['DOCUMENT_ROOT'] . $this->config->templateContentClassesDir . "notFoundContentClass.php";

            $contentClassNameFull = $templateContentDir . "notFoundContentClass";

            return new $contentClassNameFull;
        }
    }

    public function returnIndexAdminUrl()
    {
        return $this->returnAdminURL();
    }

    public function returnProductsAdminUrl()
    {
        return $this->returnAdminURL('products');
    }

    public function returnOrdersAdminUrl()
    {
        return $this->returnAdminURL('orders');
    }

    public function returnSectionsAdminUrl()
    {
        return $this->returnAdminURL('sections');
    }

    public function returnDiscountsAdminUrl()
    {
        return $this->returnAdminURL('discounts');
    }

    public function returnStatisticsAdminUrl()
    {
        return $this->returnAdminURL('statistics');
    }

    public function returnLogoutAdminUrl()
    {
        return $this->returnAdminURL('logout');
    }

    private function returnAdminURL($url = "")
    {
        $url = !empty($url) ? substr($this->config->address, 0, -1) . "?view=$url" : $this->config->address;

        return $url;
    }
}