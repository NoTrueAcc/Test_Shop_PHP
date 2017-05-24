<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 23.05.2017
 * Time: 18:38
 */

namespace messages;

require_once "complexMessageAbstractClass.php";

/**
 * Класс для обработки сообщений писем
 *
 * Class emailClass
 * @package messages
 */
class emailClass extends complexMessageAbstractClass
{
    public function __construct()
    {
        parent::__construct('emails');
    }


}