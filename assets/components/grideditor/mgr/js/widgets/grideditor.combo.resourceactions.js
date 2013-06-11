GridEditor.combo.ResourceActions = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        store: new Ext.data.ArrayStore({
            fields: ['name','value']
            ,data: [
                 ['Edit','edit']
                ,['View','view']
                ,['Delete','delete']
            ]
        })
        ,mode: 'local'
        ,fields: ['name','value']
        ,displayField: 'name'
        ,valueField: 'value'
        ,typeAhead: false
        ,editable: false
        ,forceSelection: true
        ,emptyText: 'Select Action'
        ,listeners: {
            select: {fn: this._onActionSelected, scope:this}
        }
    });
    GridEditor.combo.ResourceActions.superclass.constructor.call(this,config);
};
Ext.extend(GridEditor.combo.ResourceActions,Ext.form.ComboBox,{


    _onActionSelected: function(){

        var action = this.getValue();

        // @TODO Find a way to properly unselect action combo on selection
        this.setValue(null);
        this.fireEvent('blur');

        switch(action){
            case 'edit':    this._editAction();
                             break;
            case 'view':    this._viewAction();
                             break;
            case 'delete':  this._deleteAction();
                             break;
        }
    }


    /**
     * Open a window to view a resource in the front-end
     */
    ,_viewAction: function(){
        // Construct url
        var url = MODx.config.site_url + this.record.data.uri;
        // Open a window
        window.open(url);
    }

    /**
     * Go to resource edit screen
     */
    ,_editAction: function(){
        var action = MODx.action['resource/update'];
        var url = MODx.config.manager_url+'?a='+action+'&id='+this.record.data.id;
        console.log(url);
        document.location.href = url;
    }

    /**
     * Delete Resource
     */
    ,_deleteAction: function(){
        console.log('//@TODO Implement method deleteAction');
    }




});
Ext.reg('grideditor-combo-resourceactions',GridEditor.combo.ResourceActions);
