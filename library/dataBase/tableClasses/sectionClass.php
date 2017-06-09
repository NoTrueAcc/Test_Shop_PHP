<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 24.05.2017
 * Time: 18:29
 */

namespace database\tableClasses;

require_once $_SERVER['DOCUMENT_ROOT'] . "/library/dataBase/globalDataBaseAbstractClass.php";

use database\globalDataBaseAbstractClass;

/**
 * Класс для работы с таблицей Sections
 *
 * Class sectionClass
 * @package database\tableClasses
 */
class sectionClass extends globalDataBaseAbstractClass
{
    public function __construct()
    {
        parent::__construct('sections');
    }

    public function getAllData()
    {
        return $this->transformData($this->selectAll('id'));
    }

    public function getSectionDataOnId($id)
    {
        if(!$this->checker->checkNumberIntMoreOrZero($id))
        {
            $this->url->redirectNotFound();
        }

        return $this->selectAllOnField('id', $id);
    }

    public function getSectionTitleOnId($id)
    {
        return $this->selectFieldOnId('title', $id);
    }

    protected function transformElement($sectionDataElement)
    {
        $sectionDataElement['link'] = $this->url->sectionDataElementLink($sectionDataElement['id']);

        return $sectionDataElement;
    }
}