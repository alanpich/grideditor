/**
 * GridEditor CMP Main Panel
 * 
 * @package grideditor
 * @copyright Alan Pich 2012
 */
GridEditor.panel.CMP = function(config) {
    config = config || {};
    Ext.apply(config,{
        border: false
        ,baseCls: 'modx-formpanel'
        ,cls: 'container'
        ,items: [{
             html: '<h2>'+config.grideditor.title+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
        },{
            xtype: 'grideditor-grid',
            grideditor: config.grideditor
        }]
        ,listeners: {
            render: {fn:  this._fixStupidExtraDiv,scope:this}
        }
    });
    GridEditor.panel.CMP.superclass.constructor.call(this,config);
};
Ext.extend(GridEditor.panel.CMP,MODx.Panel,{

    _fixStupidExtraDiv: function(){
        var el = this.getEl().parent();
        var stupidDiv = el.next('.x-panel-bwrap');
        stupidDiv.setStyle({
            height: '29px'
        })
    }


});
Ext.reg('grideditor-panel-cmp',GridEditor.panel.CMP);
