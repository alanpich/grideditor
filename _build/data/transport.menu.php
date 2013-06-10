<?php
$action= $modx->newObject('modAction');
$action->fromArray(array(
    'id' => 1,
    'namespace' => PKG_NAMESPACE,
    'parent' => 0,
    'controller' => 'controller',
    'haslayout' => true,
    'lang_topics' => PKG_NAMESPACE.':default',
    'assets' => '',
),'',true,true);
 
$menu= $modx->newObject('modMenu');
$menu->fromArray(array(
    'text' => 'GridEditor Demo',
    'parent' => 'components',
    'menuindex' => 0,
    'params' => '&config=demo',
    'handler' => '',
),'',true,true);
$menu->addOne($action);
unset($menus);
 
return $menu;
