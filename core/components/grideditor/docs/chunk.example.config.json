/**
 * Example CMP config file
 * 
 * @package grideditor
 * @copyright Alan Pich 2012

 GridEdit CMPs are configured using a chunk containing a JSON encoded 
 object. The object defines the resource selection rules, which fields 
 are displayed, and some other niceties like page titles.
 
 The name of the chunk should be passed as the action parameter when 
 setting up a new menu item. 
 TODO: THIS NEEDS TO BE TESTED TO MAKE SURE ACTION PARAM CAN BE HI-JACKED
 
 **/
 {
	/* Page title (both <title> and <h2>) 
	 * @var String
	 */
	title: 'This is the page title'
	
	/* Array of template names or ids to use
	 *  names should be quoted, ids as integers
	 *  NB: Selected TVs need to be accessible on all specified 
	 *	  templates or madness may ensue.
	 */
	,templates: [2,"My Template Name"]
	
	/* Optional extra filters to apply when grabbing resources
	 *  ADVANCED USE - used as $config array in xPDO::newQuery
	 */
	,filters: []
	
	/* Stock Resource fields to include
	 * @var Array of Object
	 */
	,fields: [{
			field: 'pagetitle'
			,editable: true
		},{
			field: 'menutitle'
			,editable: true
		},{
			field: 'published' 
			,editable: true
		}]
	
	/* TV fields to include
	 *  Same as fields above
	 */
	,tvs: [{
		name: 'My-TV-Name'
		,editable: true
	}]
	
	/* Optional command buttons to attach
	 */
	,commands: ['publish','edit','delete'] 
	 
	 
