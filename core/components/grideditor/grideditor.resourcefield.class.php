<?php
/**
 * Description of grideditor
 *
 * @author alan
 */
class GridEditorResourceField extends GridEditorField {
    
    public $type = 'resource';
        
    /**
     * Resource fields allowed
     * @var array of string
     */
    private static $allowedFields = array(
            'contentType','pagetitle',
            'description','alias',
            'link_attributes','pub_date', 
            'unpub_date','introtext',
            'menutitle','menuindex',
            'template','id','uri'
        );
    
    private static $fieldEditors = array(
            'contentType' => 'modx-combo-content-type',
            'pagetitle' => 'textfield',
            'description' => 'textarea',
            'alias' => 'textfield',
            'link_attributes' => 'textfield',
            'pub_date' => 'xdatetime', 
            'unpub_date' => 'xdatetime',
            'introtext' => 'textarea',
            'menutitle' => 'textfield',
            'menuindex' => 'textfield',
            'template' => 'modx-combo-template',
            'uri' => 'textfield'
        );
    
    private static $defaultRenderers = array(
            'template' => 'GridEditor.renderer.template',
            'alias' => 'GridEditor.renderer.ResourceUrl'
        );

    /**
     * Is this field a valid resource field?
     * Checks for existance in $this->allowedFields array
     * @param $data
     * @return boolean Is field name valid?
     */
    protected function is_valid_field($data){
       return in_array(strtolower($data->field),self::$allowedFields);
    }//
    
    
    
    /**
     * Set this field's editor xtype. If none is specified in config, default is used
     * @param object $data Field data input
     * @return string
     */
    protected function get_editor_xtype($data){
        if(isset($data->editor) && !empty($data->editor)){
            // If explicitely stated, use that
            $xtype = $data->editor;
        } else {
            // Otherwise use the default from self::$fieldEditors
            $xtype = self::$fieldEditors[$data->field];
        };
        $obj = new stdClass;
        $obj->xtype = $xtype;
        return $obj;
    }//
    
    /**
     * Get field's renderer ext function name. 
     * @param type $data
     * @return string|false Renderer Function name (js) or false for default
     */
    protected function get_renderer_function($data){
        $renderer = false;
        // Has config explicitly set a renderer?
        if(isset($data->renderer) && is_string($data->renderer) && !empty($data->renderer)){
            $renderer = $data->renderer;
        } else {     
            if(isset(self::$defaultRenderers[$data->field])){
                $renderer = self::$defaultRenderers[$data->field];
            };
        };
        return $renderer;
    }//

    
    
    
};// end class GridEditorResourceField
