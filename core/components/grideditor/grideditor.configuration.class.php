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
    public $searchFields = array();
    
    /**
     * Field to group resources by
     * @var $group
     */
    public $group = false;
    
    /**
     * Resource Controls to display
     * @var array of string $controls
     */
    public $controls = array('publish','edit','delete','new');
    
    /**
     * Fields to show in grid
     * @var array of GridEditorFieldObject $fields
     */
    public $fields = array();
    
    /**
     * Array of field names for quick reference
     * @var array of string Field Names
     */
    public $fieldList = array();
    
    /**
     * Config Chunk suffix (name)
     * @var string
     */
    public $chunk = 'demo';
    
    /**
     * Reference to a MODx instance
     * @private modX $modx
     */
    private $modx;
    
    
    /**
     * @static Create an istance populated from a config chunk
     * @param string $chunkName Name of chunk to use
     * @param modX $modx Modx Instance
     * @param string $chunkPrefix. System prefix of chunk name.
     * @return GridEditorConfiguration instance
     */
    public static function fromChunk( $chunkName, modX &$modx, $chunkPrefix = '' ){
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
        // Save the chunk name
        $config->chunk = str_replace($chunkPrefix,'',$chunkName);
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
        $this->prepareResourceFields($data);
        $this->prepareTvFields($data);
        $this->prepareFieldOrder();
        
        // Add resource ID as a hidden resource field
        $id = new stdClass;
        $id->field = 'id';
        $id->hidden = true;
        $this->fields['id'] = new GridEditorResourceField($id,$this->modx);
        $this->fieldList[] = 'id';
        
        // Add published status as a hidden resource field
        $published = new stdClass;
        $published->field = 'published';
        $published->hidden = true;
        $published->label = 'Hide ME!!!!!!!!';
        $this->fields['published'] = new GridEditorResourceField($published,$this->modx);
        $this->fieldList[] = 'published';
        
        // Prepare searching & filtering
        $this->prepareSearchFields($data);
        $this->prepareFilterInfo($data);
        $this->prepareGroupingField($data);
        
        // Prepare control object
        $this->prepareGridControls($data);
        
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
               $modTpl = $this->modx->getObject('modTemplate',array('templatename'=>$tpl));
               // Bail out & warn if template doesnt exist
               if(!$modTpl instanceof modTemplate){
                   $this->warning('Invalid item in property `templates` - ['.$tpl.'] is not a valid Template name');
                   return false;
               };       
               $this->templates[] = $modTpl->get('id');
           };
           
       } 
    }//
    
    
    private function prepareResourceFields($fields){
        if(!isset($fields->fields) || count($fields->fields)<1){ return $this->warning('No resource fields specified'); };
        $fields = $fields->fields;
        foreach($fields as $field){
            $fieldObj = new GridEditorResourceField($field,$this->modx);
            if(!$fieldObj->isValid){
                $this->warning(array(
                        'key' => 'invalid_resource_field'
                        ,'data' => array(
                            'field' => $field->field
                        )
                    ));
                continue;
            };
            $this->fields[$fieldObj->field] = $fieldObj;
            $this->fieldList[] = $fieldObj->field;
        }
    }//
    
    private function prepareTvFields($fields){
        if(!isset($fields->tvs) || count($fields->tvs)<1){ return $this->warning('No TV fields specified'); };
        $fields = $fields->tvs;
        foreach($fields as $field){
            $fieldObj = new GridEditorTvField($field,$this->modx);
            if(!$fieldObj->isValid){
                $this->warning(array(
                        'key' => 'invalid_tv_field'
                        ,'data' => array(
                            'field' => $field->field
                        )
                    ));
                continue;
            };
            $this->fields[$fieldObj->field] = $fieldObj;
            $this->fieldList[] = $fieldObj->field;
        }
    }//
    
    /**
     * Sort all fields according to $order param. Not set defaults to zero
     */
    private function prepareFieldOrder(){
        $sorts = array();
        foreach($this->fields as $key => $field){
            $sorts[$key] = $field->order;
        };
        array_multisort($sorts,$this->fields);  
    }
   
    
    
    /**
     * Sanitize controls input to legitimate control names
     * @param type $data
     */
    private function prepareGridControls($data){
        if(!isset($data->controls) || count($data->controls)<1){ return; };
        $data = $data->controls;
        $controls = array();
        foreach($this->controls as $key => $val){
            if(in_array($val,$data)){
                $controls[] = $val;
            }            
        };
        $this->controls = $controls;
    }//
    
    
    /**
     * Check all listed search fields are valid. Only add valid ones. Warn others.
     * @param array $fields
     * @return bool
     */
    private function prepareSearchFields($fields){
       if(!isset($fields->search) || count($fields->search)<1){ return; };
       $fields = $fields->search;
       foreach($fields as $field){
           if(!in_array($field,$this->fieldList)){ 
               $this->warning(array(
                        'key' => 'invalid_search_field'
                        ,'data' => array(
                            'field' => $field
                        )
                    ));
               continue;
           }
           $this->searchFields[] = $field;
       } 
       return true;
    }//
    
    /**
     * Check filter field is valid
     * @param object $info
     * @return boolean
     */
    private function prepareFilterInfo( $info ){
        if(!isset($info->filter)){ return; };
        $info = $info->filter;
        if(!isset($info->field) || empty($info->field)){ return $this->warning('No filter field specified'); };
        if(!in_array($info->field,$this->fieldList)){ return $this->warning('Ignoring filter field ['.$info->field.'] as does not appear in resource or tv list'); };        
        $this->filter = new stdClass;
        $this->filter->field = $info->field;
        $this->filter->label = isset($info->label)? $info->label : $info->field;
    }//    
    
    /**
     * Check the selected grouping field is valid, and set it
     * @param object $info
     * @return boolean
     */
    private function prepareGroupingField($info){
        if(!isset($info->grouping)){ return; };
        $info = $info->grouping;
        if(!isset($info->field) || empty($info->field)){ return $this->warning('No grouping field specified'); };
        if(!in_array($info->field,$this->fieldList)){ return $this->warning('Ignoring grouping field ['.$info->field.'] as does not appear in resource or tv list'); };        
        $this->grouping = new stdClass;
        $this->grouping->field = $info->field;
        $this->grouping->label = isset($info->label)? $info->label : 'Filter results';
    }//
    
    
};// end class GridEditorConfiguration