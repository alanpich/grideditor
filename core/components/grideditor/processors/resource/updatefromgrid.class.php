<?php
/**
 * Resource inline update processor
 * 
 * @package grideditor
 * @copyright Alan Pich 2012
 */
class gridEditorResourceUpdateProcessor extends modProcessor {
    
    public function process(){
        // Parse input data
        $fields = json_decode($this->getProperty('data'));
        
        // Grab the name of config chunk
        $chunkName = $fields->chunk;
        unset($fields->chunk);
        unset($fields->menu);
        
        // Load config
        if(!$conf = $this->modx->grideditor->loadConfigChunk($chunkName)){
            return $this->failure('Invalid config chunk');
        };
                
        // Separate tvs from resource fields
        $resValues = array();
        $tvValues = array();
        foreach($fields as $field => $value){
            switch($conf->fields[$field]->type){
                case 'tv': $tvValues[$field] = $value; break;
                default:   $resValues[$field] = $value; break;
            };
        };
     
        // Grab resource object
        $resource = $this->modx->getObject('modResource',(int)$resValues['id']);
        unset($resValues['id']);
        
        // Save Resource values
        foreach($resValues as $key => $val){
            $resource->set($key,$val);
        };
        
        // Save TV values
        foreach($tvValues as $key => $val){
            $resource->setTVValue($key,$val);
        }
        
        // Save changes
        $resource->save();
       
        // Success!
        return $this->success();
    }//
}
return 'gridEditorResourceUpdateProcessor';
 
