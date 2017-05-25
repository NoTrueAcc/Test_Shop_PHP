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
 * Класс для работы с таблицей Discounts
 *
 * Class discountClass
 * @package database\tableClasses
 */
class discountClass extends globalDataBaseAbstractClass
{
    public function __construct()
    {
        parent::__construct('discounts');
    }
}