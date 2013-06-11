
/**
 * Show a toggle button that (un)publishes the resource
 */
GridEditor.renderer.publishToggle = function(value, metadata, record, rowIndex, colIndex, store){
    var elemID = 'grideditor-resource-'+record.json.id+"-toggle-published";
    GridEditor.renderer._publishToggleButton.defer(1, this, [elemID, record]);
    return '<div id="'+elemID+'"></div>';
}//
GridEditor.renderer._publishToggleButton = function(elemid, record) {
        var action = record.json.published? 'drop-yes' : 'drop-no';
        var lexAction = record.json.published? 'unpublish':'publish';
        new Ext.Button({
//           text: '<img src="'+GridEditor.config.imgUrl+'icons/'+action+'.png'+'" width="16" height="16" title="'+_('grideditor.click_to_'+action)+'" />',
//           onText: '<img src="'+GridEditor.config.imgUrl+'icons/unpublish.png'+'" width="16" height="16" title="'+_('grideditor.click_to_unpublish')+'" />',
//           offText: '<img src="'+GridEditor.config.imgUrl+'icons/publish.png'+'" width="16" height="16" title="'+_('grideditor.click_to_publish')+'" />',
           text: '<img src="'+MODx.config.manager_url+'templates/default/images/modx-theme/dd/'+action+'.gif" width="16" height="16" title="'+_('grideditor.click_to_'+lexAction)+'" />',
           onText: '<img src="'+MODx.config.manager_url+'templates/default/images/modx-theme/dd/drop-yes.gif'+'" width="16" height="16" title="'+_('grideditor.click_to_unpublish')+'" />',
           offText: '<img src="'+MODx.config.manager_url+'templates/default/images/modx-theme/dd/drop-no.gif'+'" width="16" height="16" title="'+_('grideditor.click_to_publish')+'" />',
           enableToggle: true,
           scale: 'small',
           cls: 'grideditor-button-publish',
           pressed: record.json.published? true : false,
           toggleHandler: function(btn,turnOff){
             //  console.log('Was '+state+', now change to '+(!state));
               if(turnOff){
                   // Publish resource
                   btn.setText(btn.onText);
                   var action = 'publish';
                   GridEditor.fn.publishResource(record.json.id, record.json);
               } else {
                   // Unpublish resource
                   btn.setText(btn.offText);
                   var action = 'unpublish';
                   GridEditor.fn.unpublishResource(record.json.id, record.json);
               };
           }
       }).render(document.body,elemid);
        
    }
    
    
 /**
  * Checkbox renderer - offer a one-click toggle checkbox
  */
    GridEditor.renderer._checkbox = function(elemID,record,grid,dataIndex){
        MODx.load({
            xtype: 'grideditor-checkbox'
            ,renderTo: elemID
            ,record: record
            ,grid: grid
            ,dataIndex: dataIndex
        })
    }//
    
    
 /**
  * Date renderer - format a date according to Modx manager_date_format
  */
 GridEditor.renderer.date = function(value, metadata, record, rowIndex, colIndex, store){
        return Ext.util.Format.date(value,MODx.config.manager_date_format);
    }//    
    
    /**
     * Image TV renderer (deferred)
     */
    GridEditor.renderer._image = function( elemID, imgSrc, width ){
         var src = MODx.config.connectors_url+'system/phpthumb.php?w='+width+'&zc=1&src='+imgSrc;
         var img = document.createElement('img');
             img.src = src;
         document.getElementById(elemID).appendChild(img);
    }//
    
    /**
     * Template renderer
     */
    GridEditor.renderer.template = function(value, metadata, record, rowIndex, colIndex, store){
        return GridEditor.config['templateMap'][value];
    }





    /**
     * Grid row inline action combo
     */
    GridEditor.renderer.actionCombo= function(value, metadata, record, rowIndex, colIndex, store){
        var elemID = 'grideditor-resource-'+record.json.id+"-row-actions";
        GridEditor.renderer._actionCombo.defer(1, this, [elemID, record]);
        return '<div id="'+elemID+'"></div>';
    }//
    GridEditor.renderer._actionCombo = function(elemid, record) {
        var action = record.json.published? 'unpublish' : 'publish';
        var combo = MODx.load({
            xtype: 'grideditor-combo-resourceactions',
            renderTo: elemid,
            width: 150,
            grideditor: this.grideditor
        })
     //   combo.render.defer(1000, this, [this.el,elemid]);
    }


















    
    
String.prototype.capitalize = function() {
        return this.charAt(0).toUpperCase() + this.slice(1);
    }//
