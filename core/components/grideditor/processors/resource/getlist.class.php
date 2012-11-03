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
     * Helper class
     * @access private
     */
    private $helper;
    
    
    /**
     * Load helper & custom config load to constructor
     */
    function __construct(&$modx){
        parent::__construct($modx);
        
        if(!class_exists('grideditorHelper')){
            require $this->modx->getOption('core_path').'components/grideditor/grideditorHelper.class.php';
        };
        $this->helper = new grideditorHelper($modx);
        
        // Attempt to load config chunk
        if(!$this->loadCustomConfig()){
            return $this->failure('Invalid Config Chunk');
        };
        
    }//
    
    
    /**
     * Loads json config (as specified by `config` request param
     */
    private function loadCustomConfig(){
        $params =& $this->modx->request->parameters['POST'];
        if( !isset($params['config']) || empty($params['config'])){ return false; };
        
        $chunkName = $params['config'];
        // Try to load chunk
        $chunk = $this->modx->getObject('modChunk',array(
                'name' => $chunkName
            ));
        
    }//
 
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
