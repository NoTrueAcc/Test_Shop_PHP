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

use admin\config\configClass;
use admin\helper\formatClass;
use admin\helper\authClass;
use admin\messages;
use admin\helper\urlClass;

class manageClass
{
    private $config;
    private $format;
    private $data;
    private $url;
    private $systemMessage;

    public function __construct()
    {
        $this->config = new configClass();
        $this->format = new formatClass();
        $this->systemMessage = new messages\messageClass();
        $this->data = $this->format->checkDataFromXSS($_REQUEST);
    }

    public function getView()
    {
        return isset($this->data['view']) ? $this->data['view'] : 'index';
    }

    public function adminLogin()
    {
        $url = new urlClass();
        $auth = new authClass();

        $_SESSION['login'] = isset($this->data['login']) ? $this->data['login'] : '';
        $_SESSION['password'] = isset($this->data['password']) ? $this->data['password'] : '';

        if($auth->checkAdminAuth($_SESSION['login'], $_SESSION['password']))
        {
            return $url->returnIndexAdminUrl();
        }
        else
        {
            $_SESSION['message'] = 'ERROR_ADMIN_AUTH';

            return $url->returnAuthAdminUrl();
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
}