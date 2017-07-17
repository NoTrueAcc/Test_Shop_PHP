<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 23.05.2017
 * Time: 18:32
 */

namespace messages;

require_once "globalMessageAbstractClass.php";

/**
 * Абстрактный класс для получения дополнительных сведений из сообщений
 *
 * Class complexMessageAbstractClass
 * @package messages
 */
abstract class complexMessageAbstractClass extends globalMessageAbstractClass
{
    /**
     * Возвращает заголовок сообщения
     *
     * @param $name
     * @return mixed
     */
    public function getTitle($name)
    {
        return $this->getMessageData($name . "_TITLE");
    }

    /**
     * Возвращает текст сообщения
     *
     * @param $name
     * @return mixed
     */
    public function getText($name)
    {
        return $this->getMessageData($name . "_TEXT");
    }
}