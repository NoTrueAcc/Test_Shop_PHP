<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 23.05.2017
 * Time: 18:47
 */

namespace admin\messages;

require_once "globalMessageAbstractClass.php";

/**
 * Класс для обработки сообщений
 *
 * Class messageClass
 * @package messages
 */
class messageClass extends globalMessageAbstractClass
{
    public function __construct()
    {
        parent::__construct('messages');
    }
}