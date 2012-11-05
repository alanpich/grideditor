/**
 * Delete a resource
 * @param int resourceId ID of the resource to delete
 * @param obj grid The grid to refresh after delete
 */
GridEditor.fn.deleteResource = function(resourceId, grid){
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
};//

/**
 * Publish a resource by id
 * @param int $resID Resource ID
 */
GridEditor.fn.publishResource = function(resId){
   MODx.Ajax.request({
        url: MODx.config.connectors_url+'resource/index.php'
        ,params: { 
            action: 'publish',
            id: record.json.id
        }
        ,listeners: {
            'success':{fn:function() {
               MODx.msg.status({
                   title: 'Resource published'                               
               })
            },scope:this}
            ,'error':{fn:function(){
                alert('Error: failed to publish resource');
            },scope: this}
        }
    }); 
}//


/**
 * Publish a resource by id
 * @param int $resID Resource ID
 */
GridEditor.fn.publishResource = function(resId){
   MODx.Ajax.request({
        url: MODx.config.connectors_url+'resource/index.php'
        ,params: { 
            action: 'publish',
            id: resId
        }
        ,listeners: {
            'success':{fn:function() {
               MODx.msg.status({
                   title: 'Resource published'                               
               })
            },scope:this}
            ,'error':{fn:function(){
                alert('Error: failed to publish resource');
            },scope: this}
        }
    }); 
}//


/**
 * Unpublish a resource by id
 * @param int $resID Resource ID
 */
GridEditor.fn.unpublishResource = function(resId){
   MODx.Ajax.request({
        url: MODx.config.connectors_url+'resource/index.php'
        ,params: { 
            action: 'unpublish',
            id: resId
        }
        ,listeners: {
            'success':{fn:function() {
               MODx.msg.status({
                   title: 'Resource unpublished'                               
               })
            },scope:this}
            ,'error':{fn:function(){
                alert('Error: failed to unpublish resource');
            },scope: this}
        }
    }); 
}//


