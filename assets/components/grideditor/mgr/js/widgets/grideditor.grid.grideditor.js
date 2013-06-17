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
        ,paging: true
        ,pageSize: config.grideditor.perPage
        ,remoteSort: true
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

        ,grouping: (this.grideditor.grouping!=null&&this.grideditor.grouping!='')
        ,groupBy: (this.grideditor.grouping!=null)? this.grideditor.grouping.field : null
        ,singleText: (this.grideditor.grouping!=null)? 'item':''
        ,pluralText: (this.grideditor.grouping!=null)? 'items':''

        ,listeners: {
            beforerender: {fn: function(){
                this.store.grid = this;
            },scope:this}
        }

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
                }
            }
        }

        // If controls in use, add another field for them
        if(this.grideditor.controls){
           items.push({
               header:'',
               editable: false,
               sortable: false,
               width: 165,
               fixed: true,
               renderer: GridEditor.renderer.actionButtons
           })
        }
        
        // If publish is a control, add it's own columns at the beginning
        if(this.grideditor.controls.indexOf('publish')>-1){
            items.unshift({
                header:'',
                editable: false,
                sortable: true,
                width: 35,
                fixed: true,
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
            }
        }
        return fields;
    }
    
    
    /**
     * Return array of items for context menu
     * @return Array items
     */
    ,getMenu: function(grid,index){
        var items = [];
        if(!this.grideditor.controls){ return items; };

        var record = grid.store.data.items[index];


        // View action
        items.push({
            text: 'View',
            handler: function(record){return function(menu){
                this.viewResource(record);
            }}(record),
            scope: this
        });

        // Publish state toggler
        if(grid.grideditor.controls.indexOf('publish')>-1){
            var action = record.json.published? 'Unpublish' : 'Publish';
            items.push({
                text: action,
                resId: record.id,
                isPublished: record.json.published,
                handler: function(menu){
                    if(menu.options.isPublished){
                        // Unpublish resource
                        GridEditor.fn.unpublishResource(record,grid);
                    } else {
                        // Publish resource
                        GridEditor.fn.publishResource(record,grid);
                    }
                }
            })
        }

        // Edit action (if enabled)
        if(this.grideditor.controls.indexOf('edit')>-1){
            items.push({
                text: 'Edit',
                handler: function(record){return function(menu){
                    GridEditor.fn.editResource(record,grid);
                }}(record),
                scope: this
            });
        }

        // Delete action (if enabled)
        if(this.grideditor.controls.indexOf('delete')>-1){
            items.push({
                text: 'Delete',
                handler: function(record){return function(menu){
                    GridEditor.fn.deleteResource(record,grid);
                }}(record),
                scope: this
            });
        }


        return items;
    }//
    
     
    /** 
     * Get Grid Toolbar
     */
    ,getToolbar: function(){
        var items = [];

        // If 'new' is specified, show a 'Create Resource' button
        if(this.grideditor.controls.indexOf('new')!== -1){
            items.push({
                xtype: 'button'
                ,text: 'New '+this.grideditor.resourceName
                ,handler: function(){
                    this.createResource()
                }
                ,scope: this
            })
        };

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

        // After this align right
        items.push('->');

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


        // Add 'clear filters' button
        items.push({
            xtype: 'button'
            ,text: 'Clear filters'
            ,handler: function(){
                alert('click');
            }
            ,scope: this
        })
        


        return items;
    }//


    ,getActionCombo: function(){
        return {
             header: _('usergroup')
            ,dataIndex: 'usergroup'
            ,width: 140
            ,order:3
            ,renderer: GridEditor.renderer.actionCombo
        }
    }

    /**
     * Filter by search field
     */
    ,search: function(tf,nv,ov) {
        var s = this.getStore();
        s.baseParams.query = tf.getValue();
        this.getBottomToolbar().changePage(1);
        this.refresh();
    }
    

    /**
     * Filter by dropdown value
     */
    ,filter: function(tf,nv,ov){
        var s = this.getStore();
        s.baseParams.filter = tf.getValue();
        this.getBottomToolbar().changePage(1);
        this.refresh();
    }
    

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


    /**
     * Create a new resource
     */
    ,createResource: function(){

        // Hold data params and field items
        var data = {};
        var items = [];

        for(var key in this.grideditor.newResourceDefaults){
            var value = this.grideditor.newResourceDefaults[key];

            // If parent has been specified, and is an array
            if(key === 'parent' && Ext.isObject(value)){
                items.push({
                     xtype: 'modx-combo'
                    ,fieldLabel: value.label || 'Select Parent resource'
                    ,url: GridEditor.config.connectorUrl
                    ,baseParams: {
                        action: 'resource/getlistbyid',
                        ids: value.data.join(',')
                    }
                    ,fields: ['id','pagetitle']
                    ,displayField: 'pagetitle'
                    ,forceSelection: true
                    ,value: value.data.shift()
                    ,valueField: 'id'
                    ,name: 'parent'
                    ,anchor: '100%'
                })
            } else {
                data[key] = value;
            }

        }

        if(items.length >= 1){
            this._showNewResourceOptionWindow(data,items);
        } else {
            this._redirectCreateResource(data);
        }
    }


    /**
     * Displays a modal window to
     */
    ,_showNewResourceOptionWindow: function(data,items){

        this._tmp_createResource_data = data;

        var newResourceParamsWindow = Ext.create({
            xtype: 'modx-window',
            title: 'Options',
            fields: items,
            modal: true,
            collapsible: false,
            resizable: false,
            buttons: [{
                text: 'Create'
                ,handler: function(btn){
                    this._onCreateFormSubmit(btn.ownerCt.ownerCt.fp.getForm());
                }
                ,scope: this
            },{
                text: 'Cancel'
                ,handler: function(btn){
                    btn.ownerCt.ownerCt.destroy();
                }
            }]
        }).show()

    }


    /**
     * Redirect user to resource/create action
     */
    ,_redirectCreateResource: function( params ){

        // Create url string
        var action = MODx.action['resource/create'];
        var url = Ext.urlAppend(MODx.config.manager_url,'a='+action);

        // Add default params
        for( key in params ){

            // Convert booleans
            var value = (Ext.isBoolean(params[key])) ? params[key] === true ? 1 : 0 : params[key];

            url = Ext.urlAppend(url,key+'='+value);
        }

        // Do the redirect
        document.location.href = url;
    }



    ,_onCreateFormSubmit: function(form){
        console.log(form.getFieldValues())

        var data = this._tmp_createResource_data;
        Ext.apply(data,form.getFieldValues());

        this._redirectCreateResource(data);
    }



});
Ext.reg('grideditor-grid',GridEditor.grid.GridEditor);



