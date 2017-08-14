<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 11.08.2017
 * Time: 14:21
 */

namespace admin\template\templateContentClasses;

use admin\template\globalContentAbstractClass;

require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/library/template/globalContentAbstractClass.php';

class statisticsContentClass extends globalContentAbstractClass
{
    protected $title = 'Статистика';
    protected $meta_desc = 'Аккаунт администратора интернет магазина';
    protected $meta_key = 'статистика';

    protected function getContent()
    {
        $dateFrom = isset($_SESSION['date_from']) ? $this->format->getFormatUnixTime($_SESSION['date_from']) : false;
        $dateTo = isset($_SESSION['date_to']) ? $this->format->getFormatUnixTime($_SESSION['date_to']) : false;

        if($dateFrom && $dateTo)
        {
            $statistics = $this->statistic->getAllOrdersInInterval($dateFrom, $dateTo);
            $this->template->setDataForReplace('date_from', $_SESSION['date_from']);
            $this->template->setDataForReplace('date_to', $_SESSION['date_to']);
            $this->template->setDataForReplace('orders_statistics', $statistics);
        }

        return 'statistics';
    }
}