<?php

/**
 * Get the contents of a file, stripping out php tags
 * @param string $filename Path to file
 * @return string 
 */
function getSnippetContent($filename) {
    $o = file_get_contents($filename);
    $o = trim(str_replace(array('<?php','?>'),'',$o));
    return $o;
}


/**
 * Parse the smarty readme tpl for packaging
 * @param string $path Path to tpl
 * @return string
 */
function getReadmeFile( $path ){
    global $modx;
    $modx->getService('smarty','smarty.modSmarty');
    
    $modx->smarty->assign('date',date("jS M Y g:ia"));
    $modx->smarty->assign('version',PKG_VERSION.' '.PKG_RELEASE);
    $readme = $modx->smarty->fetch($path);
    return $readme;
}//



/**
 * 
 */