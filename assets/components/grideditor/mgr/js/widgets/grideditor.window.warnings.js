GridEditor.window.Warnings = function(config) {
    config = config || {};
    this.warnings = config.warnings;
    Ext.applyIf(config,{
        title: _('grideditor.warnings.windowtitle')
        ,boxMaxWidth: 500
        ,css: {
            width: '450px'
        }
        ,iconCls: 'icon-warning'
        ,items: this.getWarnings()
    });
    GridEditor.window.Warnings.superclass.constructor.call(this,config);
};
Ext.extend(GridEditor.window.Warnings,Ext.Window,{
    
    getWarnings: function(){
        var items = [{
            html: '<p class="grideditor-warnings-intro">'+_('grideditor.warnings.info')+'</p>'
            ,border: false
            ,width: '500'
        }];
    
        var html = '';
        for(var k=0;k<this.warnings.length;k++){
            var warning = this.warnings[k];
            var str = _('grideditor.warn.'+warning.key, warning.data) || warning;
            html+= '<li style="list-style-image: url('+MODx.config.manager_url+'templates/default/images/modx-theme/shared/warning.gif);">'
                  +str+'</li>';            
        };
        
        items.push({
            html: '<ul class="grideditor-warnings-list">'+html+'</ul>'
            ,border: false
            ,width: '500'
        })
        return items;
    }//
    
});
Ext.reg('grideditor-window-warnings',GridEditor.window.Warnings);
