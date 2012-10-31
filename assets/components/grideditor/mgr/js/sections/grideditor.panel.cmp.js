/**
 * GridEditor CMP Main Panel
 * 
 * @package grideditor
 * @copyright Alan Pich 2012
 */
GridEditor.panel.Home = function(config) {
    config = config || {};
    Ext.apply(config,{
        border: false
        ,baseCls: 'modx-formpanel'
        ,cls: 'container'
        ,items: [{
            html: '<h2>'+GridEditor.custom.title+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
        },{
            xtype: 'grideditor-grid-grideditor'
            ,cls: 'main-wrapper'
            ,preventRender: true
        }]
    });
    GridEditor.panel.Home.superclass.constructor.call(this,config);
};
Ext.extend(GridEditor.panel.Home,MODx.Panel);
Ext.reg('grideditor-panel-cmp',GridEditor.panel.Home);