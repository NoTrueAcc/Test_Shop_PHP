<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 27.05.2017
 * Time: 7:32
 */

namespace admin\template;

require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/library/config/configClass.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/library/helper/urlClass.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/library/helper/formatClass.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/library/template/templateClass.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/library/dataBase/tableClasses/sectionClass.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/library/dataBase/tableClasses/productClass.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/library/dataBase/tableClasses/discountClass.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/library/messages/messageClass.php";

use admin\config\configClass;
use admin\database\tableClasses\discountClass;
use admin\database\tableClasses\sectionClass;
use admin\database\tableClasses\productClass;
use admin\helper\urlClass;
use admin\helper\formatClass;
use admin\messages\messageClass;

abstract class globalContentAbstractClass
{
    protected $config;
    protected $url;
    protected $data;
    protected $format;
    protected $template;
    protected $section;
    protected $product;
    protected $discount;
    protected $message;

    public function __construct()
    {
        session_start();
        $this->config = new configClass();
        $this->url = new urlClass();
        $this->format = new formatClass();
        $this->section = new sectionClass();
        $this->product = new productClass();
        $this->discount = new discountClass();
        $this->data = $this->format->checkDataFromXSS($_REQUEST);
        $this->template = new templateClass($_SERVER['DOCUMENT_ROOT'] . $this->config->templatesPhtmlDir);
        $this->message = new messageClass();

        $this->__setMenuTemplateDataForReplace();
        $this->template->setDataForReplace("content", $this->getContent());
        $this->template->setDataForReplace('title', $this->title);
        $this->template->setDataForReplace('meta_desc', $this->meta_desc);
        $this->template->setDataForReplace('title', $this->meta_key);

        $this->template->display("main");
    }

    abstract protected function getContent();

    private function __setMenuTemplateDataForReplace()
    {
        $this->template->setDataForReplace('index', $this->url->returnIndexAdminUrl());
        $this->template->setDataForReplace('link_products', $this->url->returnProductsAdminUrl());
        $this->template->setDataForReplace('link_orders', $this->url->returnOrdersAdminUrl());
        $this->template->setDataForReplace('link_sections', $this->url->returnSectionsAdminUrl());
        $this->template->setDataForReplace('link_discounts', $this->url->returnDiscountsAdminUrl());
        $this->template->setDataForReplace('link_statistics', $this->url->returnStatisticsAdminUrl());
        $this->template->setDataForReplace('logout', $this->url->returnLogoutAdminUrl());
    }

    protected function getMessage()
    {
        if(!isset($_SESSION['message']) || empty($_SESSION['message']))
        {
            return '';
        }
        else
        {
            $messageText = $this->message->getMessageData($_SESSION['message']);
            unset($_SESSION['message']);

            return $messageText;
        }
    }
}