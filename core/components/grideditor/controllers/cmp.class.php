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
            if($this->confData == NULL){
                $this->validConfig = false;
            }
        } else {
            // Log Error
        };
    }//
    
    /**
     * Allow config chunk to override page title
     * @return string Page Title
     */
    public function getPageTitle() { 
        return (empty($this->confData->title)) ? $this->modx->lexicon('grideditor.cmp.pagetitle') : $this->confData->title;
    }//
    
    
    public function loadCustomCssJs() {
        //$this->addJavascript($this->helper->config['jsUrl'].'mgr/widgets/helper.grid.js');
        //$this->addJavascript($this->helper->config['jsUrl'].'mgr/widgets/home.panel.js');
        //$this->addLastJavascript($this->helper->config['jsUrl'].'mgr/sections/index.js');
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
