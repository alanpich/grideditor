/**
 * Main GridEditor Component
 * 
 * @package grideditor
 * @copyright Alan Pich 2012
 */
 var GridEditor = function(config) {
    config = config || {};
    GridEditor.superclass.constructor.call(this,config);
};
Ext.extend(GridEditor,Ext.Component,{
    page:{},window:{},grid:{},tree:{},panel:{},combo:{},config: {},renderer:{}
});
Ext.reg('GridEditor',GridEditor);
GridEditor = new GridEditor();



GridEditor.deleteResource = function(resourceId, grid){
    MODx.msg.confirm({
        title: 'Delete Resource',
        text: 'Are you sure you want to delete this resource?',
        url: MODx.config.connectors_url+'resource/index.php',
        params: {
            action: 'delete',
            id: resourceId
        },
        listeners: {
            'success': {fn: function(){
                this.refresh();
            },scope: grid}
        }
    })
}