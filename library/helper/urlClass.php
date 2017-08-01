<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 22.05.2017
 * Time: 6:35
 */

namespace helper;

require_once $_SERVER['DOCUMENT_ROOT'] . "/library/config/configClass.php";

use config\configClass;

/**
 * Класс помошник для работы со ссылками
 *
 * Class urlClass
 * @package helper
 */
class urlClass
{
    protected $config;
    protected $amp;

    public function __construct($amp = true)
    {
        if(!isset($_SESSION))
        {
            session_start();
        }

        $this->config = new configClass();
        $this->amp = $amp;
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
        $uri = $_SERVER['REQUEST_URI'];
        $contentClassNameShort = preg_replace('/^\/([^\/^\?]*).*$/i',"$1" ,$uri);

        if(file_exists($_SERVER['DOCUMENT_ROOT'] . $this->config->templateContentClassesDir . $contentClassNameShort . "ContentClass.php"))
        {
        require_once $_SERVER['DOCUMENT_ROOT'] . $this->config->templateContentClassesDir . $contentClassNameShort . "ContentClass.php";

            $contentClassNameFull = "template\\templateContentClasses\\" . $contentClassNameShort . "ContentClass";

            return new $contentClassNameFull;
        }
        elseif(empty($contentClassNameShort))
        {
            require_once $_SERVER['DOCUMENT_ROOT'] . $this->config->templateContentClassesDir . "indexContentClass.php";

            $contentClassNameFull = "template\\templateContentClasses\\indexContentClass";

            return new $contentClassNameFull;
        }
        else
        {
            require_once $_SERVER['DOCUMENT_ROOT'] . $this->config->templateContentClassesDir . "notFoundContentClass.php";

            $contentClassNameFull = "template\\templateContentClasses\\notFoundContentClass";

            return new $contentClassNameFull;
        }
    }

    public function getLinkSort($sortBy, $up)
    {
        $thisUrl = $this->getThisUrl();
        $thisUrlParams = array('sort' => '', 'up' => '');
        $thisUrl = $this->deleteOrSetGet($thisUrl, $thisUrlParams);

        $thisUrlParams = array('sort' => "$sortBy", 'up' => "$up");
        $thisUrl = $this->addGet($thisUrl, $thisUrlParams);

        return $this->returnURL($thisUrl);
    }

    /**
     * устанавливает значение амперсанта
     */
    public function setAmp($amp)
    {
        $this->amp = $amp;
    }

    /**
     * @return адрес_местонахождения
     */
    public function getThisUrl()
    {
        $uri = substr($_SERVER['REQUEST_URI'], 1);
        $uri = preg_replace('/admin/i', '', $uri);

        return $this->config->address . $uri;
    }

    /**
     * Метод для удаления/изменения Get-параметров в ссылке
     *
     * @param ссылка
     * @param массив параметров на которые нужно изменить параметры в ссылке. Если параметр = '' - он будет удален
     * @return измененную ссылку
     */
    public function deleteOrSetGet($url, $params)
    {
        foreach ($params as $key => $value)
        {
            if(preg_match("/\?$key=.*?&/i", $url))
            {
                $replacement = (empty($value)) ? '' : ("$key=$value&");
                $url = preg_replace("/$key=.*?&/i", $replacement, $url);
            }
            elseif(preg_match("/&$key=.*?&/i", $url))
            {
                $replacement = (empty($value)) ? '' : "$key=$value&";
                $url = preg_replace("/$key=.*?&/i", $replacement, $url);
            }
            elseif(preg_match("/&$key=.*$/i", $url))
            {
                $replacement = (empty($value)) ? '' : ("&$key=$value");
                $url = preg_replace("/&$key=.*$/i", $replacement, $url);
            }
            else
            {
                $replacement = (empty($value)) ? '' : ("\?$key=$value");
                $url = preg_replace("/\?$key=.*$/i", $replacement, $url);
            }
        }

        return $url;
    }

    /**
     * Добавляет get параметры к адресу
     *
     * @param адрес
     * @param массив_параметров
     * @return адрес_с_get_параметрами
     */
    public function addGet($url, $params)
    {
        foreach ($params as $key => $value)
        {
            if(!preg_match('/\?+/', $url))
            {
                $url = preg_replace('/^(.*)$/', "$1?$key=$value", $url);
            }
            else
            {
                $url = preg_replace('/^(.*)$/', "$1&$key=$value", $url);
            }
        }

        return $url;
    }

    /**
     * Возвращает ссылку на элемент section
     *
     * @param $sectionId
     * @return bool|mixed|string
     */
    public function sectionDataElementLink($sectionId)
    {
        return $this->returnURL("section?id=$sectionId");
    }

    public function getAction()
    {
        return $this->returnURL("/functions.php");
    }

    /**
     * Возвращает ссылку на элемент product
     *
     * @param $productId
     * @return bool|mixed|string
     */
    public function productDataElementLink($productId)
    {
        return $this->returnURL("product?id=$productId");
    }

    /**
     * Добавляет товар в корзину
     *
     * @param айди элемента
     * @return ссылка_добавления_товара
     */
    public function addDataElementToCart($dataElementId)
    {
        return $this->returnURL("functions.php?func=add_to_cart&id=$dataElementId");
    }

    public function deleteDataElementFromCart($dataElementId)
    {
        return $this->returnURL("functions.php?func=delete_from_cart&id=$dataElementId");
    }

    /**
     * Редирект страницы по ссыдке
     *
     * @param ссылка
     */
    public function redirect($link)
    {
        header("Location: $link");
        exit();
    }

    public function redirectNotFound()
    {
        header("Location: " . $this->returnNotFoundUrl());
        exit();
    }

    public function redirectMessagePage()
    {
        return $this->redirect('message');
    }

    /**
     * Возвращает адрес несуществующей страницы
     *
     * @return bool|mixed|string
     */
    public function returnNotFoundUrl()
    {
        return $this->returnURL("notfound");
    }
    /**
     * Возвращает адрес главной страницы
     */
    public function returnIndexUrl()
    {
        return $this->returnURL("");
    }

    /**
     * Возвращает адрес страницы корзины
     */
    public function returnCartUrl()
    {
        return $this->returnURL("cart");
    }

    public function returnDeliveryUrl()
    {
        return $this->returnURL("delivery");
    }

    public function returnOrderUrl()
    {
        return $this->returnURL("order");
    }

    /**
     * Ссылка на раздел
     *
     * @param $sectionId
     * @return bool|mixed|string
     */
    public function returnSectionUrl($sectionId)
    {
        return $this->returnURL("section?id=$sectionId");
    }

    public function returnContactsUrl()
    {
        return $this->returnURL("contacts");
    }

    public function returnSearchUrl()
    {
        return $this->returnURL("search");
    }

    /**
     * Возвращает адрес сайта с URI
     * Если нужно заменить - заменяет амперсант
     * @param $url
     * @param bool $index
     * @return bool|mixed|string
     */
    protected function returnURL($url, $index = false)
    {
        if(!$index)
        {
            $index = $this->config->address;
        }

        if($url == "")
        {
            return $index;
        }

        if(strpos($url, $index) !== 0)
        {
            $url = $index . $url;
        }

        if($this->amp)
        {
            $url = str_replace('&', "&amp;", $url);
        }

        return $url;
    }
}