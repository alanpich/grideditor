/**
 * GridEditor CMP Grid - the magic
 * 
 * @package grideditor
 * @copyright Alan Pich 2012
 */
GridEditor.grid.GridEditor = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'grideditor-grid-grideditor'
        ,url: GridEditor.config.connectorUrl
        ,baseParams: { action: 'resource/getList' }
        ,paging: true
        ,remoteSort: true
        ,anchor: '97%'
        ,autoExpandColumn: 'name'
        ,autosave: true
        ,columns: this.getColumnsArray()
        ,fields: this.getFieldsArray()
    });
    GridEditor.grid.GridEditor.superclass.constructor.call(this,config)
};
Ext.extend(GridEditor.grid.GridEditor,MODx.grid.Grid,{
    
    
    /**
     * Return array of columns for grid
     * @return Array of Columns
     */
    getColumnsArray: function(){
       var items = [];
       
        // Add in the resource fields
        if(GridEditor.custom.fields){
            for(var k=0;k<GridEditor.config.custom.fields.length;k++){
                var field = GridEditor.config.custom.fields[k];
                items.push({
                    header: field.title,
                    editable: field.editable,
                    editor: {xtype: field.editor},
                    sortable: true,
                    dataIndex: field.name
                });
            };
        };
        
        return items;
        
        // Add in any TV fields
        if(GridEditor.config.custom.tvs){
            for(var k=0;k<GridEditor.config.custom.tvs.length;k++){
                var field = GridEditor.config.custom.tvs[k];
                items.push({
                    header: field.title,
                    editable: field.editable,
                    sortable: true,
                    dataIndex: 'tv.'+field.name
                });
            };
        };
       
        // If controls in use, add another field for them
        if(GridEditor.config.custom.controls && GridEditor.config.custom.controls.length > 0){
           items.push({
               header: '',
               editable: false,
               renderer: GridEditor.controlsRenderer
           })
        };
       
        // Create and return a column model
        return items;
    }//
    
    
    /**
     * Return an array of fields for the data model
     * @return Array Fields
     */
    ,getFieldsArray: function(){
        var fields = new Array();
        // Add resource fields
        if(GridEditor.custom.fields){
            for(var k=0;k<GridEditor.config.custom.fields.length;k++){
                var field = GridEditor.config.custom.fields[k];
                fields.push(field.name);
            };
        };
        
        return fields;
        
        // Add TV fields
        if(GridEditor.config.custom.tvs){
            for(var k=0;k<GridEditor.config.custom.tvs.length;k++){
                var field = GridEditor.config.custom.tvs[k];
                fields.push('tv.'+field.name);
            };
        };
        return fields;
    }
    
});
Ext.reg('grideditor-grid-grideditor',GridEditor.grid.GridEditor);



GridEditor.controlsRenderer = function(value, metadata, record, rowIndex, colIndex, store){
   var controls = GridEditor.config.custom.controls;
   
   var html = '';
 
   // Edit button
   if( controls.indexOf('edit') > -1){
       html+= '<button data-action="edit" data-resid="'+record.json.id+'">edit</button>';
   };
   
   // (un)Publish button
   if( controls.indexOf('publish') > -1){
       var word = record.json.published?  'unpublish' : 'publish';
       var action = record.json.publised? 'unpublish' : 'publish';
       html+= '<button data-action="'+action+'" data-resid="'+record.json.id+'">'+word+'</button>';
   };
   
   // Delete button
   if( controls.indexOf('delete') > -1){
       html+= '<button data-action="delete" data-resid="'+record.json.id+'">delete</button>';
   };
   
   return html;
}