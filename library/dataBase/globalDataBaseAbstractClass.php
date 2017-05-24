<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 24.05.2017
 * Time: 7:19
 */

namespace database;

require_once "dataBaseClass.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/library/config/configClass.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/library/helper/urlClass.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/library/helper/checkerClass.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/library/helper/formatClass.php";

use config\configClass;
use helper\urlClass;
use helper\checkerClass;
use helper\formatClass;

abstract class globalDataBaseAbstractClass
{
    protected $dataBaseConnect;
    protected $config;
    protected $format;
    protected $url;
    protected $checker;
    protected $tableName;

    public function __construct($tableName)
    {
        $this->dataBaseConnect = dataBaseClass::getConnection();
        $this->config = new configClass();
        $this->format = new formatClass();
        $this->url = new urlClass();
        $this->checker = new checkerClass();
        $this->tableName = $this->config->dataBasePrefix . $tableName;
    }

    public function selectAll($order = false, $desc = false, $limit = false, $offset = false)
    {
        $orderOrLimit = $this->selectOrderOrLimit($order, $desc, $limit, $offset);
        $query = "SELECT * FROM `" . $this->tableName . "`$orderOrLimit";

        return $this->dataBaseConnect->selectData($query);
    }

    protected function selectAllOnField($field, $value, $order = false, $desc = false, $limit = false, $offset = false)
    {
        $orderOrLimit = $this->selectOrderOrLimit($order, $desc, $limit, $offset);
        $query = "SELECT * FROM `" . $this->tableName . "` WHERE `$field` = " . $this->config->symQuery . "$orderOrLimit";

        return $this->dataBaseConnect->selectData($query, array($value));
    }

    protected function selectOrderOrLimit($order, $desc, $limit, $offset)
    {
        $order = $order ? " ORDER BY `$order`" : '';
        $desc = $desc ? " DESC" : '';
        $offset = $this->checker->isIntNumber($offset) ? " `$offset` ," : '';
        $limit = $this->checker->checkNumberIntMoreOrZero($limit, true) ? " LIMIT $offset `$limit`" : '';

        return $order . $desc . $limit;
    }
}