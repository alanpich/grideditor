<?php
/**
 * Grid List processor
 *
 * @package grideditor
 * @copyright Alan Pich 2012
 */
class grideditorGetListProcessor extends modObjectGetListProcessor
{
    public $classKey = 'modResource';
    public $languageTopics = array();
    public $defaultSortField = 'pagetitle';
    public $defaultSortDirection = 'ASC';
    public $objectType = 'modResource';
    /**
     * @var GridEditorConfiguration $confData Custom config data (from json chunk)
     * @access private
     */
    private $confData;
    /**
     * Helper class
     * @access private
     */
    private $helper;

    public function process()
    {
        // Grab the name of config chunk
        $params =& $this->modx->request->parameters['POST'];
        if (!isset($params['chunk']) || empty($params['chunk'])) {
            return $this->failure("No config chunk specified");
        };
        $chunkName = $params['chunk'];

        // Attempt to load config chunk
        if (!$this->confData = $this->modx->grideditor->loadConfigChunk($chunkName)) {
            return $this->failure('Invalid Config Chunk');
        };

        return parent::process();
    }

    //

    /**
     * Only select resources that are not deleted
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        // Don't show deleted resources
        $c->where(array('deleted' => 0));

        if (!empty($this->confData->resourceQuery)){
            foreach($this->confData->resourceQuery as $key => $val){
                $c->where(array($key=>$val));
            }
        }

        // Check for a search query
        $search = $this->getProperty('search');
        if (strlen($search)>=1 && !is_null($this->confData->searchFields)) {
//            die('SEARCHING - '.strlen($search).' - '.$search);
            $where = array();
            foreach($this->confData->searchFields as $field){
                $where[$field.':LIKE'] = '%'.$search.'%';
            }
            $c->where(array($where),xPDOQuery::SQL_OR);
        }

        // Check for a filter
        $filter = $this->getProperty('filter');
        if(strlen($filter)){
            $c->where(array(
                $this->confData->filter->field => $filter
            ));
        }

        return $c;
    }

    //

    /**
     * Override modObjectGetListProcessor::prepareRow to add TV values to data
     * @param xPDOObject $resource
     * @internal param \xPDOObject $object
     * @return array The row data
     */
    public function prepareRow(xPDOObject $resource)
    {
        $data = $resource->toArray();

        // Add to data array
        foreach ($this->confData->fields as $tv) {
            if ($tv->type !== 'tv') {
                continue;
            };

            $tvName = $tv->field;
            // Grab TV value (if it exists)
            $data[$tvName] = $this->getTVValue($resource, $tvName);
            // Null => empty string
            if (is_null($data[$tvName])) {
                $data[$tvName] = '';
            };
        }
        return $data;
    }

    //

    /**
     * Get TV Value by name, with typecasting
     * @param modResource $resource
     * @param string $tvName
     * @return mixed value
     */
    private function getTVValue($resource, $tvName)
    {
        // Grab TV Value
        $value = $resource->getTVValue($tvName);
        // Grab TV type
        switch ($this->getTVtype($tvName)) {
            case 'checkbox' :
                $value = (bool)$value;
                break;
        };
        // Return the correctly typecast value
        return $value;
    }

    //

    /**
     * Get TV input type
     * @param string $tvName
     * @return string type
     */
    private function getTVtype($tvName)
    {
        $tv = $this->modx->getObject('modTemplateVar', array('name' => $tvName));
        return $tv->get('type');
    }
    //

}

; // end class grideditorGetListProcessor
return 'grideditorGetListProcessor';
