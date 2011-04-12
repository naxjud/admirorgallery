<?php
 
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// Access check
$user =& JFactory::getUser();
if($user->usertype == "Super Administrator" || $user->usertype == "Administrator" || $user->usertype == "Manager" )
{
    
}else{
    die( 'Restricted access' );
}
 
jimport( 'joomla.application.component.view');

class AdmirorgalleryViewImagemanager extends JView
{
    function display($tpl = null)
    {

	  $mainframe = &JFactory::getApplication();
	  $params = &$mainframe->getParams();

	  $this->assignRef( 'galleryName', $params->get( 'galleryName' ) );

	  parent::display($tpl);

    }
}
