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
        
    }//
    
    
    /**
     * Get list of filter options specified by config chunk
     */
    public function process(){
        
        // Grab config chunk name
        $params =& $this->modx->request->parameters['POST'];
        if( !isset($params['chunk']) || empty($params['chunk'])){ 
            return $this->failure('No config chunk specified');
        };
        $chunkName = $params['chunk'];
        
        // Load config
        if(!$conf = $this->modx->grideditor->loadConfigChunk($chunkName)){
            return $this->failure('Invalid config chunk');
        };
        
        // Grab all resources that match the template filters
        $c = $this->modx->grideditor->get_xPDOQuery($conf); //$this->helper->getResourceQueryWhereArray($conf);
        $resources = $this->modx->getCollection('modResource',$c);
        
        // Grab the filter field
        $filterField = $conf->filter->field;
        $isTV = ($conf->fields[$filterField]->type == 'tv');
        
        // Grab the field specified from each resource
        $values = array(array('name' => $conf->filter->label, 'value'=>''));
        foreach($resources as $res){
            if($isTV){
                $val = $res->getTVValue($filterField);
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
     
 };// end class grideditorGetListProcessor
 return 'gridFilterGetListProcessor';
