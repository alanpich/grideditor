<?php
/**
 * Grid Get Filter options
 * 
 * @package grideditor
 * @copyright Alan Pich 2012
 */
 class gridFilterGetListProcessor extends modProcessor {

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
     * Get list of filter options specified by config chunk
     */
    public function process(){
        // Grab all resources that match the template filters
        $c = $this->helper->getResourceQueryWhereArray($this->confData);
        $resources = $this->modx->getCollection('modResource',$c);
        
        // Grab the filter field
        $filterField = $this->confData->filter->field;
        $isTV = (preg_match("/^tv_/",$filterField));
        
        // Grab the field specified from each resource
        $values = array(array('name' => $this->confData->filter->label, 'value'=>''));
        foreach($resources as $res){
            if($isTV){
                $val = $res->getTVValue(str_replace('tv_','',$filterField));
            } else {
                $val = $res->get($filterField);
            };
            if(! in_array_r($val,$values) && !empty($val)){
                $values[] = array(
                    'name' => $val,
                    'value'=> $val
                  );
            };
        };
        
        return $this->outputArray($values);        
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
     
 };// end class grideditorGetListProcessor
 return 'gridFilterGetListProcessor';
