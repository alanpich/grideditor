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

// Start up the GridEditor service
$path = $modx->getOption('core_path').'components/grideditor/';
$modx->getService('grideditor','GridEditor',$path,array('modx' => &$modx));
 
// Load up some lexiconzzzz
$modx->lexicon->load('grideditor:default');
 
// Handle request 
$modx->request->handleRequest(array(
    'processors_path' => $path.'processors/',
    'location' => '',
));