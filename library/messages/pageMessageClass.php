<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 23.05.2017
 * Time: 18:44
 */

namespace messages;

require_once "complexMessageAbstractClass.php";

/**
 * Класс для обработки сообщений на странице
 *
 * Class pageMessageClass
 * @package messages
 */
class pageMessageClass extends complexMessageAbstractClass
{
    public function __construct()
    {
        parent::__construct('pageMessages');
    }
}