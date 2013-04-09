<?php

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// Shared scripts for all views
$doc = JFactory::getDocument();
// $doc->addScript(JURI::root().'plugins/content/admirorgallery/admirorgallery/AG_jQuery.js');

// Require the base controller
require_once( JPATH_COMPONENT.DS.'controller.php' );
 
// Require specific controller if requested
if($controller = JRequest::getWord('controller', 'collection')) {
     $path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
     if (file_exists($path)) {
	  require_once $path;
     } else {
	  $controller = '';
     }
}
 
// Create the controller
$classname    = 'AvcController'.$controller;
$controller   = new $classname( );
 
// Perform the Request task
$controller->execute( JRequest::getWord( 'task' ) );
 
// Redirect if set by the controller
$controller->redirect();
