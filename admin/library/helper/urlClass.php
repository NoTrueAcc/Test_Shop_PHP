<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 22.05.2017
 * Time: 6:35
 */

namespace admin\helper;

require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/library/config/configClass.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/library/helper/urlClass.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/library/helper/formatClass.php";

use admin\config\configClass;

/**
 * Класс помошник для работы со ссылками
 *
 * Class urlClass
 * @package helper
 */
class urlClass extends \helper\urlClass
{
    protected $config;
    protected $_format;
    protected $_data;

    public function __construct()
    {
        parent::__construct();
        $this->config = new configClass();
        $this->_format = new formatClass();
        $this->_data = $this->_format->checkDataFromXSS($_REQUEST);
    }

    /**
     * Возвращает класс для отрисовки страницы.
     * Если URI пустое - возвращает главную страницу.
     * Иначе выводит NotFound
     *
     * @return mixed
     */
    public function getContentClass()
    {
        $templateContentDir = "admin\\template\\templateContentClasses\\";
        $contentClassNameShort = $this->__getView();

        if(file_exists($_SERVER['DOCUMENT_ROOT'] . $this->config->templateContentClassesDir . $contentClassNameShort . "ContentClass.php"))
        {
        require_once $_SERVER['DOCUMENT_ROOT'] . $this->config->templateContentClassesDir . $contentClassNameShort . "ContentClass.php";

            $contentClassNameFull = $templateContentDir . $contentClassNameShort . "ContentClass";

            return new $contentClassNameFull;
        }
        elseif(empty($contentClassNameShort))
        {
            require_once $_SERVER['DOCUMENT_ROOT'] . $this->config->templateContentClassesDir . "indexContentClass.php";

            $contentClassNameFull = $templateContentDir . "indexContentClass";

            return new $contentClassNameFull;
        }
        else
        {
            require_once $_SERVER['DOCUMENT_ROOT'] . $this->config->templateContentClassesDir . "notFoundContentClass.php";

            $contentClassNameFull = $templateContentDir . "notFoundContentClass";

            return new $contentClassNameFull;
        }
    }

    public function returnIndexAdminUrl()
    {
        return $this->returnAdminURL();
    }

    public function returnProductsAdminUrl()
    {
        return $this->returnAdminURL('products');
    }

    public function returnOrdersAdminUrl()
    {
        return $this->returnAdminURL('orders');
    }

    public function returnSectionsAdminUrl()
    {
        return $this->returnAdminURL('sections');
    }

    public function returnDiscountsAdminUrl()
    {
        return $this->returnAdminURL('discounts');
    }

    public function returnStatisticsAdminUrl()
    {
        return $this->returnAdminURL('statistics');
    }

    public function returnLogoutAdminUrl()
    {
        return parent::returnURL('/functions.php?func=logout');
    }

    public function returnAuthAdminUrl()
    {
        return $this->returnAdminURL('auth');
    }

    public function returnAdminNotFoundUrl()
    {
        return $this->returnAdminURL('notfound');
    }

    public function redirectAuth()
    {
        parent::redirect($this->__returnAuthUrl());
    }

    public function redirectAdminNotFound()
    {
        parent::redirect($this->returnAdminNotFoundUrl());
    }

    public function redirectAdminIndex()
    {
        parent::redirect($this->returnIndexAdminUrl());
    }

    public function redirectAdminProducts()
    {
        parent::redirect($this->returnProductsAdminUrl());
    }

    public function redirectAdminSections()
    {
        parent::redirect($this->returnSectionsAdminUrl());
    }

    public function redirectAdminOrders()
    {
        parent::redirect($this->returnOrdersAdminUrl());
    }

    public function redirectAdminDiscounts()
    {
        parent::redirect($this->returnDiscountsAdminUrl());
    }

    public function getFuncLink($funcData)
    {
        $url = $this->getThisUrl();
        $funcGet = array('func' => $funcData);

        return $this->addGet($url, $funcGet);
    }

    public function getLinkAdminEditProduct($id)
    {
        return $this->returnAdminURL("products&func=edit&id=$id");
    }

    public function getLinkAdminDeleteProduct($id)
    {
        return parent::returnURL("/functions.php?func=delete_product&id=$id");
    }

    public function getLinkAdminEditSection($id)
    {
        return $this->returnAdminURL("sections&func=edit&id=$id");
    }

    public function getLinkAdminDeleteSection($id)
    {
        return parent::returnURL("/functions.php?func=delete_section&id=$id");
    }

    public function getLinkAdminEditOrder($id)
    {
        return $this->returnAdminURL("orders&func=edit&id=$id");
    }

    public function getLinkAdminDeleteOrderPosition($orderId, $positionId = '')
    {
        return parent::returnURL("/functions.php?func=delete_order_position&order_id=$orderId&position_id=$positionId");
    }

	public function getLinkAdminAddOrderPosition($orderId)
	{
		return parent::returnURL("/functions.php?func=add_order_position&order_id=$orderId");
	}

    public function getLinkAdminDeleteOrder($id)
    {
        return parent::returnURL("/functions.php?func=delete_order&id=$id");
    }

    public function getLinkAdminEditDiscount($id)
    {
        return $this->returnAdminURL("discounts&func=edit&id=$id");
    }

    public function getLinkAdminDeleteDiscount($id)
    {
        return parent::returnURL("/functions.php?func=delete_discount&id=$id");
    }

    public function productDataElementLink($productId)
    {
        return $this->__returnSiteUrl("product?id=$productId");
    }

    private function __returnSiteUrl($uri)
    {
        return !empty($uri) ? $this->config->siteAddress . $uri : $this->config->siteAddress;
    }

    private function returnAdminURL($url = "")
    {
        $url = !empty($url) ? $this->config->address . "?view=$url" : $this->config->address;

        return $url;
    }

    private function __returnAuthUrl()
    {
        return $this->returnAdminURL('auth');
    }

    private function __getView()
{
    return isset($this->_data['view']) ? $this->_data['view'] : 'index';
}
}