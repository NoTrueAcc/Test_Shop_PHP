<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 24.05.2017
 * Time: 6:28
 */

namespace messages;

require_once $_SERVER['DOCUMENT_ROOT'] . "/library/config/configClass.php";
require_once "emailClass.php";

use config\configClass;

/**
 * Класс для формирования и отправки писем
 *
 * Class mailClass
 * @package messages
 */
class mailClass
{
    private $config;
    private $email;

    public function __construct()
    {
        $this->config = new configClass();
        $this->email = new emailClass();
    }

    /**
     * Метод для формирования и отправки писем
     *
     * @param кому
     * @param данные_для_замены_в_письме
     * @param шаблон_письма
     * @param от_кого
     * @return bool
     */
    public function sendMail($to, $messageData, $template, $from = false)
    {
        $data['sitename'] = $this->config->siteName;
        $from = $from ? $from : $this->config->adminEmail;
        $subject = $this->email->getTitle($template);
        $message = $this->email->getMessageData($template);
        $headers = "From: $from\r\nReply-To: $from\r\nContent-type: text/html; charset=utf-8\r\n";

        foreach($messageData as $key => $value)
        {
            $subject = str_replace("%$key%", $value, $subject);
            $message = str_replace("%$key%", $value, $message);
        }

        $subject = "=?utf-8?B?" . base64_encode($subject) . "?=";

        return mail($to, $subject, $message, $headers);
    }
}