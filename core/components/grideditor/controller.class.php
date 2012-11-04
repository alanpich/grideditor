<?php
/**
 * Abstract & Base CMP controllers
 * 
 * @package grideditor
 * @copyright Alan Pich 2012
 */
require_once dirname(__FILE__) . '/grideditorHelper.class.php';

/**
 * @abstract Manager Controller Global Setup
 */
abstract class GrideditorManagerController extends modExtraManagerController {
    /** @var grideditorHelper $helper */
    public $helper;
    
    public function initialize() {
        $this->helper = new grideditorHelper($this->modx);
        
        $this->addCss($this->helper->config['cssUrl'].'mgr.css');
        $this->addJavascript($this->helper->config['managerUrl'].'assets/modext/util/datetime.js');
        $this->addJavascript($this->helper->config['jsUrl'].'grideditor.js');
        $this->addJavascript($this->helper->config['jsUrl'].'grideditor.functions.js');
        $this->addJavascript($this->helper->config['jsUrl'].'grideditor.renderers.js');
        $this->addHtml('<script type="text/javascript">
        Ext.onReady(function() {
            GridEditor.config = '.$this->helper->configJSON().';
        });
        </script>');
        return parent::initialize();
    }
    
    public function getLanguageTopics() {
        return array('grideditor:default');
    }
    
    public function checkPermissions() { return true;}
}

/**
 * Base CMP controller - triggers other controller through here
 */
class ControllerManagerController extends GrideditorManagerController {
    public static function getDefaultController() { return 'cmp'; }
 };// end class
 
