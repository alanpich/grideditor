GridEditor.combo.GridFilter = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: GridEditor.config.connectorUrl
        ,baseParams: {
                action: 'grid/getFilterList',
                chunk: config.configChunk
            }
        ,fields: ['name','value']
        ,displayField: 'name'
        ,valueField: 'value'
        ,typeAhead: false
        ,editable: false
        ,value: ''
    });
    GridEditor.combo.GridFilter.superclass.constructor.call(this,config);
};
Ext.extend(GridEditor.combo.GridFilter,MODx.combo.ComboBox);
Ext.reg('grideditor-combo-gridfilter',GridEditor.combo.GridFilter);