<?php
/**
 * Copyright 2013 by Alan Pich <alan.pich@gmail.com>
 *
 * This file is part of tvImagePlus
 *
 * tvImagePlus is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * tvImagePlus is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * tvImagePlus; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
 *
 * @package tvImagePlus
 * @author Alan Pich <alan.pich@gmail.com>
 * @copyright Alan Pich 2013
 */

require dirname(__FILE__) . '/tools/build.tools.php';
require dirname(__FILE__) . '/build.config.php';
Tools::StartTimer();


// Create modx & package instance -----------------------------------------------------------------
$modx = Tools::loadModxInstance();
$builder = new modPackageBuilder($modx);
$builder->createPackage(PKG_NAMESPACE, PKG_VERSION, PKG_RELEASE);


// Register Namespace -----------------------------------------------------------------------------
$builder->registerNamespace(PKG_NAMESPACE, false, true, '{core_path}components/' . PKG_NAMESPACE . '/');


// Add Action and Menu Item ====================================================
$modx->log(modX::LOG_LEVEL_INFO,'Packaging in menu...');
$menu = include $sources['data'].'transport.menu.php';
if (empty($menu)) $modx->log(modX::LOG_LEVEL_ERROR,'Could not package in menu.');
$vehicle= $builder->createVehicle($menu,array (
    xPDOTransport::PRESERVE_KEYS => true,
    xPDOTransport::UPDATE_OBJECT => true,
    xPDOTransport::UNIQUE_KEY => 'text',
    xPDOTransport::RELATED_OBJECTS => true,
    xPDOTransport::RELATED_OBJECT_ATTRIBUTES => array (
        'Action' => array (
            xPDOTransport::PRESERVE_KEYS => false,
            xPDOTransport::UPDATE_OBJECT => true,
            xPDOTransport::UNIQUE_KEY => array ('namespace','controller'),
        ),
    ),
));
$builder->putVehicle($vehicle);
unset($vehicle,$menu);


// Create a Category for neatness ==============================================
$category= $modx->newObject('modCategory');
$category->set('id',1);
$category->set('category',PKG_NAME);
$attr = array(
        xPDOTransport::UNIQUE_KEY => 'category',
        xPDOTransport::PRESERVE_KEYS => false,
        xPDOTransport::UPDATE_OBJECT => true,
        xPDOTransport::RELATED_OBJECTS => true,
        xPDOTransport::RELATED_OBJECT_ATTRIBUTES => array (
            'Chunks' => array(
                xPDOTransport::PRESERVE_KEYS => false,
                xPDOTransport::UPDATE_OBJECT => true,
                xPDOTransport::UNIQUE_KEY => 'name',
            ),
        ),
    );



// Add in demo config chunk ====================================================
$modx->log(modX::LOG_LEVEL_INFO,'Packaging in demo config chunk..');
$chunks = array($modx->newObject('modChunk',array(
        'name' => 'grideditor.config.demo',
        'description' => 'Demo configuration file for GridEditor',
        'snippet' => getSnippetContent($sources['elements'].'chunks/grideditor.config.demo.php')
    )));
$category->addMany($chunks);


// Create Vehicle & add to package =============================================
$vehicle = $builder->createVehicle($category,$attr);


// Add in file resolvers =======================================================
$modx->log(modX::LOG_LEVEL_INFO,'Adding file resolvers to package...');
$vehicle->resolve('file',array(
    'source' => PKG_ASSETS,
    'target' => "return MODX_ASSETS_PATH . 'components/';",
));
$vehicle->resolve('file',array(
    'source' => PKG_CORE,
    'target' => "return MODX_CORE_PATH . 'components/';",
));
$builder->putVehicle($vehicle);


// Add documentation ===========================================================
$modx->log(modX::LOG_LEVEL_INFO, 'Adding documentation...');
$builder->setPackageAttributes(
    array(
        'license' => file_get_contents($sources['docs'] . 'license.txt'),
        'readme' => Tools::parseReadmeTpl($sources['docs'] . 'readme.tpl'),
        'changelog' => file_get_contents($sources['docs'] . 'changelog.txt'),
    )
);

// Create transport package -----------------------------------------------------------------------
$modx->log(modX::LOG_LEVEL_INFO, 'Packing component for transport...');
$builder->pack();


// Copy transport package back to PKG_ROOT --------------------------------------------------------
$zipFile = PKG_NAMESPACE.'-'.PKG_VERSION.'-'.PKG_RELEASE.'.transport.zip';
$zipPath = MODX_CORE_PATH.'packages/'. $zipFile;
copy($zipPath,PKG_ROOT.$zipFile);


// Build process finished -------------------------------------------------------------------------
$totalTime= sprintf("%2.4f s", Tools::stopTimer());
$modx->log(modX::LOG_LEVEL_INFO,"Package ".PKG_NAME.' '.PKG_VERSION.'-'.PKG_RELEASE." built in {$totalTime}");


exit;
