GridEditor.combo.ResourceActions = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        store: new Ext.data.ArrayStore({
            fields: ['name','value']
            ,data: [
                 ['Edit','edit']
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
            change: {fn: function(){
                alert('change');
            }, scope:this }
            ,select: {fn: function(){

                var value = this.getValue();

                this.setValue(null);
                this.fireEvent('blur');

            },scope:this}
        }
    });
    GridEditor.combo.ResourceActions.superclass.constructor.call(this,config);
};
Ext.extend(GridEditor.combo.ResourceActions,Ext.form.ComboBox,{


    _onChange: function(){
        console.log('CHANGE',arguments);
    }

});
Ext.reg('grideditor-combo-resourceactions',GridEditor.combo.ResourceActions);
