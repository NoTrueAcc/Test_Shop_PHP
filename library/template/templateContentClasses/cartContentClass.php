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

class cartContentClass extends globalContentAbstractClass
{
    protected $title = 'Корзина';
    protected $meta_key = 'Содержимое корзины';
    protected $meta_desc = 'корзина, содержимое корзины';

    protected function getContent()
    {
        $cartDataListOnIds = array();
        $cartFullSumma = 0;
        $productCartIds = (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) ? explode(',', $_SESSION['cart']) : array();
        $cartDataList = !empty($productCartIds) ? $this->product->getAllOnIds($productCartIds) : array();
        $cart = array();

        for($i = 0; $i < count($cartDataList); $i++)
        {
            $cartDataListOnIds[$cartDataList[$i]['id']] = $cartDataList[$i];
        }

        $productCartUniqueIds = array_unique($productCartIds);
        $i = 0;

        foreach($productCartUniqueIds as $uniqueId)
        {
            $cart[$i]['id'] = $cartDataListOnIds[$uniqueId]['id'];
            $cart[$i]['title'] = $cartDataListOnIds[$uniqueId]['title'];
            $cart[$i]['img'] = $cartDataListOnIds[$uniqueId]['img'];
            $cart[$i]['price'] = $cartDataListOnIds[$uniqueId]['price'];
            $cart[$i]['count'] = $this->getCountValueInArray($uniqueId, $productCartIds);
            $cart[$i]['summa'] = $cart[$i]['count'] * $cart[$i]['price'];
            $cart[$i]['link_delete'] = $this->url->deleteDataElementFromCart($uniqueId);
            $cart[$i]['link_product'] = $cartDataListOnIds[$uniqueId]['link'];
            $cartFullSumma += $cart[$i]['summa'];

            $i++;
        }

        $discount = (isset($_SESSION['discount']) && !empty($_SESSION['discount'])) ? $this->discount->getDiscountOnCode($_SESSION['discount']) : '';
        $cartFullSumma = (!empty($discount)) ? (1 - $discount)*$cartFullSumma : $cartFullSumma;
        $dicountCode = isset($_SESSION['discount']) ? $_SESSION['discount'] : '';

        $this->template->setDataForReplace('cart_full_summa', $cartFullSumma);
        $this->template->setDataForReplace('cart', $cart);
        $this->template->setDataForReplace('discount', $dicountCode);
        $this->template->setDataForReplace('discount_text', $this->getDiscountText($discount));
        $this->template->setDataForReplace('order_submit', $this->url->returnOrderUrl());

        return 'cart';
    }

    private function getDiscountText($discount)
    {
        return !empty($discount) ? " (со скидкой " . $discount*100 . "%)" : '';
    }

    /**
     * Находит количество совпадений в 2у-мерном массиве
     *
     * @param $id
     * @param $idDataList
     * @return int
     */
    private function getCountValueInArray($value, $valueDataList)
    {
        $countValue = 0;

        for($i = 0; $i < count($valueDataList); $i++)
        {
            $countValue = ($valueDataList[$i] == $value) ? ($countValue + 1) : $countValue;
        }

        return $countValue;
    }
}