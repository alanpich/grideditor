<?php
/**
 * Main CMP controller
 * 
 * @package grideditor
 * @copyright Alan Pich 2012
 */
class GrideditorCmpManagerController extends GrideditorManagerController {
    
    /** bool $validConfig Is there a valid config file? */
    private $validConfig = true;
    
    /**
     * Checks config file is valid
     * @param array $scriptProperties
     */
    public function process(array $scriptProperties = array()) {
       
        
        $this->validConfig = true;
        
        // Check service loaded ok
        $configName = isset($scriptProperties['config'])? $scriptProperties['config'] : '';
        if(! $conf = $this->modx->grideditor->loadConfigChunk('demo')){
            echo 'Failed to load config';
            $this->validConfig = false;
        } else {
            $this->confData =& $conf;
        }
        
        // Now prepare output for Ext Gridness
        if(! $this->Ext = $this->modx->grideditor->generateExtJavascript($conf,'grideditor-cmp-grid-div')){
            $this->validConfig = false;
        };
    }//
    
    /**
     * Allow config chunk to override page title
     * @return string Page Title
     */
    public function getPageTitle() { 
        return (empty($this->confData->title)) ? $this->modx->lexicon('grideditor.cmp.default.title') : $this->confData->title;
    }//
    
    
    /**
     * Add all nescesary JS to page
     */
    public function loadCustomCssJs() {
        // Dont load anything if there's no config
        if( ! $this->validConfig ){ return; };
        
        //$this->addJavascript($this->modx->grideditor->config['jsUrl'].'mgr/widgets/helper.grid.js');
        $this->addJavascript($this->modx->grideditor->config['jsUrl'].'sections/grideditor.panel.cmp.js');
        $this->addJavascript($this->modx->grideditor->config['jsUrl'].'widgets/grideditor.grid.grideditor.js');
        $this->addJavascript($this->modx->grideditor->config['jsUrl'].'widgets/grideditor.combo.tv.js');
        $this->addJavascript($this->modx->grideditor->config['jsUrl'].'widgets/grideditor.combo.gridfilter.js');
        $this->addJavascript($this->modx->grideditor->config['jsUrl'].'widgets/grideditor.checkbox.js');
        $this->addJavascript($this->modx->grideditor->config['jsUrl'].'widgets/grideditor.window.warnings.js');
        $this->addLastJavascript($this->modx->grideditor->config['jsUrl'].'sections/grideditor.cmp.js');
        
        $this->addHtml($this->Ext);
        
    }//
    
    /**
     * Check config file exists, either return cmp tpl or error message
     * @return string Path to smarty template
     */
    public function getTemplateFile() {
        $tpl = $this->validConfig ? 'cmp.tpl' : 'invalidconfig.tpl';
        return $this->modx->grideditor->config['templatePath'].$tpl;
    }//
    
    /**
     * Add some javascript to set the custom page title to the page
     */
    private function getPageTitleExt(){
        $title = $this->confData->title;
        $tpl = $this->modx->grideditor->config['templatePath'].'cmp.pagetitle.tpl';
        $this->modx->smarty->assign('grideditorpagetitle',$title);
        return $this->modx->smarty->fetch($tpl);
    }//

    
}// end class
