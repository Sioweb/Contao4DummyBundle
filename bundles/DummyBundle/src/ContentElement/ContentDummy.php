<?php

namespace Sioweb\ContentElement;
use Contao\ContentElement;

class ContentDummy extends ContentElement {

  protected $strTemplate = 'dummy_default';
  
  public function generate() {
    return parent::generate();
  }

  public function compile() {
    $this->Template->dummy = '<pre>Service: '.print_r($this->getContainer()->get('sioweb.dummybundle.search')->getResult(),1).'</pre>';
  }

}
