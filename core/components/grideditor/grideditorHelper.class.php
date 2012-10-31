<?php
/**
 * Helper Class
 * 
 * @package grideditor
 * @copyright Alan Pich 2012
 */
 class grideditorHelper {
	 
    /**
     * @var array $config Useful paths etc
     * @access public
     */
    public $config = array();

    /**
     * @var modX $modx Reference to MODx instance
     * @access private
     */
    private $modx;
    
    /**
     * @var array $resFieldRenderers Resource field Ext renderers
     * @access private
     */
    private $resFieldRenderers = array(
            'pagetitle' => 'textfield',
            'longtitle' => 'textfield',
            'introtext' => 'textfield',
            'description' => 'textarea',
            'alias' => 'textfield'
        );
    
    /**
     * Constructor - sets up config array
     * @param modX $modx Current instance of MODx
     */
    function __construct(modX &$modx){
           // Make MODx instance accessible
           $this->modx =& $modx;

           // Setup config data
           $core = $this->modx->getOption('core_path').'components/grideditor/';
           $assets = $this->modx->getOption('assets_url').'components/grideditor/';
           $this->config = array(
                   'corePath' => $core,
                   'processorPath' => $core.'processors/',
                   'controllerPath' => $core.'controllers/',
                   'templatePath' => $core.'templates/',
                   'docsPath' => $core.'docs/',
                   'assetsUrl' => $assets,
                   'jsUrl' => $assets.'mgr/js/',
                   'cssUrl' => $assets.'mgr/css/',
       'connectorUrl' => $assets.'mgr/connector.php',

       'configChunkPrefix' => 'grideditor.config.'
           );
    }//

    
    /**
     * Get config array as JSON string (for javascript insert)
     * @return string JSON encoded self::$config array
     */
    public function configJSON(){
           return $this->modx->toJSON($this->config); 
    }//
     
     
    /**
     * Sanitize json input. Removes all invalid chars & comments
     * @param string $raw Raw JSON input
     * @return mixed Parse JSON -> object|array etc
     */
    public function sanitizedJSONdecode($raw){
        $safe = preg_replace("/[^[:print:]]/",'',$raw);
        return json_decode($safe);        
    }//
    
    /**
     * Prepare custom config for passing to Ext/Javascript
     * @param Object $data Parsed config object
     * @return Object prepared for Ext use
     */
    public function getExtConfig($conf){
        $C = new stdClass;
        // Title for both <title> and <h2> page titles
        $C->title = isset($conf->title)? $conf->title : $this->modx->lexicon('grideditor.cmp.default.title');
        // Control buttons for resources (edit,publish,delete etc)
        $C->controls = isset($conf->controls)? $conf->controls : array();
        // Grid Field data
        $C->fields = array();
   
        // Add resource fields
        if(isset($conf->fields)){
            foreach($conf->fields as $field){
                if($F = $this->prepFieldForExt($field)){
                    $C->fields[] = $F;
                };
            };
        };
        
        // Add TV fields
        if(isset($conf->tvs)){
            foreach($conf->tvs as $tv){
                if($F = $this->prepTvForExt($tv)){
                    $C->fields[] = $F;
                };
            };
        };
        
        return $C;
    }//
    
    
    /**
     * Prepare resource field data for Ext use
     * @param object $field The config data for this field
     * @return object Prepared data
     */
    private function prepFieldForExt($field){
        // Sanity check
        $resourceFields = array_keys($this->resFieldRenderers);
        if(!isset($field->name) || !in_array($field->name,$resourceFields)){ return FALSE; };
        // Prepare Data
        $F = new stdClass;
        $F->name = $field->name;
        $F->title = isset($field->title)? $field->title : $field->name;
        $F->editable = isset($field->editable)? $field->editable : false;
        $F->editor = $this->getFieldEditor($field->name); 
        // Return object
        return $F;
    }//
    
    
    /**
     * Return a js renderer function for a resource field
     * @param string $field Name of resource field
     * @return string Renderer function
     */
    private function getFieldEditor($field){
        // Sanity check
        if(!in_array($field, array_keys($this->resFieldRenderers))){ return 'textfield'; };
        return $this->resFieldRenderers[$field];
    }//
    
    
    /**
     * Prepare TV field data for Ext use
     * @param object $field The config data for this TV
     * @return object Prepared data
     */
    private function prepTvForExt($field){
        // Sanity Checks
        if(!isset($field->name) || empty($field->name)){ return false; };
        $tv = $this->modx->getObject('modTemplateVar',array('name' => $field->name));
        if( ! $tv instanceof modTemplateVar ){ return false; };
        // Prepare data
        $F = new stdClass;
        $F->name = 'tv.'.$field->name;
        $F->title = isset($field->title)? $field->title : $field->name;
        $F->editable = isset($field->editable)? $field->editable : false;
        $F->editor = $this->getTvEditor($field->name); 
    }//
    
    
    /**
     * Return a js renderer function for a TV field
     * @param modTemplateVar $field TV field in question
     * @return string Renderer function
     */
    private function getTvEditor(modTemplateVar $tv){
        
    }//
    
	 
 };// end class grideditorHelper
