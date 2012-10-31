<?php
/**
 * MGR Connector
 * 
 * @package grideditor
 * @copyright Alan Pich 2012
 */
require_once dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/config.core.php';
require_once MODX_CORE_PATH.'config/'.MODX_CONFIG_KEY.'.inc.php';
require_once MODX_CONNECTORS_PATH.'index.php';
 
$corePath = $modx->getOption('core_path').'components/grideditor/';

// Include the helper class (for processors to initialize)
require_once $corePath.'grideditorHelper.class.php';
 
// Load up some lexiconzzzz
$modx->lexicon->load('grideditor:default');
 
// Handle request 
$modx->request->handleRequest(array(
    'processors_path' => $corePath.'processors/',
    'location' => '',
));