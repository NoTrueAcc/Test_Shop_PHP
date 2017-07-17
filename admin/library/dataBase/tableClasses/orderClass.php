<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 24.05.2017
 * Time: 18:29
 */

namespace database\tableClasses;

require_once $_SERVER['DOCUMENT_ROOT'] . "/library/dataBase/globalDataBaseAbstractClass.php";

use database\globalDataBaseAbstractClass;

/**
 * Класс для работы с таблицей Orders
 *
 * Class orderClass
 * @package database\tableClasses
 */
class orderClass extends globalDataBaseAbstractClass
{
    public function __construct()
    {
        parent::__construct('orders');
    }

    public function checkData($data)
    {
        if(!$this->checker->oneOrZero($data['delivery'])) return "ERROR_DELIVERY";
        if(!$this->checker->checkIds($data['product_ids'])) return "UNKNOWN_ERROR";
        if(!$this->checker->checkPrice($data['price'])) return "UNKNOWN_ERROR";
        if(!$this->checker->checkName($data['name'])) return "ERROR_NAME";
        if(!$this->checker->checkPhone($data['phone'])) return "ERROR_PHONE";
        if(!$this->checker->checkEmail($data['email'])) return "ERROR_EMAIL";
        $empty = ($data['delivery'] == 1) ? true : false;
        if(!$this->checker->checkText($data['address'], $empty)) return "ERROR_ADDRESS";
        if(!$this->checker->checkText($data['notice'], true)) return "ERROR_NOTICE";
        if(!$this->checker->checkTimeStamp($data['date_order'])) return "UNKNOWN_ERROR";
        if(!$this->checker->checkTimeStamp($data['date_send'])) return "UNKNOWN_ERROR";
        if(!$this->checker->checkTimeStamp($data['date_pay'])) return "UNKNOWN_ERROR";

        return true;
    }
}