<?php

/**
 * Contao Open Source CMS
 */

/**
 * @file ApiController.php
 * @class ApiController
 * @author Sascha Weidner
 */


namespace Sioweb\DummyBundle\Controller;
use Contao;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiController extends Controller
{
	public function indexAction() {
		return new JsonResponse([
      'success' => 1,
      'funktion' => __METHOD__,
      'foo' => 'bar'
    ]);
	}
  
	public function ichWillAlleDateinamenAction() {
  
    $Files = [
      '/files/test.jpg',
      '/files/lorem.jpg',
    ];
    
		return new JsonResponse([
      'success' => 1,
      'funktion' => __METHOD__,
      'files' => $Files
    ]);
	}
}
