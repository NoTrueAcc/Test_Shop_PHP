<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 20.05.2017
 * Time: 18:58
*/

require_once $_SERVER['DOCUMENT_ROOT'] . '/library/helper/urlClass.php';

$params = array('a' => '', 'b' => '', 'c' => '');

echo (new \helper\urlClass())->deleteOrSetGet('http://asdada.ru/sadas?b=1&a=2&c=3', $params);