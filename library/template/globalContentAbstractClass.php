<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 27.05.2017
 * Time: 7:32
 */

namespace template;

require_once $_SERVER['DOCUMENT_ROOT'] . "/library/config/configClass.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/library/helper/urlClass.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/library/helper/formatClass.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/library/template/templateClass.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/library/dataBase/tableClasses/sectionClass.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/library/dataBase/tableClasses/productClass.php";

use config\configClass;
use database\tableClasses\sectionClass;
use database\tableClasses\productClass;
use helper\urlClass;
use helper\formatClass;

abstract class globalContentAbstractClass
{
    protected $config;
    protected $url;
    protected $data;
    protected $format;
    protected $template;
    protected $section;
    protected $product;

    public function __construct()
    {
        session_start();
        $this->config = new configClass();
        $this->url = new urlClass();
        $this->format = new formatClass();
        $this->section = new sectionClass();
        $this->product = new productClass();
        $this->data = $this->format->checkDataFromXSS($_REQUEST);
        $this->template = new templateClass($_SERVER['DOCUMENT_ROOT'] . $this->config->templatesPhtmlDir);

        $this->template->setDataForReplace("content", $this->getContent());
        $this->template->setDataForReplace("title", $this->title);
        $this->template->setDataForReplace("meta_desc", $this->meta_desc);
        $this->template->setDataForReplace("meta_key", $this->meta_key);
        $this->template->setDataForReplace("items", $this->section->getAllData());
        $this->template->setDataForReplace("link_index", $this->url->returnIndexUrl());
        $this->template->setDataForReplace("link_cart", $this->url->returnCartUrl());
        $this->template->setDataForReplace("link_delivery", $this->url->returnDeliveryUrl());
        $this->template->setDataForReplace("link_contacts", $this->url->returnContactsUrl());
        $this->template->setDataForReplace("link_search", $this->url->returnSearchUrl());
        $this->template->display("main");
    }

    abstract protected function getContent();
}