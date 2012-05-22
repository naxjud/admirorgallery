<?php
 
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.view');

class AdmirorgalleryViewLayout extends JView
{

    function display($tpl = null)
    {

	$mainframe = &JFactory::getApplication('site');
	$params = &$mainframe->getParams();

	$this->assignRef( 'galleryName', $params->get( 'galleryName' ) );
	$this->assignRef( 'template', $params->get( 'template' ) );
	$this->assignRef( 'thumbWidth', $params->get( 'thumbWidth' ) );
	$this->assignRef( 'thumbHeight', $params->get( 'thumbHeight' ) );
	$this->assignRef( 'thumbAutoSize', $params->get( 'thumbAutoSize' ) );
	$this->assignRef( 'arrange', $params->get( 'arrange' ) );
	$this->assignRef( 'newImageTag', $params->get( 'newImageTag' ) );
	$this->assignRef( 'newImageDays', $params->get( 'newImageTag_days' ) );
	$this->assignRef( 'frameWidth', $params->get( 'frame_width' ) );
	$this->assignRef( 'frameHeight', $params->get( 'frame_height' ) );
	$this->assignRef( 'showSignature', $params->get( 'showSignature' ) );
	$this->assignRef( 'popupEngine', $params->get( 'popupEngine' ) );
	$this->assignRef( 'foregroundColor', $params->get( 'foregroundColor' ) );
	$this->assignRef( 'backgroundColor', $params->get( 'backgroundColor' ) );
	$this->assignRef( 'highliteColor', $params->get( 'highliteColor' ) );
	$this->assignRef( 'plainTextCaptions', $params->get( 'plainTextCaptions' ) );

        // Albums Support
	$this->assignRef( 'albumUse', $params->get( 'albumUse' ) );
        // Paginations Support
	$this->assignRef( 'paginUse', $params->get( 'paginUse' ) );
	$this->assignRef( 'paginImagesPerGallery', $params->get( 'paginImagesPerGallery' ) );
    

        parent::display($tpl);

    }
}