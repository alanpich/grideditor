<?php
/**
 * Abstract & Base CMP controllers
 * 
 * @package grideditor
 * @copyright Alan Pich 2012
 */

/**
 * @abstract Manager Controller Global Setup
 */
abstract class GrideditorManagerController extends modExtraManagerController {
    /** @var GridEditor */
    public $grideditor;
    
    public function initialize() {
        // Require the grideditor service
        $path = $this->modx->getOption('core_path').'components/grideditor/';
        $this->modx->getService('grideditor','GridEditor',$path,array('modx' => &$this->modx));

        $this->grideditor =& $this->modx->grideditor;
        
        $this->addCss($this->grideditor->config['cssUrl'].'mgr.css');
        $this->addJavascript($this->grideditor->config['managerUrl'].'assets/modext/util/datetime.js');
        $this->addJavascript($this->grideditor->config['jsUrl'].'grideditor.js');
        $this->addJavascript($this->grideditor->config['jsUrl'].'grideditor.functions.js');
        $this->addJavascript($this->grideditor->config['jsUrl'].'grideditor.renderers.js');
        $this->addHtml('<script type="text/javascript">
            Ext.onReady(function() {
                GridEditor.config = '.json_encode($this->grideditor->config).';
            });
            </script>');
        parent::initialize();
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
 
