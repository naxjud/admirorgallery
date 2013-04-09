<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport( 'joomla.application.component.controller' );
jimport('joomla.html.parameter');

class AvcController extends JController
{
    function display($cachable = false, $urlparams = false)
    {
        parent::display();
    }
}