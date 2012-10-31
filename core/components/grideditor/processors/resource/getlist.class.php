<?php
/**
 * Grid List processor
 * 
 * @package grideditor
 * @copyright Alan Pich 2012
 */
 class grideditorGetListProcessor extends modObjectGetListProcessor {
    public $classKey = 'modResource';
    public $languageTopics = array();
    public $defaultSortField = 'pagetitle';
    public $defaultSortDirection = 'ASC';
    public $objectType = 'modResource';
    
    /**
     * @var $confData Custom config data (from json chunk)
     * @access private
     */
    private $confData;
 
    /**
     * @param xPDOQuery $c
     * @return \xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c) {
  /*     $query = $this->getProperty('property');
        if (!empty($query) && is_numeric($query)) {
            $c->innerJoin('fdmRoomTypeProperty','RTP','fdmRoomType.id = roomtype');
            $c->where(array(
                'RTP.property' => (int)$query
            ));
        }
   */     return $c;
    }
     
 };// end class grideditorGetListProcessor
 return 'grideditorGetListProcessor';
