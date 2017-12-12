<?php

/**
 * Contao Open Source CMS
 */

/**
 * @file autoload.php
 * @author Sascha Weidner
 * @version 3.0.0
 * @package sioweb.dummybundle
 * @copyright Sioweb, Sascha Weidner
 */


if(TL_MODE == 'FE') {
	// Pfad ggf. anpassen
	// Alle Dateien in /src/Ressources/public werden unter /web/bundles/bundle-name
	// als Symlink veröffentlicht nach composer install/update
	$GLOBALS['TL_CSS'][] = 'bundles/contao4dummy/css/dummy.css|static';
	$GLOBALS['TL_JAVASCRIPT'][] = 'bundles/contao4dummy/js/dummy.js|static';
}

array_insert($GLOBALS['TL_CTE']['texts'],2,array (
	'content_dummy' => 'Sioweb\ContentElement\ContentDummy',
));
