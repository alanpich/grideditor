/**
 * GridEditor CMP page
 * 
 * @package grideditor
 * @copyright Alan Pich 2012
 */
Ext.onReady(function() {
    MODx.load({ xtype: 'grideditor-page-cmp'});
});
 
GridEditor.page.CMP = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        components: [{
           xtype: 'grideditor-panel-cmp'
            ,renderTo: 'grideditor-cmp-div'
        }]
    });
    GridEditor.page.CMP.superclass.constructor.call(this,config);
};
Ext.extend(GridEditor.page.CMP,MODx.Component);
Ext.reg('grideditor-page-cmp',GridEditor.page.CMP);