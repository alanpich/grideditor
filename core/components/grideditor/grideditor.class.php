<?php
$here = dirname(__FILE__).'/';
require_once $here.'/lib/minify.json.php';
require_once $here.'grideditor.configuration.class.php';
require_once $here.'grideditor.field.abstract.php';
require_once $here.'grideditor.resourcefield.class.php';
require_once $here.'grideditor.tvfield.class.php';
class GridEditor {
    
    
    /**
     * @var string Prefix for configuration chunk names
     */
    private $chunkPrefix = 'grideditor.config.';
    
    
    
    public function __construct( modX &$modx ){
       $this->modx =& $modx;
       
       // Setup config data
           $core = $this->modx->getOption('core_path').'components/grideditor/';
           $assets = $this->modx->getOption('assets_url').'components/grideditor/';
           $this->config = array(
                   'corePath' => $core,
                   'processorPath' => $core.'processors/',
                   'controllerPath' => $core.'controllers/',
                   'templatePath' => $core.'templates/',
                   'libPath' => $core.'lib/',
                   'docsPath' => $core.'docs/',
                   'assetsUrl' => $assets,
                   'jsUrl' => $assets.'mgr/js/',
                   'cssUrl' => $assets.'mgr/css/',
                   'imgUrl' => $assets.'mgr/img/',
                   'connectorUrl' => $assets.'mgr/connector.php',
                   'managerUrl' => $this->modx->getOption('manager_url'),
                   'documentationUrl' => 'http://github.com/alanpich/grideditor/',
                   'templateMap' => $this->getTemplateMap()
           );
       
       // Allow overriding the chunk prefix using a system setting
       $this->chunkPrefix = $this->modx->getOption('grideditor.configChunkPrefix',null,$this->chunkPrefix,true); 
    }//
    
    
    /**
     * Load a configuration chunk and prepare data
     * @param string $chunkName Suffix for config chunk
     * @return false|object Config Object or false
     */
    public function loadConfigChunk( $chunkSuffix ){
        // Check chunk exists
        $chunkName = $this->chunkPrefix.$chunkSuffix;
        $chunk = $this->modx->getObject('modChunk',array('name'=>$chunkName));
        if(!$chunk instanceof modChunk){ return false; };
        
        // Translate insto a GridEditorConfig object
        $conf = GridEditorConfiguration::fromChunk($chunkName,&$this->modx,$this->chunkPrefix);
        
        return $conf;   
    }//

    
    /**
     * Get a pre-prepared xPDOQuery object for gathering relevant resources
     * @param GridEditorConfiguration $conf
     * @return xPDOQuery
     */
    public function get_xPDOQuery($conf){
        $c = $this->modx->newQuery('modResource',array(
                'deleted' => 0
            ));
        foreach($conf->templates as $tplID){
            $c->where(array('template'=>$tplID));
        };
        return $c;
    }//
    
    /**
     * Get an array template map
     * returned as ID => Name
     * @return array
     */
    private function getTemplateMap(){
        $tpls = $this->modx->getCollection('modTemplate');
        $map = array();
        foreach($tpls as $tpl){
            $map[$tpl->get('id')] = $tpl->get('templatename');
        };
        return $map;
    }

};// end class GridEditor

// Handy function - In Array for multidimensional arrays
function in_array_r($needle, $haystack, $strict = true) {
    foreach ($haystack as $item) {
        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
            return true;
        }
    }

    return false;
}
