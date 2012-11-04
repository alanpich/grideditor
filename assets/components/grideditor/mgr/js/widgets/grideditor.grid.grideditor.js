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
        ,baseParams: { 
               action: 'resource/getList'
               ,config: GridEditor.custom.chunk
           }
        ,paging: false
        ,remoteSort: false
        ,anchor: '97%'
        ,autoExpandColumn: 'name'
        ,autosave: true
        ,save_action: 'resource/updateFromGrid'
        ,columns: this.getColumnsArray()
        ,fields: this.getFieldsArray()
        ,css: {
            verticalAlign: 'middle'
        }
        ,tbar:this.getToolbar()
        ,searchBox: false
        ,filterBox: false

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
            for(var k=0;k<GridEditor.custom.fields.length;k++){
                var field = GridEditor.custom.fields[k];
                if(!field.hidden || field.hidden!=true){
                    items.push({
                        header: field.title,
                        editable: field.editable,
                        editor: field.editor,
                        sortable: field.sortable,
                        dataIndex: field.field
                    });
                };
            };
        };

        // If controls in use, add another field for them
        var min = (GridEditor.custom.controls&&GridEditor.custom.controls.indexOf('publish')>-1)? 1 : 0;
        var total = GridEditor.custom.controls.length - min;
        if(GridEditor.custom.controls && GridEditor.custom.controls.length > min){
           items.push({
               header: '',
               editable: false,
               width: (22*total),
               renderer: GridEditor.controlsRenderer
           })
        };
        
        // If publish is a control, add it's own columns at the beginning
        if(GridEditor.custom.controls.indexOf('publish')>-1){
            items.unshift({
                header:'',
                editable: false,
                sortable: true,
                width: 20,
                renderer: GridEditor.renderer.publishToggle
            })
        }
       
        // Create and return a column model
        return items;
    }//
    
    
    /**
     * Return an array of fields for the data model
     * @return Array Fields
     */
    ,getFieldsArray: function(){
        var fields = new Array('published');
        // Add resource fields
        if(GridEditor.custom.fields){
            for(var k=0;k<GridEditor.custom.fields.length;k++){
                var field = GridEditor.custom.fields[k];
                fields.push(field.field);
            };
        };
        return fields;
    }
    
    
    /**
     * Return array of items for context menu
     * @return Array items
     */
    ,getMenu: function(){
        var items = [];
        if(!GridEditor.custom.controls){ return items; };
        
        // Publish state toggler
        if(GridEditor.custom.controls.indexOf('publish')>-1){
            var action = this.menu.record.published? 'Unpublish' : 'Publish';
            items.push({
                text: action,
                handler: function(){
                    alert('TODO: toggle publish state');
                }
            })
        }
                
        // Edit resource link
        if(GridEditor.custom.controls.indexOf('edit')>-1){
            items.push({
                text: 'Edit',
                handler: function(){
                    var url = MODx.config.manager_url+'?a=30&id='+this.menu.record.id;
                    document.location.href = url;
                }
            })
        };
 
         // Delete resource link
        if(GridEditor.custom.controls.indexOf('delete')>-1){
            items.push({
                text: _('delete'),
                handler: function(){
                    GridEditor.deleteResource( this.menu.record.id, this )
                }
            })
        };

 
        return items;
    }//
    
     
    /** 
     * Get Grid Toolbar
     */
    ,getToolbar: function(){
        var items = [];
        
        // Add search box
        items.push({
            xtype: 'textfield'
            ,id: 'grideditor-search-filter'
            ,emptyText: 'Search'//_('grideditor.search...')
            ,listeners: {
                'change': {fn:this.search,scope:this}
                ,'render': {fn: function(cmp) {
                    new Ext.KeyMap(cmp.getEl(), {
                        key: Ext.EventObject.ENTER
                        ,fn: function() {
                            this.fireEvent('change',this);
                            this.blur();
                            return true;
                        }
                        ,scope: cmp
                    });
                },scope:this}
            }
        });
        
        // If filter is set, show a filterbar
        if(GridEditor.custom.filter && GridEditor.custom.filter.field != ''){
            items.push({
            xtype: 'grideditor-combo-gridfilter'
            ,emptyText: GridEditor.custom.filter.label
            ,title: GridEditor.custom.filter.label
            ,listeners: {
                'select': {fn:this.filter,scope:this}
            }
        })
        }
        
        return items;
    }//
    
   
    /**
     * Filter by search field
     */
    ,
    search: function(tf,nv,ov) {
        var s = this.getStore();
        this.searchBox = tf;
        s.filterBy(this.recordMatchesFilter)
    }
    

    /**
     * Filter by dropdown value
     */
    ,filter: function(tf,nv,ov){
        var s = this.getStore();
        this.filterBox = tf;
        s.filterBy(this.recordMatchesFilter);
    }
    
    /**
     * The actual filtering function. Acts on each record 
     * @return boolean Row matches filter
     */
    ,recordMatchesFilter: function(record){
        var fields = record.data;
        var grid = Ext.getCmp('grideditor-grid-grideditor');
        
        // Do string search
        var str = grid.searchBox? grid.searchBox.getValue() : '';
        if( str != ''){
            // Count all hits
            var points = 0;
            // Generate RegExp pattern from search string
            var pattern = new RegExp(str,'gim');
            // Test all search fields 
            for(var k=0; k< GridEditor.custom.searchFields.length; k++){
                var searchField = GridEditor.custom.searchFields[k];
                if( pattern.test( fields[searchField] ) ){
                    points++;
                };
            };
            // If no points, fail
            if(points==0){ return false};
        };
        
        // Can assume this field matches on search (or there is no search)...
        
        // Do filter search
        var filterValue = grid.filterBox? grid.filterBox.getValue() : '';
        if(grid.filterBox && filterValue != ''){
            var filterField = GridEditor.custom.filter.field;
            var filterPattern = new RegExp(filterValue,'gim');
            return filterPattern.test(fields[filterField]);
        };
        
        // Default to false
        return true;
    }//
    
    
});
Ext.reg('grideditor-grid-grideditor',GridEditor.grid.GridEditor);



