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
     * Only select resources that are not deleted
     */
    public function prepareQueryBeforeCount( xPDOQuery $c ){
        $c->where(array( 'deleted' => 0 ));
        
        foreach($this->confData->templates as $tpl){
            if( is_string($tpl) ){
                $tplObj = $this->modx->getObject('modTemplate',array("name" => $tpl));
                if(! $tplObj instanceof modTemplate ){ continue; };
                $tpl = $tplObj->get('id');
            };
            $c->where(array(
                'template' => $tpl
            ));          
        }
        
        return $c;
    }//
    
    
    /**
     * Loads json config (as specified by `config` request param
     */
    private function loadCustomConfig(){
        $params =& $this->modx->request->parameters['POST'];
        if( !isset($params['config']) || empty($params['config'])){ return false; };
        // Grab config chunk
        $chunkName = $params['config'];
        // Try to load chunk
        $chunk = $this->modx->getObject('modChunk',array(
                'name' => $chunkName
            ));
        if(! $chunk instanceof modChunk ){ return false; }
        // Store parsed config data
        $this->confData = $this->helper->sanitizedJSONdecode($chunk->process());        
    }//
 
    /**
     * Override modObjectGetListProcessor::prepareRow to add TV values to data
     * @param xPDOObject $object
     * @return array The row data
     */
    public function prepareRow(xPDOObject $resource) {
        $data = $resource->toArray();
        
        // Grab names of all TVs
        $tvs = isset($this->confData->tvs)? $this->confData->tvs : array();
        // Add to data array
        foreach($tvs as $tv){
            $tvName = $tv->field;
            // Grab TV value (if it exists)
            $data['tv_'.$tvName] = $resource->getTVValue($tvName);;
            // Null => empty string
            if(is_null($data['tv_'.$tvName])){
                $data['tv_'.$tvName] = '';
            };           
        }
        return $data;
    }//
     
 };// end class grideditorGetListProcessor
 return 'grideditorGetListProcessor';
