<?php

/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 07.06.2017
 * Time: 6:41
 */

require_once $_SERVER['DOCUMENT_ROOT'] . "/library/config/configClass.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/library/helper/formatClass.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/library/dataBase/tableClasses/productClass.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/library/dataBase/tableClasses/discountClass.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/library/dataBase/tableClasses/orderClass.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/library/messages/systemMessageClass.php";

use config\configClass;
use helper\formatClass;
use database\tableClasses\productClass;
use database\tableClasses\discountClass;
use database\tableClasses\orderClass;
use messages\systemMessageClass;

class manageClass
{
    private $config;
    private $format;
    private $data;
    private $product;
    private $discount;
    private $order;
    private $systemMessage;

    public function __construct()
    {
        session_start();

        $this->config = new configClass();
        $this->format = new formatClass();
        $this->product = new productClass();
        $this->discount = new discountClass();
        $this->order = new orderClass();
        $this->systemMessage = new systemMessageClass();
        $this->data = $this->format->checkDataFromXSS($_REQUEST);
        $this->saveData();
    }

    private function saveData()
    {
        foreach ($this->data as $key => $value)
        {
            $_SESSION[$key] = $value;
        }
    }


    public function addToCart($id = false)
    {
        if($id === false)
        {
            $id = isset($this->data['id']) ? $this->data['id'] : '';
        }

        if(!$this->product->isExistsId($id))
        {
            return false;
        }

        $_SESSION['cart'] = isset($_SESSION['cart']) ? $_SESSION['cart'] . ",$id" : "$id";
    }

    public function deleteFromCart()
    {
        $id = isset($this->data['id']) ? $this->data['id'] : '';
        $sessionIdsList = explode(',', $_SESSION['cart']);
        unset($_SESSION['cart']);

        for($i = 0; $i < count($sessionIdsList); $i++)
        {
            if($sessionIdsList[$i] != $id)
            {
                $this->addToCart($sessionIdsList[$i]);
            }
        }
    }

    public function updateCartData()
    {
        if(empty($_SESSION['cart']))
        {
            return false;
        }

       unset($_SESSION['cart']);

       foreach ($this->data as $key => $value)
       {
           if(preg_match('/^count_[0-9]*$/i', $key))
           {
               $id = substr($key, strlen('count_'));

               for($i = 0; $i < $value; $i++)
               {
                   $this->addToCart($id);
               }
           }
       }

       $_SESSION['discount'] = (isset($this->data['discount']) && !empty($this->data['discount'])) ? $this->data['discount'] : '';
    }

    public function addOrder()
    {
        $tempData = array();
        $tempData['delivery'] = $this->data['delivery'];
        $tempData['product_ids'] = $_SESSION['cart'];
        $tempData['price'] = $this->getPrice();
        $tempData['name'] = $this->data['name'];
        $tempData['phone'] = $this->data['phone'];
        $tempData['email'] = $this->data['email'];
        $tempData['address'] = $this->data['address'];
        $tempData['notice'] = $this->data['notice'];
        $tempData['date_order'] = $this->format->getTimeStamp();
        $tempData['date_send'] = 0;
        $tempData['date_pay'] = 0;

        $success = $this->order->insertData($tempData);

        if($success)
        {
            $_SESSION = array();

            return $this->systemMessage->getPageMessage('ADD_ORDER');
        }
        else
        {
            return false;
        }
    }

    private function getPrice()
    {
        $ids = isset($_SESSION['cart']) ? explode(',', $_SESSION['cart']) : array();
        $price = $this->product->getCartPriceOnIds($ids);
        $discount = isset($_SESSION['discount']) ? $this->discount->getDiscountOnCode($_SESSION['discount']) : 0;

        return $price * (1 - $discount);
    }
}