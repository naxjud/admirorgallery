<?php

/*------------------------------------------------------------------------
# plg_admirorframes - Admiror Frames Plugin
# ------------------------------------------------------------------------
# author    Vasiljevski & Kekeljevic
# copyright Copyright (C) 2011 admiror-design-studio.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.admiror-design-studio.com/joomla-extensions
# Technical Support:  Forum - http://www.vasiljevski.com/forum/index.php
-------------------------------------------------------------------------*/

defined( '_JEXEC' ) or die( 'Restricted access' );

//Import library dependencies
jimport('joomla.event.plugin');
jimport('joomla.plugin.plugin');
jimport( 'joomla.filesystem.folder' );


class plgContentAdmirorframes extends JPlugin
{	

	//Constructor
	function plgContentAdmirorframes( &$subject, $params )
	{
		parent::__construct( $subject, $params );
	}
	
	function onContentPrepare($context, &$row, &$params, $page = 0) {

	if ( !preg_match("#{AF[^}]*}(.*?){/AF}#s", $row->text) ) {
		return;
	}
	
	//GD check
    if (!function_exists('gd_info')) {
        // ERROR - Invalid image
        return JFactory::getApplication()->enqueueMessage( JText::_( 'GD support is not enabled' ), 'error' );
    }	

    require_once (JPATH_BASE.DS.'plugins'.DS.'content'.DS.'admirorframes'.DS.'scripts'.DS.'AF_helper.php');

    $AF = new AF_helper($this->params,JPATH_BASE.DS.'plugins'.DS.'content'.DS.'admirorframes'.DS.'templates'.DS,JURI::root().'plugins/content/admirorframes/templates/');  
    		
	  if (preg_match_all("#{AF[^}]*}(.*?){/AF}#s", $row->text, $matches, PREG_PATTERN_ORDER)>0)
	  {	
		
		foreach($matches[0] as $matchKey => $matchValue)
		{		
		
			$html=$AF->AF_createFrame(preg_replace("/{.+?}/", "", $matchValue), $matchValue, $matchKey."_".rand(0,1000000));
			$row->text = str_replace( $matchValue, $html , $row->text);
		  
		}
		
		$row->text .='<div style="clear:both"></div>';
		
		/* ========================= SIGNATURE ====================== */
		if($AF->staticParams['showSignature']==1){
				$row->text .= '<div style="display:block; font-size:10px;">';
		}else{
				$row->text .= '<div style="display:block; font-size:10px; overflow:hidden; height:1px; padding-top:1px;">';
		}
		$row->text .= '<br /><a href="http://www.admiror-design-studio.com/admiror/en/design-resources/joomla-admiror-frames" target="_blank">AdmirorFrames 1.7.x</a>, '.JText::_("author/s").' <a href="http://www.vasiljevski.com/" target="_blank">Vasiljevski</a> & <a href="http://www.admiror-design-studio.com" target="_blank">Kekeljevic</a>.<br /></div>';
	  }//if (preg_match_all("#{AdmirorFrames[^}]*}(.*?){/AdmirorFrames}#s", $row->text, $matches, PREG_PATTERN_ORDER)>0)

  }//function onPrepareContent(&$row, &$params, $limitstart) {

}//class plgContentAdmirorFrames extends JPlugin


?>
