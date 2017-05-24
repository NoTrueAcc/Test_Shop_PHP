<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 23.05.2017
 * Time: 18:05
 */

namespace messages;

require_once $_SERVER['DOCUMENT_ROOT'] . "/library/config/configClass.php";

use config\configClass;

/**
 * Абстрактный класс для парсинга и передачи данных сообщений
 *
 * Class globalMessageAbstractClass
 * @package messages
 */
abstract class globalMessageAbstractClass
{
    private $data;

    /**
     * Парсит сообщения
     *
     * globalMessageAbstractClass constructor.
     * @param файл собщения
     */
    public function __construct($messageFile)
    {
        $config = new configClass();
        $this->data = parse_ini_file($config->messagesTextDir . $messageFile . ".ini");
    }

    /**
     * Возвращает текст сообщений по имени сообщения
     *
     * @param $messageName
     * @return mixed
     */
    public function getMessageData($messageName)
    {
        return $this->data[$messageName];
    }
}