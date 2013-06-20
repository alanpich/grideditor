/**
 * Delete a resource
 *
 * @param record
 * @param grid
 */
GridEditor.fn.deleteResource = function(record, grid){
    MODx.msg.confirm({
        title: 'Warning',
        text: 'This is irreversible. Are you sure you wish to continue?',
        url: MODx.config.connectors_url+'resource/index.php',
        params: {
            action: 'delete',
            id: record.json.id
        },
        listeners: {
            'success':{fn: function(r) {
                console.log('success');
                MODx.msg.status({
                    title: 'Resource deleted',
                    message: record.json.pagetitle
                })
                this.refresh();
            },scope:grid}
        }
    })
};//


/**
 * Publish a resource by id
 *
 * @param record
 * @param grid
 */
GridEditor.fn.publishResource = function(record,grid){
   MODx.Ajax.request({
        url: MODx.config.connectors_url+'resource/index.php'
        ,params: { 
            action: 'publish',
            id: record.json.id
        }
        ,listeners: {
            'success':{fn:function() {
               MODx.msg.status({
                   title: 'Resource published',
                   message: record.json.pagetitle
               })
               try{
                this.refresh()
               } catch( err ){}
            }, scope: grid }
            ,'error':{fn:function(){
                alert('Error: failed to publish resource');
            },scope: this}
        }
    }); 
}//


/**
 * Unpublish a resource by id
 *
 * @param record
 * @param grid
 */
GridEditor.fn.unpublishResource = function(record,grid){
   MODx.Ajax.request({
        url: MODx.config.connectors_url+'resource/index.php'
        ,params: { 
            action: 'unpublish',
            id: record.json.id
        }
        ,listeners: {
            'success':{fn:function() {
               MODx.msg.status({
                   title: 'Resource unpublished'
                   ,message: record.json.pagetitle
               })
                try{
                    this.refresh()
                } catch( err ){}
            },scope:grid}
            ,'error':{fn:function(){
                alert('Error: failed to unpublish resource');
            },scope: this}
        }
    }); 
}//


/**
 * Redirect to resource edit screen
 *
 * @param record
 * @param grid
 */
GridEditor.fn.editResource = function(record,grid){
    var action = MODx.action['resource/update'];
    document.location.href = MODx.config.manager_url+'?a='+action+'&id='+record.json.id;
}


/**
 * Open a window to view a resource in the front end
 *
 * @param record
 * @param grid
 */
GridEditor.fn.viewResource = function(record,grid){
    var url = MODx.config.site_url + record.json.uri;
    window.open(url);
}

