<?php

namespace Sioweb\DummyBundle\EventListener;

use Contao\CoreBundle\Routing\ScopeMatcher;
use Symfony\Component\HttpFoundation\RequestStack;

class System {
    
    private $requestStack;
    private $scopeMatcher;

    public function __construct(RequestStack $requestStack, ScopeMatcher $scopeMatcher) {
        $this->requestStack = $requestStack;
        $this->scopeMatcher = $scopeMatcher;
    }

    public function isBackend() {
        return $this->scopeMatcher->isBackendRequest($this->requestStack->getCurrentRequest());
    }

    public function isFrontend() {
        return $this->scopeMatcher->isFrontendRequest($this->requestStack->getCurrentRequest());
    }

    public function initializeSystem() {
        if($this->isFrontend) { // Früher TL_MODE == 'FE'
            // Pfad ggf. anpassen
            // Alle Dateien in /src/Ressources/public werden unter /web/bundles/bundle-name
            // als Symlink veröffentlicht nach composer install/update
            $GLOBALS['TL_CSS'][] = 'bundles/dummybundle/css/dummy.css|static';
            $GLOBALS['TL_JAVASCRIPT'][] = 'bundles/dummybundle/js/dummy.js|static';
        }
        
        array_insert($GLOBALS['TL_CTE']['texts'],2,array (
            'content_dummy' => 'Sioweb\DummyBundle\ContentElement\ContentDummy',
        ));
    }

}