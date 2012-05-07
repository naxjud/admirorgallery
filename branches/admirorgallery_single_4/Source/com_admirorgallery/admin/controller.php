<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport( 'joomla.application.component.controller' );
jimport('joomla.html.parameter');

class AdmirorgalleryController extends JController
{
    function display( )
    {
        require_once JPATH_COMPONENT.'/helpers/admirorgallery.php';
        AdmirorGalleryHelper::addSubmenu(JRequest::getCmd('view', 'control_panel'));
        parent::display();
    }
}