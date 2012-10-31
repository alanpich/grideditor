<?php
/**
 * Helper Class
 * 
 * @package grideditor
 * @copyright Alan Pich 2012
 */
 class grideditorHelper {
	 
	 /**
	  * @var array $config Useful paths etc
	  * @access public
	  */
	 public $config = array();
	 
	 /**
	  * @var modX $modx Reference to MODx instance
	  * @access private
	  */
	 private $modx;
	 
	 /**
	  * Constructor - sets up config array
	  * @param modX $modx Current instance of MODx
	  */
	 function __construct(modX &$modx){
		// Make MODx instance accessible
		$this->modx =& $modx;
		
		// Setup config data
		$core = $this->modx->getOption('core_path').'components/grideditor/';
		$assets = $this->modx->getOption('assets_url').'components/grideditor/';
		$this->config = array(
			'corePath' => $core,
			'processorPath' => $core.'processors/',
			'controllerPath' => $core.'controllers/',
			'templatePath' => $core.'templates/',
			'docsPath' => $core.'docs/',
			'assetsUrl' => $assets,
			'jsUrl' => $assets.'mgr/js/',
			'cssUrl' => $assets.'mgr/css/',
            
            'configChunkPrefix' => 'grideditor.config.'
		);
	 }//
	 
	 /**
	  * Get config array as JSON string (for javascript insert)
	  * @return string JSON encoded self::$config array
	  */
	 public function configJSON(){
		return $this->modx->toJSON($this->config); 
	 }//
     
     
     
     /**
      * Sanitize json input. Removes all invalid chars & comments
      * @param string $raw Raw JSON input
      * @return mixed Parse JSON -> object|array etc
      */
     public function sanitizedJSONdecode($raw){
         $safe = preg_replace("/[^[:print:]]/",'',$raw);
         return json_decode($safe);        
     }//
	 
 };// end class grideditorHelper
