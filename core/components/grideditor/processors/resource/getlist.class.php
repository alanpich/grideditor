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
        $chunkName = $this->getProperty('chunk',false);
        if ($chunkName===false || empty($chunkName)) {
            return $this->failure("No config chunk specified");
        };

        // Attempt to load config chunk
        if (!$this->confData = $this->modx->grideditor->loadConfigChunk($chunkName)) {
            return $this->failure('Invalid Config Chunk');
        };

        return parent::process();
    }


    /**
     * Get the data of the query
     * @return array
     */
/*    public function getData() {
        $data = array();
        $limit = intval($this->getProperty('limit'));
        $start = intval($this->getProperty('start'));

        $c = $this->modx->newQuery($this->classKey);
        $c = $this->prepareQueryBeforeCount($c);
        $data['total'] = $this->modx->getCount($this->classKey,$c);
        $c = $this->prepareQueryAfterCount($c);

        $sortClassKey = $this->getSortClassKey();
        $sortKey = $this->modx->getSelectColumns($sortClassKey,$this->getProperty('sortAlias',$sortClassKey),'',array($this->getProperty('sort')));
        if (empty($sortKey)) $sortKey = $this->getProperty('sort');
        $c->sortby($sortKey,$this->getProperty('dir'));
        if ($limit > 0) {
            $c->limit($limit,$start);
        }

        // Manually execute query as we are not returning modResource objects
        $c->prepare();
        $c->stmt->execute();

        $data['results'] = $c->stmt->fetchAll(PDO::FETCH_ASSOC);

        return $data;
    }*/



    /**
     * Only select resources that are not deleted
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        // Apply resource filters
        $c->select($this->modx->getSelectColumns('modResource','modResource',''));
        $c->where($this->confData->resourceQuery,xPDOQuery::SQL_AND,null,0);


        $search = $this->getProperty('query','');
        $doSearch = strlen($search) && count($this->confData->searchFields);

        $filterValue = $this->getProperty('filter','');
        $doFilter = isset($this->confData->filter) && $this->confData->filter !== false && $filterValue != '';
        if($doFilter){
            $filter = (object)(array)$this->confData->filter;
            $filter->value = $filterValue;
        }


        // Add tv fields to select
        foreach($this->confData->fields as $field){

            // TV Fields
            if($field->type == 'tv'){
                $safeFieldName = str_replace(array('-','.'),'_',$field->field);
                $joinName = $safeFieldName."TV";

                $c->leftJoin('modTemplateVarResource', $joinName, "modResource.id = {$joinName}.contentid AND {$joinName}.tmplvarid = {$field->tvId}");
                $c->select(array($safeFieldName => "{$joinName}.value"));


                // Conditions for searching TVs
                if($doSearch && in_array($field->field,$this->confData->searchFields)){
                    $c->condition($c->query['having'], "`{$safeFieldName}` LIKE '%{$search}%'", xPDOQuery::SQL_OR, null, 1);
                }

                // Conditions for filtering on TVs
                if($doFilter && $field->field == $filter->field){
                    $c->condition($c->query['having'], "`{$safeFieldName}` = '{$filter->value}'", xPDOQuery::SQL_AND, null, 1);
                }


            } else {
                // Conditions for searching Resource fields
                if($doSearch && in_array($field->field,$this->confData->searchFields)){
                    $c->having(array(
                            $field->field.":LIKE" => "%{$search}%"
                        ),xPDOQuery::SQL_OR);
                }
                // Conditions for filtering Resource fields
                if($doFilter && $field->field == $filter->field){
                    $c->having(array(
                            $field->field.":=" => $filter->value
                        ),xPDOQuery::SQL_AND);
                }
            }
        }


        $c->prepare();
    //    die($c->toSQL());
        return $c;
    }


    /**
     * Override modObjectGetListProcessor::prepareRow to add TV values to data
     * @param xPDOObject $resource
     * @internal param \xPDOObject $object
     * @return array The row data
     */
    public function prepareRow($resource)
    {
        $data = $resource->toArray();
        // Return safetified tv names to their proper values
        foreach($this->confData->fields as $safeName => $field){

            if($field->type != 'tv') continue;

            if(array_key_exists($safeName,$data)){
                $data[$field->field] = $data[$safeName];
                unset($data[$safeName]);
            }
        }
        return $data;
    }


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


}

; // end class grideditorGetListProcessor
return 'grideditorGetListProcessor';
