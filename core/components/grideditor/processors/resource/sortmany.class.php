<?php
class grideditorResourceSortManyProcessor extends modProcessor {


    public function process(){


        $json = $this->getProperty('data','');
            if(!strlen($json))
                return $this->failure($this->modx->lexicon('invalid_data'));
        $data = $this->modx->fromJSON($json);
            if(empty($data))
                return $this->failure($this->modx->lexicon('invalid_data'));


        foreach($data as $menuIndex => $resId){
            $res = $this->modx->getObject('modResource',$resId);
            if($res instanceof modResource){
                $res->set('menuindex',$menuIndex);
                $res->save();
            } else {
                $this->modx->log(xPDO::LOG_LEVEL_ERROR,"Failed to set menu index for nonexistant resource #{$resId}");
            }
        }
        return $this->success();
    }

};
return "grideditorResourceSortManyProcessor";