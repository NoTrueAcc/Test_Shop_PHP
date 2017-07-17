<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 21.06.2017
 * Time: 6:28
 */

namespace admin\messages;


class systemMessageClass
{
    public function __construct()
    {
        //session_start();
    }

    public function getMessage($name, $result = false)
    {
        $_SESSION['message'] = $name;

        return $result;
    }

    public function getPageMessage($name, $result = true)
    {
        $_SESSION['page_message'] = $name;

        return $result;
    }

    public function getUnknownError($page = false)
    {
        if($page)
        {
            $_SESSION['page_message'] = 'UNKNOWN_ERROR';
        }
        else
        {
            $_SESSION['message'] = 'UNKNOWN_ERROR';
        }

        return false;
    }
}