<?php

/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 07.06.2017
 * Time: 6:41
 */

require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/library/config/configClass.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/library/helper/formatClass.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/library/messages/messageClass.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/library/helper/urlClass.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/library/helper/checkerClass.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/library/dataBase/tableClasses/productClass.php";

use admin\config\configClass;
use admin\helper\formatClass;
use admin\helper\authClass;
use admin\messages;
use admin\helper\urlClass;
use admin\helper\checkerClass;

class manageClass
{
    private $config;
    private $format;
    private $data;
    private $systemMessage;
    private $__checker;
    private $__product;
    private $__url;

    public function __construct()
    {
        $this->config = new configClass();
        $this->format = new formatClass();
        $this->systemMessage = new messages\systemMessageClass();
        $this->data = $this->format->checkDataFromXSS($_REQUEST);
        $this->__checker = new checkerClass();
        $this->__product = new \admin\database\tableClasses\productClass();
        $this->__url = new urlClass();
    }

    public function adminLogin()
    {
        $auth = new authClass();

        $_SESSION['login'] = isset($this->data['login']) ? $this->data['login'] : '';
        $_SESSION['password'] = isset($this->data['password']) ? $this->data['password'] : '';

        if($auth->checkAdminAuth($_SESSION['login'], $_SESSION['password']))
        {
            return $this->__url->returnIndexAdminUrl();
        }
        else
        {
            $_SESSION['message'] = 'ERROR_ADMIN_AUTH';

            return $this->__url->returnAuthAdminUrl();
        }
    }

    public function adminLogout()
    {
        if(isset($_SESSION['login']) && isset($_SESSION['password']))
        {
            unset($_SESSION['login']);
            unset($_SESSION['password']);
        }
    }

    public function adminAddProduct()
    {
        $this->__setFormSessionData($this->data);

        $tempProductData = $this->dataProduct();
        $tempProductData['date'] = $this->format->getTimeStamp();
        $img = $this->__loadImage();
        $tempProductData['img'] = $img;

        if(!$img)
        {
            return false;
        }

        if($this->__product->insertData($tempProductData))
        {
            $this->__unsetFormSessionData($this->data);
            $this->systemMessage->getMessage('SUCCESS_ADD_PRODUCT');

            return $this->__url->redirectAdminProducts();
        }

        return false;
    }

    public function adminEditProduct()
    {
        $tempProductData = $this->dataProduct();
        $tempProductData['date'] = $this->__product->getDate($this->data['id']);

        $img = $_FILES['img'];
        $oldImg = $this->__product->getImg($this->data['id']);

        if(!$img['name'])
        {
            $tempProductData['img'] = $oldImg;
        }
        else
        {
            if($this->__product->getCountProductsOnImageName($oldImg) < 2)
            {
                unlink($_SERVER['DOCUMENT_ROOT'] . $this->config->productImagesDir . $oldImg);
            }
            $img = $this->__loadImage();

            if(!$img)
            {
                return false;
            }

            $tempProductData['img'] = $img;
        }

        if($this->__product->updateData($this->data['id'], $tempProductData))
        {
            $this->systemMessage->getMessage('SUCCESS_EDIT_PRODUCT');

            return $this->__url->redirectAdminProducts();
        }

        return false;
    }

    public function adminDeleteProduct()
    {
        $imgName = $this->__product->getImg($this->data['id']);

        if($this->__product->deleteData($this->data['id']))
        {
            $this->systemMessage->getMessage('SUCCESS_DELETE_PRODUCT');
        }

        if($this->__product->getCountProductsOnImageName($imgName) < 1)
        {
            unlink($_SERVER['DOCUMENT_ROOT'] . $this->config->productImagesDir . $imgName);
        }
    }

    private function __loadImage()
    {
        $img = $_FILES['img'];

        if(!$img['name'])
        {
            return $this->systemMessage->getMessage('ERROR_IMAGE_NAME');
        }

        if(!$this->__checker->checkImage($img))
        {
            return false;
        }

        $uploadFile = $_SERVER['DOCUMENT_ROOT'] . $this->config->productImagesDir . $img['name'];

        return move_uploaded_file($img['tmp_name'], $uploadFile) ? $img['name'] : $this->systemMessage->getUnknownError();
    }

    public function dataProduct()
    {
        $tempProductData = array();
        $tempProductData['section_id'] = isset($this->data['section_id']) ? $this->data['section_id'] : '';
        $tempProductData['title'] = isset($this->data['prod_title']) ? $this->data['prod_title'] : '';
        $tempProductData['price'] = isset($this->data['price']) ? $this->data['price'] : '';
        $tempProductData['year'] = isset($this->data['year']) ? $this->data['year'] : '';
        $tempProductData['country'] = isset($this->data['country']) ? $this->data['country'] : '';
        $tempProductData['director'] = isset($this->data['director']) ? $this->data['director'] : '';
        $tempProductData['cast'] = isset($this->data['cast']) ? $this->data['cast'] : '';
        $tempProductData['play'] = isset($this->data['play']) ? $this->data['play'] : '';
        $tempProductData['description'] = isset($this->data['description']) ? $this->data['description'] : '';

        return $tempProductData;
    }

    private function __setFormSessionData($formData)
    {
        foreach ($formData as $fieldName => $data)
        {
            $_SESSION[$fieldName] = $data;
        }
    }

    private function __unsetFormSessionData($formData)
    {
        foreach ($formData as $fieldName => $data)
        {
            unset($_SESSION[$fieldName]);
        }
    }
}