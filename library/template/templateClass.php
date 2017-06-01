<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 26.05.2017
 * Time: 6:13
 */

namespace template;

/**
 * Класс шаблонизатор получает путь к шаблону и данные на которые нужно заменить
 *
 * Class templateClass
 * @package template
 */
class templateClass
{
    private $dirTemplate;
    private $dataForReplace = array();

    public function __construct($dirTemplate)
    {
        $this->dirTemplate = $dirTemplate;
    }

    /**
     * Метод записывает данные в массив(с данными для замены в шаблоне) по имени замены
     *
     * @param имя
     * @param данные
     */
    public function setDataForReplace($name, $value)
    {
        $this->dataForReplace[$name] = $value;
    }

    /**
     * Удаляет элемент массива с данными для замены
     *
     * @param имя
     */
    public function deleteData($name)
    {
        unset($this->dataForReplace[$name]);
    }

    /**
     * Служебнай метод. Проверяет существет ли элемент с данным именем
     * вызывается при обращении к классу
     *
     * @param имя
     * @return найденный_элемент|пустую_строку
     */
    public function __get($name)
    {
        return isset($this->dataForReplace[$name]) ? $this->dataForReplace[$name] : "";
    }

    /**
     * Метод устанавливает данные в шаблоне, записывает в буфер, после чего выводит их и очищает буфер
     *
     * @param $template
     */
    public function display($template)
    {
        $template = $this->dirTemplate . $template . ".phtml";
        ob_start();
        include ($template);
        echo ob_get_clean();
    }
}