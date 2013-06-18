<?php
/**
 * Grid Get Filter options
 * 
 * @package grideditor
 * @copyright Alan Pich 2012
 */
 class gridFilterGetListProcessor extends modProcessor {

    /**
     * Get list of filter options specified by config chunk
     */
    public function process(){
        
        // Grab config chunk name
        $chunkName =& $this->getProperty('chunk','');
        if(!strlen($chunkName)){
            return $this->failure('No config chunk specified');
        };

        // Load config
        if(!$conf = $this->modx->grideditor->loadConfigChunk($chunkName)){
            return $this->failure('Invalid config chunk');
        };
        
        // Grab all resources that match the template filters
        $c = $this->modx->grideditor->get_xPDOQuery($conf);
        $resources = $this->modx->getCollection('modResource',$c);
        $filterField = $conf->filter->field;


        // Grab the field info
        if(!$field = $conf->fields[$filterField]){

            // Try for tv thingy
            $safeName = str_replace(array('-','.'),'_',$filterField);
            if(!$field = $conf->fields[$safeName]){
                return $this->failure("Invalid filter field");
            }
        }

        // Grab the filter field
        $isTV = ($field->type == 'tv');

        // Grab the field specified from each resource
        $values = array(array('name' => $conf->filter->label, 'value'=>''));
        foreach($resources as $res){
            if($isTV){
                $val = $res->getTVValue($field->field);
            } else {
                $val = $res->get($filterField);
            };
            if(! in_array_r($val,$values) && !empty($val)){
                $values[] = array(
                    'name' => $val,
                    'value'=> $val
                  );
            };
        };
        
        return $this->outputArray($values);        
    }//
     
 };// end class grideditorGetListProcessor
 return 'gridFilterGetListProcessor';
