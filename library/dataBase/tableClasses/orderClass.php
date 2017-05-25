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
}