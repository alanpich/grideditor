<?php
/**
 * Grid List processor
 *
 * @package grideditor
 * @copyright Alan Pich 2012
 */
class grideditorGetListByIdProcessor extends modObjectGetListProcessor
{
    public $classKey = 'modResource';
    public $languageTopics = array();
    public $defaultSortField = 'pagetitle';
    public $defaultSortDirection = 'ASC';
    public $objectType = 'modResource';


    /**
     * Only select resources in the specified ids
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $ids = explode(',',$this->getProperty('ids',''));
        $c->where(array('id:IN' => $ids));
        return $c;
    }

};
return 'grideditorGetListByIdProcessor';
