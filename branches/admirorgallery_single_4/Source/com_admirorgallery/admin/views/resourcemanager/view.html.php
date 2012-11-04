<?php

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

class AdmirorgalleryViewResourcemanager extends JView
{

    function display($tpl = null)
    {
          $AG_resourceType = JRequest::getVar( 'AG_resourceType' );// Current resource type
	  JToolBarHelper::title( JText::_( 'COM_ADMIRORGALLERY_'.strtoupper($AG_resourceType)), $AG_resourceType);
	  parent::display($tpl);
    }
}
