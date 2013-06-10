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
        ,collapsible: false
        ,anchor: '97%'
        ,autoExpandColumn: 'name'
        ,autosave: true
        ,border: false
        ,save_action: 'resource/updateFromGrid'
        ,saveParams: {
            chunk: this.grideditor.chunk
        }
        ,columns: this.getColumnsArray()
        ,fields: this.getFieldsArray()
        ,css: {
            verticalAlign: 'middle'
        }
        ,cls: 'grideditor-grid'
        ,tbar:this.getToolbar()
        ,tbarCfg: {
            padding: 10
            ,css: { marginLeft: '30px' }
        }

        ,header: false
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
                if(field.hidden!==true && field.field!=''){
                    items.push({
                        header: field.label,
                        editable: field.editable,
                        editor: field.editor,
                        sortable: field.sortable,
                        dataIndex: field.field,
                        renderer: {
                            fn: eval(field.renderer),
                            scope: this
                        },
                        width: (field.width==false)? null : field.width
                    });
                };
            };
        };

        // If controls in use, add another field for them
        if(this.grideditor.controls){
           items.push(this.getControlButtons())
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
        
        // Add control buttons
        items = items.concat(this.getControlButtons());
                
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
                    if(!this.WarningsWindow){
                        this.WarningsWindow = MODx.load({
                            xtype: 'grideditor-window-warnings'
                            ,warnings: this.grideditor.warnings
                        });
                    };
                    this.WarningsWindow.show();
                }
                ,scope: this
            });
        };
        
        items.push({
            /** Link to documentation */
            id: 'grideditor-help'
            ,qtip: _('grideditor.documentation')
            ,handler: function(){
                window.open(GridEditor.config.documentationUrl);
            }
            ,scope: this
        },{
            /** Refresh the view */
            id:'grideditor-refresh'
            ,qtip: _('grideditor.refresh')
            ,handler: function(){
                this.refresh();
            }
            ,scope: this        
        },{
            /** Link to config chunk editor */
            id:'grideditor-config'
            ,qtip: _('grideditor.edit_config')
            ,handler: function(){
                document.location.href = MODx.config.manager_url+'?a=10&id='+this.grideditor.chunkId
            }
            ,scope: this
        })
        
        return items;
    }//
    
    /**
     * Get required resource controls
     */
    ,getControlButtons: function(){
        var items = [];
        var total = 0;
        
        // Edit Resource link
        if(this.grideditor.controls.indexOf('edit')>-1){
            items.push({
                icon: GridEditor.config.imgUrl+'icons/edit.png',
                tooltip: 'Edit this resource',
                cls: 'grideditor-action-button',
                handler: function(grid,row,col){
                    var resId = grid.getStore().getAt(row).get('id');
                    document.location.href = MODx.config.manager_url+'?a=30&id='+resId;
                }
            });
            total++;
        };
        
         // Edit Resource link
        if(this.grideditor.controls.indexOf('delete')>-1){
            items.push({
                icon: GridEditor.config.imgUrl+'icons/delete.png',
                tooltip: 'Delete this resource',
                cls: 'grideditor-action-button',
                handler: function(grid,row,col){
                    var resId = grid.getStore().getAt(row).get('id');
                    GridEditor.fn.deleteResource(resId,grid);
                }
            });
            total++;
        };
        
        items.push({
            icon: GridEditor.config.imgUrl+'icons/publish.png',
            tooltip: 'Unpublish this resource',
            isPublishButton: true,
            handler: function(){
                console.log(this,arguments);
            }
        })
        
        return {
               xtype: 'actioncolumn',
               header: '',
               editable: false,
               width: (28*total)+20,
               items: items
           };
    }//
   
    /**
     * Filter by search field
     */
    ,search: function(tf,nv,ov) {
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
    
    
    
    ,renderer: {
        /**
         * Checkbox renderer - offer a one-click toggle checkbox
         */
        checkbox: function(value, metadata, record, rowIndex, colIndex, store){
              var elemID = 'grideditor-resource-'+record.json.id+"-"+colIndex+"-checkbox";
              var dataIndex = this.getColumnModel().getDataIndex(colIndex);
              GridEditor.renderer._checkbox.defer(1, null, [elemID, record, this, dataIndex]);
              return '<div id="'+elemID+'" class="grideditor-renderer-checkbox"></div>';
           }//
           
        ,image: function(value, metadata, record, rowIndex, colIndex, store){
                if(value==''){return;};
                var elemID = 'grideditor-resource-'+record.get('id')+"-"+colIndex+"-thumb";
                var dataIndex = this.getColumnModel().getDataIndex(colIndex);
                var width = this.grideditor.fields[dataIndex].width || 50;
                GridEditor.renderer._image.defer(1, this, [elemID, value, width]);
                return '<div id="'+elemID+'"></div>';
            }//
    }
    
});
Ext.reg('grideditor-grid',GridEditor.grid.GridEditor);



