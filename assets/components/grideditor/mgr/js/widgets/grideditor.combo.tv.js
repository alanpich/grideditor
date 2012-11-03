GridEditor.combo.tv = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        displayField: 'name'
        ,valueField: 'value'
        ,fields: ['name','value']
        ,pageSize: 20
        ,url: GridEditor.config.connectorUrl
        ,baseParams: {
                action: 'tv/getComboOptions',
                tvName: config.tvName
            }
        ,typeAhead: false
        ,editable: false
    });
    GridEditor.combo.tv.superclass.constructor.call(this,config);
};
Ext.extend(GridEditor.combo.tv,MODx.combo.ComboBox);
Ext.reg('grideditor-combo-tv',GridEditor.combo.tv);