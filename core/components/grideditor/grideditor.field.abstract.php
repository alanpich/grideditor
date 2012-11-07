<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Abstract Class base for Resource & TV field classes
 *
 * @author alan
 */
abstract class GridEditorField {
    
    /**
     * Becomes true when a field is invalid/unusable
     * @var boolean
     */
    public $isValid = true;
    
    /**
     * The data index key of the field name
     * @var string Field Name
     */
    public  $field = '';
    
    /**
     * Column Label for Grid
     * @var string Text Label
     */
    public  $label = '';
    
    /**
     * Should this field be editable
     * Defaults to false 
     * @var boolean Is editable
     */
    public  $editable = false;
    
    /**
     * Ext object xtype for field editor
     * Defaults to 'textfield'
     * @var string Ext field editor xtype 
     */
    public  $editor = false;
    
    /**
     * TODO
     * Ext object xtype for field renderer
     * Defaults to false (for no renderer)
     * @var string Ext field renderer function
     */
    public  $renderer = false;
    
    /**
     * Whether this field column should be sortable
     * Defaults to true, sorting on
     * @var boolean Can sort on this field?
     */
    public  $sortable = true;
            
    
    
    /**
     * Resource fields allowed
     * @var array of string
     */
    private static $allowedFields = array(
            'contentType','pagetitle',
            'description','alias',
            'link_attributes',' pub_date', 
            'unpub_date','introtext',
            'menutitle','menuindex',
            'template','id'
        );
    
    
    /**
     * Factory Loader - parses input data
     * @param object $data
     * @return GridEditorHelper Instance
     */
    public static function fromInputData( $data ){
        $self = new self($data);
        return $self;
    }//
    
    
    
    public function __construct($data){
        // Check field name is valid
        if(! $this->_is_valid_field($data)){return; /* WARN */ };   
        
        // Field Name
        
        // Get editor config
        $this->get_editor_settings($data);
        // Get editor renderer (TODO);
        $this->get_renderer_settings($data);
        // 
    }//
    
    
    
    
    /**
     * Baseline behavior method for checking if a field is allowed.
     * Override this in extended classes
     * @param data $fieldName Name of field
     * @return boolean Is field name valid?
     */
    private function _is_valid_field($data){
       return true; 
    }//
    
    /**
     * Sets self::editable self::editor appropriately
     * @param type $data
     */
    private function get_editor_settings($data){
        // If no property in data, default to current value
        if(!isset($data->editable)){ $data->editable = $this->editable; }; 
        if($data->editable){
            // Field is editable
            $this->editable = true;
            $this->editor = $this->_get_editor_xtype($data);            
        }
    }//
    
    /**
     * Sets appropriate ext grid field renderer function
     * Defaults to false, for no renderer
     * @param string name of js renderer function
     */
    private function get_renderer_settings($data){
        // If no property set, default to current value
        if(!isset($data->renderer)){ $data->renderer = $this->renderer; };
        // Ensure renderer input is a string
        if(!is_string($data->renderer)){ $this->renderer = false; };
        // Returns an xtype (or false if an error
        $this->renderer = $this->_get_renderer_xtype($data);
    }
    
    
    /**
     * Baseline behavior method for getting a field's editor type. 
     * Override this in extended classes
     * @param type $data
     * @return string
     */
    private function _get_editor_xtype($data){
        // Baseline all fields are text fields
       return 'textfield'; 
    }//

    /**
     * Baseline behavior method for getting a field's renderer ext function name. 
     * Override this in extended classes
     * @param type $data
     * @return string|false Renderer Function name (js) or false for default
     */
    private function _get_renderer_xtype($data){
        // Baseline all fields are text fields
       return false; 
    }//

    
    
    
    
    
};// end abstract class GridEditorField
