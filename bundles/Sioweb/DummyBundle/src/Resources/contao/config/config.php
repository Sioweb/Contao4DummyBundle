<?php

/**
 * Contao Open Source CMS
 */

/**
 * @file autoload.php
 * @author Sascha Weidner
 * @package sioweb.dummybundle
 * @copyright Sioweb, Sascha Weidner
 */

// Achtung:
// Diese Datei ist nur in Version 4.4 nötig, aber 4.5 kann die Datei weggelassen werden.
// Die Config befindet sich dann in der Klasse DummyBundle/src/EventListener/System
// Der "Hook", der die Klasse aufruft, ist in DummyBundle/src/Resoureces/config/listener.yml definiert

if(VERSION <= 4.5) {
    if (TL_MODE === 'FE') { // Früher TL_MODE == 'FE'
        // Pfad ggf. anpassen
        // Alle Dateien in /src/Ressources/public werden unter /web/bundles/bundle-name
        // als Symlink veröffentlicht nach composer install/update
        $GLOBALS['TL_CSS'][] = 'bundles/dummybundle/css/dummy.css';
        $GLOBALS['TL_JAVASCRIPT'][] = 'bundles/dummybundle/js/dummy.js';
    }

    array_insert($GLOBALS['TL_CTE']['texts'], 2, array(
        'content_dummy' => 'Sioweb\ContentElement\ContentDummy',
    ));
}