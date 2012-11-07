<?php
/**
 * Description of grideditor
 *
 * @author alan
 */
class GridEditorConfiguration{
        
    /**
     * Holds all config warnings
     * @var array
     */
    public $warnings = array();
    
    /**
     * Page title
     * @var string $title
     */
    public $title = '';
    
    /**
     * Templates to restrict to
     * @var array of int $templates
     */
    public $templates = array();
    
    /**
     * Field & label to use for dropdown filtering
     * @var stdClass $filter
     */
    public $filter = false;
    
    /**
     * Fields to use for text search
     * @var array of string $fields
     */
    public $search = array();
    
    /**
     * Field to group resources by
     * @var $group
     */
    public $group = false;
    
    /**
     * Resource Controls to display
     * @var array of string $controls
     */
    public $controls = array();
    
    /**
     * Fields to show in grid
     * @var array of GridEditorFieldObject $fields
     */
    public $fields = array();
    
    
    /**
     * Reference to a MODx instance
     * @private modX $modx
     */
    private $modx;
    
    
    /**
     * @static Create an istance populated from a config chunk
     * @param string $chunkName Name of chunk to use
     * @param modX $modx Modx Instance
     * @return GridEditorConfiguration instance
     */
    public static function fromChunk( $chunkName, modX &$modx ){
        // Create a new instance
        $config = new self($modx);
        // Try to grab the chunk
        $chunk = &$modx->getObject('modChunk',array('name' => $chunkName ));
        // If chunk doesnt exists, bail out and record warning
        if(!$chunk instanceof modChunk){
            $config->warning('Specified configuration chunk does not exist');
            return $config;
        };
        $chunk = $chunk->process();
        // If chunk is empty, bail out and record warning
        if(empty($chunk)){
            $config->warning('Specified configuration chunk is empty');
            return $config;
        };
        // Populate config from JSON string
        $config->fromJSON($chunk,$modx);
        // Return the config object
        return $config;
    }//
    
    
    /**
     * Constructor -> generate from input object
     */
    public function __construct(modX &$modx){
        $this->modx = $modx;
    }//
    
    /**
     * Log a warning message
     * @param string $msg Warning Message
     */
    private function warning( $msg ){  $this->warnings[] = $msg;  }//
    
    
    /**
     * Populate object from an object.
     * Adds warnings to stack for any malformed or missing params
     */
    private function fromJSON($json){
        // Minify json
        $json = json_minify($json);
        // Attempt to parse JSON
        if(!$data = json_decode($json)){
            $this->warning("Configuration chunk appears to be malformed. Decoding failed.");
            return false;
        };
        
        // Check for a page title
        if(!isset($data->title)){
            $this->warning("Property `title` omitted, using default from lexicon");
            $this->title = $this->modx->lexicon('grideditor.defaults.title');
        } else {
            $this->title = $data->title;
        };
        
        // Prepare Fields
        $this->prepareResourceFields();
        $this->prepareTvFields();
        
        // Parse and prepare templates array
        if(!is_null($data->templates) && !is_array($data->templates)){
            // If templates param is specified, but not an array, throw a warning
            $this->warning("Property `templates` specified but of wrong type. Type `".gettype($data->templates)."` is not `array`");
        } else {
            // Parse and sanitize templates array
            if(!$this->prepareTemplates($data->templates)){ /* return false; */ };
        };
        
        
        
        
    }//
    
    
    /**
     * Parses and prepares templates array. Converts template names to IDs and 
     * removes invalid ids/names while issuing a warning
     * @param array $templates
     * @return boolean Valid
     */
    private function prepareTemplates($templates){
       for($k=0;$k<count($templates);$k++){
           $tpl = $templates[$k];
           
           if( is_integer($tpl) ){
               // Use as template ID
               $modTpl = $this->modx->getObject('modTemplate',$tpl);
               // Bail out & warn if template doesnt exist
               if(!$modTpl instanceof modTemplate){
                   $this->warning('Invalid item in property `templates` - ['.$tpl.'] is not a valid Template ID');
                   return false;
               };
               // Assume template exists then
               $this->templates[] = $tpl;
           } else {
               // Use as template name
               $modTpl = $this->modx->getObject('modTemplate',array('name'=>$tpl));
               // Bail out & warn if template doesnt exist
               if(!$modTpl instanceof modTemplate){
                   $this->warning('Invalid item in property `templates` - ['.$tpl.'] is not a valid Template name');
                   return false;
               };       
               $this->templates[] = $modTpl->get('id');
           };
           
       } 
    }//
    
    
};// end class GridEditorConfiguration