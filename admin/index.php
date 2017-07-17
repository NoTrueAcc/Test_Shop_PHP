<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 20.05.2017
 * Time: 18:58
*/
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/library/helper/urlClass.php";

use admin\helper\urlClass;

$url = new urlClass();
$viewContent = $url->getContentClass();