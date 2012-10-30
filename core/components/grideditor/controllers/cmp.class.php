<?php
/**
 * Main CMP controller
 * 
 * @package grideditor
 * @copyright Alan Pich 2012
 */
class GrideditorCmpManagerController extends grideditorManagerController {
    public function process(array $scriptProperties = array()) {
 
    }
    public function getPageTitle() { return $this->modx->lexicon('grideditor.cmp.pagetitle'); }
    public function loadCustomCssJs() {
        //$this->addJavascript($this->helper->config['jsUrl'].'mgr/widgets/helper.grid.js');
        //$this->addJavascript($this->helper->config['jsUrl'].'mgr/widgets/home.panel.js');
        //$this->addLastJavascript($this->helper->config['jsUrl'].'mgr/sections/index.js');
    }
    public function getTemplateFile() { return $this->helper->config['templatePath'].'cmp.tpl'; }
}// end class
