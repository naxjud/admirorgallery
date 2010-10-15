<?php
 
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.view');
 
class AdmirorgalleryViewLayout extends JView
{
    function display($tpl = null)
    {

	$mainframe = &JFactory::getApplication();
	$params = &$mainframe->getParams();

	$this->assignRef( 'galleryName', $params->get( 'galleryName' ) );
	$this->assignRef( 'template', $params->get( 'template' ) );
	$this->assignRef( 'thumbWidth', $params->get( 'th_width' ) );
	$this->assignRef( 'thumbHeight', $params->get( 'th_height' ) );
	$this->assignRef( 'thumbAutoSize', $params->get( 'th_autoSize' ) );
	$this->assignRef( 'arrange', $params->get( 'arrange' ) );
	$this->assignRef( 'newImageTag', $params->get( 'newImageTag' ) );
	$this->assignRef( 'newImageDays', $params->get( 'newImageTag_days' ) );
	$this->assignRef( 'frameWidth', $params->get( 'frame_width' ) );
	$this->assignRef( 'frameHeight', $params->get( 'frame_height' ) );
	$this->assignRef( 'showSignature', $params->get( 'showSignature' ) );
	$this->assignRef( 'popup', $params->get( 'popupEngine' ) );
	$this->assignRef( 'foregroundColor', $params->get( 'foregroundColor' ) );
	$this->assignRef( 'highliteColor', $params->get( 'highliteColor' ) );

        parent::display($tpl);

    }
}
