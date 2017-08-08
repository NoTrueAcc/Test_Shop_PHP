<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/library/manageClass.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/library/helper/urlClass.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/library/helper/authClass.php";

$manage = new \manageClass();
$url = new admin\helper\urlClass();
$auth = new \admin\helper\authClass();

$func = isset($_REQUEST['func']) ? $_REQUEST['func'] : '';

switch ($func) {
    case 'admin_auth' :
        $link = $manage->adminLogin();
        break;
    case 'logout' :
        $manage->adminLogout();
        break;
    default : break;
    case 'add_product' :
        $manage->adminAddProduct();
        break;
    case 'edit_product' :
        $manage->adminEditProduct();
        break;
    case 'delete_product' :
        $manage->adminDeleteProduct();
        break;
    case 'add_section' :
        $manage->adminAddSection();
        break;
    case 'edit_section' :
        $manage->adminEditSection();
        break;
    case 'delete_section' :
        $manage->adminDeleteSection();
        break;
	case 'delete_order_position' :
        $manage->adminDeleteOrderPosition();
        break;
}

    if(!isset($link))
    {
        $link = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : $url->returnIndexAdminUrl();
    }

    header("Location: $link");

exit();