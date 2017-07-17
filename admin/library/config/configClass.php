<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 19.05.2017
 * Time: 17:10
 */

namespace admin\config;

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
    public $address = "http://alextest.local/admin/";

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
    public $othersLimit = 5;
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
    public $helperDir = "/admin/library/helper/";
    public $dataBaseDir = "/admin/library/dataBase/";
    public $messagesDir = "/admin/library/messages/";
    public $templateDir = "/admin/library/template/";
    public $templateContentClassesDir = "/admin/library/template/templateContentClasses/";
    public $productImagesDir = "/admin/images/products/";
    public $templatesPhtmlDir = "/admin/templates/";

    /**
     * Конфиги для функций
     */
    public $nameMinLength = 1;
    public $nameMaxLength = 255;
    public $phoneMinLength = 3;
    public $phoneMaxLength = 20;
    public $emailMinLength = 4;
    public $emailMaxLength = 50;
    public $textMinLength = 3;
    public $textMaxLength = 65535;

    /**
     * Для админки
     */
    public $secretWord = 'asdadasdDASdaa';
    public $adminLogin = 'admin';
    public $adminPassHash = '';
}