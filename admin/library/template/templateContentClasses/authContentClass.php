<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 29.05.2017
 * Time: 6:58
 */

namespace admin\template\templateContentClasses;

require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/library/template/globalContentAbstractClass.php';

use admin\template\globalContentAbstractClass;

class authContentClass extends globalContentAbstractClass
{
    protected $title = 'Вход в панель администратора';
    protected $meta_key = 'Управление интернет-магазином';
    protected $meta_desc = 'интернет магазин, интернет магазин управление, интернет магазин панель администратора, интернет магазин вход в панель администратора';

    const adminAuth = 'Вход в панель администратора';
    const changeUser = 'Изменение пользователя';

    public function __construct()
    {
        parent::__construct(false);
    }

    protected function getContent()
    {

        if(isset($_SESSION['login']) && isset($_SESSION['password']) && $this->auth->checkAdminAuth($_SESSION['login'], $_SESSION['password']))
        {
            $this->url->redirectAdminIndex();
        }

        $login = isset($_SESSION['login']) ? $_SESSION['login'] : '';
        $this->template->setDataForReplace('login', $login);

        return 'auth';
    }
}