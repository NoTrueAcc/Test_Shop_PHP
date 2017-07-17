<?php

/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 07.06.2017
 * Time: 6:41
 */

require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/library/config/configClass.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/library/helper/formatClass.php";

use admin\config\configClass;
use admin\helper\formatClass;

class manageClass
{
    private $config;
    private $format;
    private $data;

    public function __construct()
    {
        $this->config = new configClass();
        $this->format = new formatClass();
        $this->data = $this->format->checkDataFromXSS($_REQUEST);
    }

    public function getView()
    {
        return isset($this->data['view']) ? $this->data['view'] : 'index';
    }

}