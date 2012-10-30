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
    page:{},window:{},grid:{},tree:{},panel:{},combo:{},config: {}
});
Ext.reg('GridEditor',GridEditor);
GridEditor = new GridEditor();
