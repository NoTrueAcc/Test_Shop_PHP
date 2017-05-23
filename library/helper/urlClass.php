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
    private $config;
    private $amp;

    public function __construct($amp = true)
    {
        $this->config = new configClass();
        $this->amp = $amp;
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
     * Возвращает адрес сайта
     */
    public function returnIndexUrl()
    {
        return $this->returnURL("");
    }

    /**
     * Возвращает адрес главной страницы
     */
    public function returnCartUrl()
    {
        return $this->returnURL("cart");
    }

    /**
     * Возвращает адрес сайта с URI
     * Если нужно заменить - заменяет амперсант
     * @param $url
     * @param bool $index
     * @return bool|mixed|string
     */
    private function returnURL($url, $index = false)
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