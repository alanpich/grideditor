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
            'template','id'
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
            'template' => 'modx-combo-template'
        );
    
    /**
     * Is this field a valid resource field?
     * Checks for existance in $this->allowedFields array
     * @param data $fieldName Name of field
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
    
    
    
};// end class GridEditorResourceField