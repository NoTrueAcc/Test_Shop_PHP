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

	public function checkFieldsData($data)
	{
		if(isset($data['delivery']) && !$this->checker->oneOrZero($data['delivery'])) return "ERROR_DELIVERY";
		if(isset($data['product_ids']) && !$this->checker->checkIds($data['product_ids'])) return "UNKNOWN_ERROR";
		if(isset($data['price']) && !$this->checker->checkPrice($data['price'])) return "UNKNOWN_ERROR";
		if(isset($data['name']) && !$this->checker->checkName($data['name'])) return "ERROR_NAME";
		if(isset($data['phone']) && !$this->checker->checkPhone($data['phone'])) return "ERROR_PHONE";
		if(isset($data['email']) && !$this->checker->checkEmail($data['email'])) return "ERROR_EMAIL";
		$empty = (isset($data['delivery']) && $data['delivery'] == 1) ? true : false;
		if(isset($data['address']) && !$this->checker->checkText($data['address'], $empty)) return "ERROR_ADDRESS";
		if(isset($data['notice']) && !$this->checker->checkText($data['notice'], true)) return "ERROR_NOTICE";
		if(isset($data['date_order']) && !$this->checker->checkTimeStamp($data['date_order'])) return "UNKNOWN_ERROR";
		if(isset($data['date_send']) && !$this->checker->checkTimeStamp($data['date_send'])) return "UNKNOWN_ERROR";
		if(isset($data['date_pay']) && !$this->checker->checkTimeStamp($data['date_pay'])) return "UNKNOWN_ERROR";

		return true;
	}

    public function getProductIds($id)
    {
        return $this->selectFieldOnId('product_ids', $id);
    }

	/**
	 *
	 *
	 * @param $orderId
	 * @param $positionId
	 * @return bool
	 */
    public function deletePosition($orderId, $positionId)
	{
		$orderData = $this->getOrderDataOnId($orderId);
		$orderPositions = explode(',', $orderData[0]['product_ids']);

		if(count($orderPositions) == 1)
        {
            return false;
        }

		$resultPositions = array_diff($orderPositions, array($positionId));
		$orderPrice = $this->__getPriceWithDiscount($resultPositions, $orderData[0]['discount']);
		$resultPositions = implode(',', $resultPositions);

		return $this->updateFieldsOnId($orderId, array('product_ids' => $resultPositions, 'price' => $orderPrice));
	}

	private function __getPriceWithDiscount($resultPositions, $discount)
	{
		$price = $this->__product->getPriceOnIds($resultPositions);

		return $price * (1 - $discount);
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
        $orderDataElement['date_send'] = ($orderDataElement['date_send'] == 0) ? 'Не отправлено' : $this->format->getFormatDate($orderDataElement['date_send']);
        $orderDataElement['date_pay'] = ($orderDataElement['date_pay'] == 0) ? 'Не оплачено' : $this->format->getFormatDate($orderDataElement['date_pay']);

        return $orderDataElement;
    }
}