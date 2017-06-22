<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/library/manageClass.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/library/helper/urlClass.php";

$manage = new manageClass();
$url = new \helper\urlClass();
$func = isset($_REQUEST['func']) ? $_REQUEST['func'] : '';

switch ($func) {
    case 'add_to_cart' :
        $manage->addToCart();
        break;
    case 'delete_from_cart' :
        $manage->deleteFromCart();
        break;
    case 'cart_data' :
        $manage->updateCartData();
        break;
    case 'order' :
        $successAddOrder = $manage->addOrder();
        break;
    default : exit();
}

if($successAddOrder)
{
    $url->redirectMessagePage();
}
else
{
    $link = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : $url->returnIndexUrl();
    header("Location: $link");
}
exit();