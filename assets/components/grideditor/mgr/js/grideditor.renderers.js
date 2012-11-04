
/**
 * Show a toggle button that (un)publishes the resource
 */
GridEditor.renderer.publishToggle = function(value, metadata, record, rowIndex, colIndex, store){
    var elemID = 'grideditor-resource-'+record.json.id+"-toggle-published";
    GridEditor.renderer._publishToggleButton.defer(1, this, [elemID, record]);
    return '<div id="'+elemID+'"></div>';
}//
GridEditor.renderer._publishToggleButton = function(elemid, record) {
        var action = record.json.published? 'unpublish' : 'publish';
        new Ext.Button({
           text: '<img src="'+GridEditor.config.imgUrl+'icons/'+action+'.png'+'" width="20" height="20" />',
           onText: '<img src="'+GridEditor.config.imgUrl+'icons/unpublish.png'+'" width="20" height="20" />',
           offText: '<img src="'+GridEditor.config.imgUrl+'icons/publish.png'+'" width="20" height="20" />',
           enableToggle: true,
           scale: 'small',
           cls: 'grideditor-button-nostyle',
           pressed: record.json.published? true : false,
           toggleHandler: function(btn,turnOff){
             //  console.log('Was '+state+', now change to '+(!state));
               if(turnOff){
                   // Unpublish resource
                   btn.setText(btn.onText);
                   var action = 'publish';
               } else {
                   // Publish resource
                   btn.setText(btn.offText);
                   var action = 'unpublish';
               }
               
               MODx.Ajax.request({
                    url: MODx.config.connectors_url+'resource/index.php'
                    ,params: { 
                        action: action,
                     //   register: 'mgr' ,
                        id: record.json.id
                    }
                    ,listeners: {
                        'success':{fn:function() {
                           MODx.msg.status({
                               title: 'Resource '+action+'ed'                               
                           })
                        },scope:this}
                        ,'error':{fn:function(){
                            alert('Error: failed to '+action+' resource');
                        },scope: this}
                    }
                });
           }
       }).render(document.body,elemid);
        
    }
    
    
    
    
/*******************************************************************************
 * Render resource control buttons to a cell
 */
GridEditor.renderer.resourceControls = function(value, metadata, record, rowIndex, colIndex, store){
   var controls = GridEditor.custom.controls;
   
   var html = '';
   var items = [];
 
   // Edit button
   if( controls.indexOf('edit') > -1){
       var mgrUrl = MODx.config.manager_url+'?a=30&id='+record.json.id;
       html+= '<a class="grideditor-button-edit" href="'+mgrUrl+'"><img src="'+GridEditor.config.imgUrl+'icons/edit.png'+'" width="20" height="20" alt="edit icon" title="Edit this resource" /></a>';
    };
   
   // Delete button#
   if( controls.indexOf('delete') > -1){
     var elemID = 'grideditor-resource-'+record.json.id+"-delete-resource";
     GridEditor.renderer._deleteResourceButton.defer(1, this, [elemID, record]);
     html+= '<div id="'+elemID+'" title="Delete this resource"></div>';
   };
    
    // Return HTML to grid
    return html;
   }//
GridEditor.renderer._deleteResourceButton = function(elemid, record) {
        new Ext.Button({
           text: '<img src="'+GridEditor.config.imgUrl+'icons/delete.png'+'" width="20" height="20" />',
           scale: 'small',
           cls: 'grideditor-button-nostyle',
           style: {
               float: 'left'
           },
           pressed: record.json.published? true : false,
        }).render(document.body,elemid);
        
    }//
    
    
    
String.prototype.capitalize = function() {
    return this.charAt(0).toUpperCase() + this.slice(1);
}