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
     * Sort order for grid columns
     * @var int
     */
    public $order = 0;
    
    /**
     * Is this field hidden from the grid?
     * @var boolean
     */
    public $hidden = false;
    
    /**
     * Width (in pixels) that the grid column should aim for
     * @var int
     */
    public $width = false;
             
    
    protected $modx;
    
    /**
     * Factory Loader - parses input data
     * @param object $data Field data
     * @return GridEditorHelper Instance
     */
    public static function fromInputData( $data ){
        $self = new self($data);
        return $self;
    }//
    
    
    
    public function __construct($data, modX &$modx){
        // Reference modX class
        $this->modx =& $modx;
        
        // Check field name is valid
        if(! $this->is_valid_field($data)){$this->isValid = false; return; /* WARN */ };
        
        // Field name
        $this->field = $data->field;        
        // Get label settings
        $this->_get_field_label($data);
        // Get editor config
        $this->_get_editor_settings($data);
        // Get editor renderer (TODO);
        $this->_get_renderer_settings($data);
        // Sort Order
        $this->order = isset($data->order)? (int) $data->order : $this->order;
        // Hidden
        $this->hidden = isset($data->hidden)? $data->hidden : $this->hidden;
        // Sortable column
        $this->sortable = isset($data->sortable)? $data->sortable : $this->sortable;
        // Column width
        $this->width = isset($data->width)? (int) $data->width : $this->width;
        
        // Kill off modx reference
        unset($this->modx);
    }//
    
    
    
    /**
     * Set the field label to an appropriate value
     * @param object $data Field data input
     */
    protected final function _get_field_label($data){
       $this->label = $this->get_field_label($data);
    }// 
    
    /**
     * Get a label for this field
     */
    protected function get_field_label($data){
       if(isset($data->label) && !empty($data->label)){ return $data->label; };
       return $data->field;
    }//
    
    /**
     * Baseline behavior method for checking if a field is allowed.
     * Override this in extended classes
     * @param data $fieldName Name of field
     * @return boolean Is field name valid?
     */
    protected function is_valid_field($data){
       return true; 
    }//
    
    /**
     * Sets self::editable self::editor appropriately
     * @param type $data
     */
    protected final function _get_editor_settings($data){
        // If no property in data, default to current value
        if(!isset($data->editable)){ $data->editable = $this->editable; }; 
        if($data->editable){
            // Field is editable
            $this->editable = true;
            $this->editor = $this->get_editor_xtype($data);           
        } else {
            // Field is not editable
            $this->editable = false;
            $this->editor = new stdClass();
        }
    }//
    
    /**
     * Sets appropriate ext grid field renderer function
     * Defaults to false, for no renderer
     * @param string name of js renderer function
     */
    protected final function _get_renderer_settings($data){
        // If no property set, default to current value
        if(!isset($data->renderer)){ $data->renderer = $this->renderer; };
        // Ensure renderer input is a string
        if(!is_string($data->renderer) || empty($data->renderer)){ $this->renderer = false; };
        // Returns an xtype (or false if an error
        $this->renderer = $this->get_renderer_function($data);
    }
    
    
    /**
     * Baseline behavior method for getting a field's editor type. 
     * Override this in extended classes
     * @param type $data
     * @return object
     */
    protected function get_editor_xtype($data){
        // Baseline all fields are text fields
       $obj = new stdClass;
       $obj->xtype = 'textfield'; 
       return $obj;
    }//

    /**
     * Baseline behavior method for getting a field's renderer ext function name. 
     * Override this in extended classes
     * @param type $data
     * @return string|false Renderer Function name (js) or false for default
     */
    protected function get_renderer_function($data){
        // Baseline display raw value
       return false; 
    }//

    
    
    
    
    
};// end abstract class GridEditorField
