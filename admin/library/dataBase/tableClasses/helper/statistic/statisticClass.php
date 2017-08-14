<?php

/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 11.08.2017
 * Time: 13:51
 */

namespace admin\database\tableClasses\helper;

use admin\database\tableClasses\orderClass;

require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/library/dataBase/tableClasses/orderClass.php";

class statisticClass
{
    private $__order;

    public function __construct()
    {
        $this->__order = new orderClass();
    }

    public function getAllOrdersInInterval($dateFrom, $dateTo)
    {
        $ordersResultData = array(
            'count_orders'  => 0,
            'summ_accounts' => 0,
            'income'        => 0,
            'count_dvd'     => 0
        );

        $ordersData = $this->__order->getAllInInterval($dateFrom, $dateTo);
        $ordersResultData['count_orders'] = count($ordersData);

        for($i = 0; $i < count($ordersData); $i++)
        {
            $ordersResultData['summ_accounts'] += $ordersData[$i]['price'];
            $ordersResultData['income'] += ($ordersData[$i]['date_pay'] != 0) ? $ordersData[$i]['price'] : 0;
            $ordersResultData['count_dvd'] += count(explode(',', $ordersData[$i]['product_ids']));
        }

        return $ordersResultData;
    }
}