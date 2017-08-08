<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 24.05.2017
 * Time: 18:29
 */

namespace admin\database\tableClasses;

require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/library/dataBase/globalDataBaseAbstractClass.php";
require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/library/dataBase/tableClasses/productClass.php';

use admin\database\globalDataBaseAbstractClass;
use admin\database\tableClasses\productClass;

/**
 * Класс для работы с таблицей Orders
 *
 * Class orderClass
 * @package database\tableClasses
 */
class orderClass extends globalDataBaseAbstractClass
{
    private $__product;

    public function __construct()
    {
        parent::__construct('orders');
        $this->__product = new productClass();
    }

    public function getOrderDataOnId($id)
    {
        if(!$this->checker->checkNumberIntMoreOrZero($id))
        {
            $this->url->redirectNotFound();
        }

        return $this->transformData($this->selectAllOnField('id', $id));
    }

    public function getTableData($limit, $offset)
    {
        return $this->transformData($this->selectAll('id', true, $limit, $offset));
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

    public function getProductIds($id)
    {
        return $this->selectFieldOnId('product_ids', $id);
    }

    private function __addProductsDataToOrderDataElement($orderDataElement)
    {
        $productsDataList = $this->__product->getTitleAndCountOnIds($orderDataElement['product_ids']);

        if(count($productsDataList))
        {
            for ($i = 0; $i < count($productsDataList); $i++)
            {
                $orderDataElement['products'][$productsDataList[$i]['id']] = array($productsDataList[$i]['title'] => $productsDataList[$i]['count']);
            }
        }
        elseif(isset($productsDataList['title']))
        {
            $orderDataElement['products'][$productsDataList['id']] = array($productsDataList['title'] => $productsDataList['count']);
        }

        return $orderDataElement;
    }

    protected function transformElement($orderDataElement)
    {
        $orderDataElement = $this->__addProductsDataToOrderDataElement($orderDataElement);
        $orderDataElement['link_admin_edit'] = $this->url->getLinkAdminEditOrder($orderDataElement['id']);
        $orderDataElement['link_admin_delete'] = $this->url->getLinkAdminDeleteOrder ($orderDataElement['id']);
        $orderDataElement['date_order'] = $this->format->getFormatDate($orderDataElement['date_order']);

        return $orderDataElement;
    }
}