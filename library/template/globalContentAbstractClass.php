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
require_once $_SERVER['DOCUMENT_ROOT'] . "/library/dataBase/tableClasses/discountClass.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/library/messages/messageClass.php";

use config\configClass;
use database\tableClasses\discountClass;
use database\tableClasses\sectionClass;
use database\tableClasses\productClass;
use helper\urlClass;
use helper\formatClass;
use messages\messageClass;

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
        $this->config = new configClass();
        $this->url = new urlClass();
        $this->format = new formatClass();
        $this->section = new sectionClass();
        $this->product = new productClass();
        $this->discount = new discountClass();
        $this->data = $this->format->checkDataFromXSS($_REQUEST);
        $this->template = new templateClass($_SERVER['DOCUMENT_ROOT'] . $this->config->templatesPhtmlDir);
        $this->message = new messageClass();

        $this->setInfoCart();
        $this->template->setDataForReplace("content", $this->getContent());
        $this->template->setDataForReplace("title", $this->title);
        $this->template->setDataForReplace("meta_desc", $this->meta_desc);
        $this->template->setDataForReplace("meta_key", $this->meta_key);
        $this->template->setDataForReplace("items", $this->section->getAllData());
        $this->template->setDataForReplace("link_index", $this->url->returnIndexUrl());
        $this->template->setDataForReplace("link_cart", $this->url->returnCartUrl());
        $this->template->setDataForReplace("link_delivery", $this->url->returnDeliveryUrl());
        $this->template->setDataForReplace('action', $this->url->getAction());
        $this->template->setDataForReplace("link_contacts", $this->url->returnContactsUrl());
        $this->template->setDataForReplace("link_search", $this->url->returnSearchUrl());
        $this->template->display("main");
    }

    private function setInfoCart()
    {
        $dataListCartIds = !isset($_SESSION['cart']) ? array() : explode(',', $_SESSION['cart']);
        $dataListCartIdsCount = count($dataListCartIds);
        $dataListCartIdsSumma = $this->product->getCartPriceOnIds($dataListCartIds);
        $this->template->setDataForReplace('cart_count', $dataListCartIdsCount);
        $this->template->setDataForReplace('cart_summa', $dataListCartIdsSumma);
        $this->template->setDataForReplace('cart_products_count_word', $this->__getProductsCountWord($dataListCartIdsCount));

    }

    abstract protected function getContent();

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

    private function __getProductsCountWord($productsCount)
    {
        $productsCountWords = array('товар', 'товара', 'товаров');

        if((10 < $productsCount) && ($productsCount < 15))
        {
            return $productsCountWords[2];
        }

        if(preg_match('/^(.*)?[056789]$/', $productsCount))
        {
            return $productsCountWords[2];
        }
        elseif(preg_match('/^(.*)?[234]$/', $productsCount))
        {
            return $productsCountWords[1];
        }
        elseif(preg_match('/^(.*)?[1]$/', $productsCount))
        {
            return $productsCountWords[0];
        }
    }
}