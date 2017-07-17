<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 17.07.2017
 * Time: 6:32
 */
namespace admin\helper;

require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/library/config/configClass.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/library/helper/formatClass.php";

use admin\config\configClass;
class authClass
{
    private $config;
    private $format;

    public function __construct()
    {
      $this->config = new configClass();
      $this->format = new formatClass();
    }

    public function checkAuthUser($login, $password, $hash = true)
    {
        $login = mb_strtolower($login);
        if($hash)
        {
            $password = $this->format->hash($password);
        }

        return (($login === mb_strtolower($this->config->adminLogin)) && ($password === $this->config->adminPassHash));
    }
}