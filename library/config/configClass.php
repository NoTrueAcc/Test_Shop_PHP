<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 19.05.2017
 * Time: 17:10
 */

namespace config;

/**
 * Class configClass
 *
 * Класс содержит конфиги админа, подключения к БД и другие
 * @package config\configClass
 */

class configClass
{
    /**
     * Адрес сайта и имя сайта
     */
    public $siteName = "alexTest.local";
    public $address = "http://alextest.local/";

    /**
     * Конфиги подключения к БД
     */
    public $dataBaseHost = "localhost";
    public $dataBaseLogin = "root";
    public $dataBasePassword = "root";
    public $dataBaseSchema = "shopdvd-local";
    public $dataBasePrefix = "sdvd_";
    public $symQuery = "{?}";
    public $productLimitDataOnPage = 8;

    /**
     * Конфиги админа
     */
    public $adminName = "noTrueAcc";
    public $adminEmail = "notrueacc@mail.ru";

    /**
     * Конфиги сообщений
     */
    public $messagesTextDir = "/messagesText/";

    /**
     * Директории
     */
    public $helperDir = "/library/helper/";
    public $dataBaseDir = "/library/dataBase/";
    public $messagesDir = "/library/messages/";
    public $templateDir = "/library/template/";
    public $templateContentClassesDir = "/library/template/templateContentClasses/";
    public $productImagesDir = "/images/products/";
    public $templatesPhtmlDir = "/templates/";
}