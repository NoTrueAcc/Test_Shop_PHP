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

use config\configClass;
use helper\formatClass;
use database\tableClasses\productClass;

class manageClass
{
    private $config;
    private $format;
    private $data;
    private $product;

    public function __construct()
    {
        session_start();

        $this->config = new configClass();
        $this->format = new formatClass();
        $this->product = new productClass();
        $this->data = $this->format->checkDataFromXSS($_REQUEST);
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
}