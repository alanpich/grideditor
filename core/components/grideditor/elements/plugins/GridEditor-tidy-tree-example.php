<?php

/** @var modResource $resource */


// Hide 'project' resources by template
if($resource->get('template') == 3){
    $resource->set('show_in_tree',0);
    $resource->show_in_tree = false;
    $resource->save();
}
