<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 29.05.2017
 * Time: 6:58
 */

namespace template\templateContentClasses;

require_once $_SERVER['DOCUMENT_ROOT'] . '/library/template/globalContentAbstractClass.php';

use template\globalContentAbstractClass;

class orderContentClass extends globalContentAbstractClass
{
    protected $title = 'Ваш заказ';
    protected $meta_key = 'Оформление заказа';
    protected $meta_desc = 'заказ, оформление заказа';

    protected function getContent()
    {
        $this->template->setDataForReplace('message', $this->getMessage());
        $this->template->setDataForReplace('name', (isset($_SESSION['name']) ? $_SESSION['name'] : ''));
        $this->template->setDataForReplace('phone', (isset($_SESSION['phone']) ? $_SESSION['phone'] : ''));
        $this->template->setDataForReplace('email', (isset($_SESSION['email']) ? $_SESSION['email'] : ''));
        $this->template->setDataForReplace('delivery', (isset($_SESSION['delivery']) ? $_SESSION['delivery'] : ''));
        $this->template->setDataForReplace('address', (isset($_SESSION['address']) ? $_SESSION['address'] : ''));
        $this->template->setDataForReplace('notice', (isset($_SESSION['notice']) ? $_SESSION['notice'] : ''));
        $this->template->setDataForReplace('order_price', $this->getFullPriceWithDiscount());
        $this->template->setDataForReplace('have_discount', (isset($_SESSION['discount']) ? '(со скидкой)' : ''));

        return 'order';
    }

    private function getFullPriceWithDiscount()
    {
        $ids = isset($_SESSION['cart']) ? explode(',', $_SESSION['cart']) : array();
        $price = $this->product->getCartPriceOnIds($ids);
        $discount = isset($_SESSION['discount']) ? $this->discount->getDiscountOnCode($_SESSION['discount']) : 0;

        return $price * (1 - $discount);
    }
}