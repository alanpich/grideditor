<?php
class comboValuesGetListProcessor extends modProcessor {
    
    public function process(){
       $tvName = $this->getProperty('tvName');
       
       // Grab tv object
       $tv = $this->modx->getObject('modTemplateVar',array('name'=>$tvName));
       if(!$tv instanceof modTemplateVar){ return '[]'; };
       
       // Grab options from tv
       $data = array();
       $opts = explode('||',$tv->get('elements'));
       foreach($opts as $opt){
           $bits = explode('==',$opt);
           $obj = new stdClass;
           $obj->name = $bits[0];
           $obj->value = (isset($bits[1]))? $bits[1] : $bits[0];
           $data[] = $obj;
       };
       
       return $this->outputArray($data);       
    }//
};// end class
return 'comboValuesGetListProcessor';