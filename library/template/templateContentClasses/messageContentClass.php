<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 29.05.2017
 * Time: 6:58
 */

namespace template\templateContentClasses;

require_once $_SERVER['DOCUMENT_ROOT'] . '/library/template/globalContentAbstractClass.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/library/messages/pageMessageClass.php';

use template\globalContentAbstractClass;
use messages\pageMessageClass;

class messageContentClass extends globalContentAbstractClass
{
    public $title;
    public $meta_desc;
    public $meta_key;

    protected function getContent()
    {
        $pageMessage = new pageMessageClass();
        $messageTitle = isset($_SESSION['page_message']) ? $pageMessage->getTitle($_SESSION['page_message']) : '';
        $messageText = isset($_SESSION['page_message']) ? $pageMessage->getText($_SESSION['page_message']) : '';
        $this->title = $messageTitle;
        $this->meta_desc = $messageText;
        $this->meta_key = preg_replace('/\s+/i', ', ', mb_strtolower($messageText));
        $this->template->setDataForReplace('message_title', $messageTitle);
        $this->template->setDataForReplace('message_text', $messageText);

        return 'message_page';
    }
}