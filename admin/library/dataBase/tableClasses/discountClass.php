<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 24.05.2017
 * Time: 18:29
 */

namespace admin\database\tableClasses;

require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/library/dataBase/globalDataBaseAbstractClass.php";

use admin\database\globalDataBaseAbstractClass;

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

    public function getDiscountOnCode($discountCode)
    {
        $result = $this->selectColumnOnFieldValue('code', $discountCode, 'value');

        if(!$result)
        {
            return false;
        }

        return $result['value'];
    }

    public function getDiscountDataOnId($id)
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

    protected function transformElement($dataElement)
    {
        $dataElement['link_admin_edit'] = $this->url->getLinkAdminEditDiscount($dataElement['id']);
        $dataElement['link_admin_delete'] = $this->url->getLinkAdminDeleteDiscount($dataElement['id']);
        return $dataElement;
    }

    public function checkData($data)
    {
        if(!$this->checker->checkCode($data['code'])) return 'ERROR_DISCOUNT_CODE';
        if(!$this->checker->checkDiscountValue($data['value'])) return 'ERROR_DISCOUNT_VALUE';

        return true;
    }
}