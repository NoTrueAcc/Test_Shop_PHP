<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 24.05.2017
 * Time: 18:29
 */

namespace admin\database\tableClasses;

require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/library/dataBase/globalDataBaseAbstractClass.php";

use admin\database\globalDataBaseAbstractClass;

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

        return $this->transformData($this->selectAllOnField('id', $id));
    }

    public function getSectionTitleOnId($id)
    {
        return $this->selectFieldOnId('title', $id);
    }

    public function getTableData($limit, $offset)
    {
        return $this->transformData($this->selectAll('id', true, $limit, $offset));
    }

    protected function transformElement($sectionDataElement)
    {
        $sectionDataElement['link'] = $this->url->sectionDataElementLink($sectionDataElement['id']);
        $sectionDataElement['link_admin_edit'] = $this->url->getLinkAdminEditSection($sectionDataElement['id']);
        $sectionDataElement['link_admin_delete'] = $this->url->getLinkAdminDeleteSection($sectionDataElement['id']);
        $sectionDataElement['section_title'] = $sectionDataElement['title'];

        return $sectionDataElement;
    }

    public function checkData($data)
    {
        if(!$this->checker->checkTitle($data['title'], false)) return 'ERROR_TITLE';

        return true;
    }
}