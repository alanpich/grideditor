<?php
/**
 * Description of grideditor
 *
 * @author alan
 */
class GridEditorTvField extends GridEditorField {
    
    public $type = 'tv';
    
    private static $defaultEditors = array(
            'listbox' => 'grideditor-combo-tv',
            'checkbox' => 'modx-combo-boolean',
            'text' => 'textfield',
            'textarea' => 'textarea',
            'richtext' => 'textarea',
            'date' => 'xdatetime',
            'email' => 'textfield',
            'image' => 'textfield',
            'file' => 'textfield',
            'number' => 'textfield'
        );
    
    private static $defaultRenderers = array(
            'checkbox' => 'this.renderer.checkbox',
            'date' => 'GridEditor.renderer.date',
            'image' => 'this.renderer.image'
        );
        
   
    /**
     * Is this field a valid resource field?
     * Checks for existance in $this->allowedFields array
     * @param data $fieldName Name of field
     * @return boolean Is field name valid?
     */
    protected function is_valid_field($data){
       $tvName = $data->field;
       $tv = $this->modx->getObject('modTemplateVar',array('name'=>$tvName));
       return ($tv instanceof modTemplateVar);
    }//
    
    
    
    /**
     * Set this field's editor xtype. If none is specified in config, default is used
     * @param object $data Field data input
     * @return string
     */
    protected function get_editor_xtype($data){
        // If explicitely stated, use that
        if(isset($data->editor) && !empty($data->editor)){
            return $data->editor;
        };
        // Otherwise try and figure it out using TV's input type
        $tv = $this->modx->getObject('modTemplateVar',array('name'=>$data->field));
        // Check against defaults array
        $tvType = $tv->get('type');
        if(isset(self::$defaultEditors[$tvType])){
            $xtype = self::$defaultEditors[$tvType];
        } else {
            // Fallback to text
            $xtype = 'textfield';
        };
        
        $obj = new stdClass;
        $obj->xtype = $xtype;
        
        // Add tvName field to list combo
        if($xtype=='grideditor-combo-tv'){
            $obj->tvName = $data->field;
        }
        
        return $obj;
    }//
    
    
    /**
     * Get Field's renderer ext function name. 
     * @param type $data
     * @return string|false Renderer Function name (js) or false for default
     */
    protected function get_renderer_function($data){
        $renderer = false;
       
        // Has config explicitly set a renderer?
        if(isset($data->renderer) && is_string($data->renderer) && !empty($data->renderer)){
            $renderer = $data->renderer;
        } else {        
        // Otherwise try and figure it out using TV's input type
            $tv = $this->modx->getObject('modTemplateVar',array('name'=>$data->field));
            $tvType = $tv->get('type');
            // Check against defaults array
            if(isset(self::$defaultRenderers[$tvType])){
                $renderer = self::$defaultRenderers[$tvType];
            };
        };       
        return $renderer;
        $obj = new stdClass();
        $obj->fn = $renderer;
        return $obj    ;
    }//
    
    
    
};// end class GridEditorResourceField
