/**
 * GridEditor CMP Grid - the magic
 * 
 * @package grideditor
 * @copyright Alan Pich 2012
 */
GridEditor.grid.GridEditor = function(config) {
    config = config || {};
    this.grideditor = config.grideditor;
    Ext.applyIf(config,{
        id: 'grideditor-grid-grideditor'
        ,renderTo: config.renderTo
        ,url: GridEditor.config.connectorUrl
        ,baseParams: { 
               action: 'resource/getList'
               ,chunk: this.grideditor.chunk
           }
        ,paging: false
        ,remoteSort: false
        ,anchor: '97%'
        ,autoExpandColumn: 'name'
        ,autosave: true
        ,save_action: 'resource/updateFromGrid'
        ,saveParams: {
            chunk: this.grideditor.chunk
        }
        ,columns: this.getColumnsArray()
        ,fields: this.getFieldsArray()
        ,css: {
            verticalAlign: 'middle'
        }
        ,tbar:this.getToolbar()
        ,tbarCfg: {
            padding: 10
            ,css: { marginLeft: '30px' }
        }

        ,header: true
        ,headerAsText: true
        ,title: this.grideditor.title
        ,searchBox: false
        ,filterBox: false
        ,tools: this.getTools()
    
        ,grouping: (this.grideditor.grouping!=null&&this.grideditor.grouping!='')
        ,groupBy: (this.grideditor.grouping!=null)? this.grideditor.grouping.field : null
        ,singleText: (this.grideditor.grouping!=null)? 'item':''
        ,pluralText: (this.grideditor.grouping!=null)? 'items':''

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
        if(this.grideditor.fields){
            for(var k in this.grideditor.fields){
                var field = this.grideditor.fields[k];
                if(field.hidden!==true){
                    items.push({
                        header: field.label,
                        editable: field.editable,
                        editor: field.editor,
                        sortable: field.sortable,
                        dataIndex: field.field,
                        width: (field.width==false)? null : field.width
                    });
                };
            };
        };

        // If controls in use, add another field for them
        var min = (this.grideditor.controls&&this.grideditor.controls.indexOf('publish')>-1)? 1 : 0;
        var total = this.grideditor.controls.length - min;
        if(this.grideditor.controls && this.grideditor.controls > min){
           items.push({
               header: '',
               editable: false,
               width: (22*total),
               renderer: GridEditor.renderer.resourceControls
           })
        };
        
        // If publish is a control, add it's own columns at the beginning
        if(this.grideditor.controls.indexOf('publish')>-1){
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
        if(this.grideditor.fieldList){
            for(var k=0;k<this.grideditor.fieldList.length;k++){
                fields.push(this.grideditor.fieldList[k]);
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
        if(!this.grideditor.controls){ return items; };
        
        // Publish state toggler
        if(this.grideditor.controls.indexOf('publish')>-1){
            var action = this.menu.record.published? 'Unpublish' : 'Publish';
            items.push({
                text: action,
                resId: this.menu.record.id,
                isPublished: this.menu.record.published,
                handler: function(menu){
                    if(menu.options.isPublished){
                        // Unpublish resource
                        GridEditor.fn.unpublishResource(menu.options.resId);
                    } else {
                        // Publish resource
                        GridEditor.fn.publishResource(menu.options.resId);
                    }
                }
            })
        }
                
        // Edit resource link
        if(this.grideditor.controls.indexOf('edit')>-1){
            items.push({
                text: 'Edit',
                handler: function(){
                    var url = MODx.config.manager_url+'?a=30&id='+this.menu.record.id;
                    document.location.href = url;
                }
            })
        };
 
         // Delete resource link
        if(this.grideditor.controls.indexOf('delete')>-1){
            items.push({
                text: _('delete'),
                handler: function(){
                    GridEditor.fn.deleteResource( this.menu.record.id, this )
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
            ,margin: 10
            ,emptyText: 'Search...'//_('grideditor.search...')
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
        if(this.grideditor.filter && this.grideditor.filter.field != ''){
            items.push({
                xtype: 'grideditor-combo-gridfilter'
                ,emptyText: this.grideditor.filter.label
                ,title: this.grideditor.filter.label
                ,configChunk: this.grideditor.chunk
                ,listeners: {
                    'select': {fn:this.filter,scope:this}
                }
            })
        };
        
        // If 'new' is specified, show a 'Create Resource' button
        if(this.grideditor.controls.indexOf('new')!== -1){
            items.push('->');
            items.push({
                xtype: 'button'
                ,text: 'Create Resource'
                ,handler: function(){
                    // &a=55&class_key=modDocument&parent=38&context_key=web
                    document.location.href = MODx.config.manager_url+'?a=55&class_key=modDocument&parent='+this.grideditor.parentResourceId+'&context_key=web'
                }
            })
        };
        
        return items;
    }//
    
    
    /**
     * Get header tool buttons
     */
    ,getTools: function(){
        var items = [];
        
        // Add warnings button (if there are warnings)
        if(this.grideditor.warnings.length > 0){
            items.push({
                id: 'grideditor-warning'
                ,qtip: _('grideditor.warnings.total',{total:this.grideditor.warnings.length})
                ,handler: function(){
                    var Win = MODx.load({
                        xtype: 'grideditor-window-warnings'
                        ,warnings: this.grideditor.warnings
                    });
                    Win.show();
                }
                ,scope: this
            });
        };
        
        // Add help link
        items.push({
            id: 'grideditor-help'
            ,qtip: _('grideditor.documentation')
            ,handler: function(){}
            ,scope: this
        })
        
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
            for(var k=0; k< grid.grideditor.searchFields.length; k++){
                var searchField = grid.grideditor.searchFields[k];
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
            var filterField = grid.grideditor.filter.field;
            var filterPattern = new RegExp(filterValue,'gim');
            return filterPattern.test(fields[filterField]);
        };
        
        // Default to false
        return true;
    }//
    
    
    /**
     * Show configuration warnings window
     */
    ,showWarnings: function(){
      var Win = MODx.load({
            xtype: 'grideditor-window-warnings'
            ,warnings: this.grideditor.warnings
        });
        Win.show();  
    }//
    
    
});
Ext.reg('grideditor-grid-grideditor',GridEditor.grid.GridEditor);



