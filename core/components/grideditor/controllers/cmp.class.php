<?php
/**
 * Main CMP controller
 * 
 * @package grideditor
 * @copyright Alan Pich 2012
 */
class GrideditorCmpManagerController extends GrideditorManagerController {
    
    /** bool $validConfig Is there a valid config file? */
    private $validConfig = false;
    
    /**
     * Checks config file is valid
     * @param array $scriptProperties
     */
    public function process(array $scriptProperties = array()) {
        // Config chunk is prefix.name
        $configName = isset($scriptProperties['config'])? $scriptProperties['config'] : '';
        $configChunk = $this->helper->config['configChunkPrefix'].$configName;
        // Check chunk exists
        $chunk = $this->modx->getObject('modChunk',array('name' => $configChunk));
        $this->validConfig = ($chunk instanceof modChunk);
        // Parse & Load json config if exists
        if($this->validConfig){
            $this->confData = $this->helper->sanitizedJSONdecode($chunk->process());
            $this->confData->chunk = $configChunk;
        } else {
            // Log Error
        };
    }//
    
    /**
     * Allow config chunk to override page title
     * @return string Page Title
     */
    public function getPageTitle() { 
        return (empty($this->confData->title)) ? $this->modx->lexicon('grideditor.cmp.default.title') : $this->confData->title;
    }//
    
    
    public function loadCustomCssJs() {
        // Dont load anything if there's no config
        if( ! $this->validConfig ){ return; };
        
        //$this->addJavascript($this->helper->config['jsUrl'].'mgr/widgets/helper.grid.js');
        $this->addJavascript($this->helper->config['jsUrl'].'sections/grideditor.panel.cmp.js');
        $this->addJavascript($this->helper->config['jsUrl'].'widgets/grideditor.grid.grideditor.js');
        $this->addLastJavascript($this->helper->config['jsUrl'].'sections/grideditor.cmp.js');
        
        $this->addHtml('<script type="text/javascript">
            Ext.onReady(function() {
                GridEditor.config.custom = '.json_encode($this->confData).';
                GridEditor.custom = '.json_encode($this->helper->getExtConfig($this->confData)).';
            });
        </script>');
    }
    
    /**
     * Check config file exists, either return cmp tpl or error message
     * @return string Path to smarty template
     */
    public function getTemplateFile() {
        $tpl = $this->validConfig ? 'cmp.tpl' : 'invalidconfig.tpl';
        return $this->helper->config['templatePath'].$tpl;
    }//

    
}// end class
