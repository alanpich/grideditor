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
        
        // Separate tvs from resource fields
        $resValues = array();
        $tvValues = array();
        foreach($fields as $field => $value){
            if( preg_match("/^tv_/",$field)){
                $tvValues[ str_replace('tv_','',$field)] = $value;
            } else {
                $resValues[$field] = $value;
            }
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
 
