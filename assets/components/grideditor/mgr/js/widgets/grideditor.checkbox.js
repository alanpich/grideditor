GridEditor.checkbox = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        listeners: {
        //    'check': {fn: this.onChange,scope:this}
            'afterrender': {fn: this.onAfterRender,scope:this}
        }        
    });
    GridEditor.checkbox.superclass.constructor.call(this,config);
};
Ext.extend(GridEditor.checkbox,Ext.form.XCheckbox,{
    
    /**
     * Update the record according to checkbox state
     * Trigger a save
     */
    onChange: function(e){
      this.record.set(this.dataIndex,this.getValue());
      this.grid.saveRecord(e);
    }
    
    /**
     * Sets the checkbox state according to input data
     */
    ,onAfterRender: function(){
        this.setValue(this.record.get(this.dataIndex));
        this.on('check',this.onChange,this);
    }
});
Ext.reg('grideditor-checkbox',GridEditor.checkbox);