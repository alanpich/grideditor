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



