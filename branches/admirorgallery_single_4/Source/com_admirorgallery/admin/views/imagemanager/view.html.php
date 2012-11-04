<?php

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

class AdmirorgalleryViewImagemanager extends JView
{

    function display($tpl = null)
    {
	  JToolBarHelper::title( JText::_( 'COM_ADMIRORGALLERY_IMAGE_MANAGER' ), 'imagemanager' );
	  parent::display($tpl);
    }
}
