<?php
/*
// Admiror Frames
// Author: Igor Kekeljevic & Nikola Vasiljevski, 2011.
*/

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
	
    require_once (JPATH_BASE.DS.'plugins'.DS.'content'.DS.'admirorframes'.DS.'scripts'.DS.'AF_helper.php');

    $AF = new AF_helper();  
    
  // Default parameters
	$AF->staticParams['template'] = $this->params->get('af_template', 'default');
	$AF->staticParams['bgcolor'] = $this->params->get('af_bgcolor', 'white');	
	$AF->staticParams['colorize'] = $this->params->get('af_colorize', '000000');	
	$AF->staticParams['ratio'] = $this->params->get('af_ratio', '100');
	$AF->staticParams['width'] = $this->params->get('af_width', '100%');
	$AF->staticParams['height'] = $this->params->get('af_height', '');	
	$AF->staticParams['margin'] = $this->params->get('af_margin', '0');
	$AF->staticParams['padding'] = $this->params->get('af_padding', '0');
	$AF->staticParams['horiAlign'] = $this->params->get('af_horiAlign', 'left');
	$AF->staticParams['vertAlign'] = $this->params->get('af_vertAlign', 'top');
	$AF->staticParams['float'] = $this->params->get('af_float', 'none');
	$AF->staticParams['showSignature'] = $this->params->get('af_showSignature', '1');
    $AF->params['templates_BASE'] = JPATH_BASE.DS.'plugins'.DS.'content'.DS.'admirorframes'.DS.'templates'.DS;  
    $AF->params['templates_ROOT'] = JURI::root().'plugins/content/admirorframes/templates/';  

		
	  if (preg_match_all("#{AF[^}]*}(.*?){/AF}#s", $row->text, $matches, PREG_PATTERN_ORDER)>0)
	  {	
		
		foreach($matches[0] as $matchKey => $matchValue)
		{		
		
			$html=$AF->AF_createFrame(preg_replace("/{.+?}/", "", $matchValue), $matchValue);
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
