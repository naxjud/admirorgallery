<?php

/*------------------------------------------------------------------------
# plg_Admirorcolumnizer - Admiror Columnizer Plugin
# ------------------------------------------------------------------------
# author    Vasiljevski & Kekeljevic
# copyright Copyright (C) 2011 admiror-design-studio.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.admiror-design-studio.com/joomla-extensions
# Technical Support:  Forum - http://www.vasiljevski.com/forum/index.php
-------------------------------------------------------------------------*/

defined('_JEXEC') or die('Restricted access');

// Import library dependencies
jimport('joomla.event.plugin');
jimport('joomla.plugin.plugin');

class plgContentAdmirorcolumnizer extends JPlugin {
    //Constructor
    function plgContentAdmirorcolumnizer(&$subject) {
        parent::__construct($subject);
        // load plugin parameters
        $this->plugin = JPluginHelper::getPlugin('content', 'admirorcolumnizer');
        $this->params = new JParameter($this->plugin->params);
    }
    function onPrepareContent(&$row, &$params, $limitstart) {

	if ( !preg_match("#{AC[^}]*}(.*?){/AC}|{ac[^}]*}(.*?){/ac}#s", strtoupper($row->text)) ) {
		return;
	}	
	require_once (JPATH_BASE.DS.'plugins'.DS.'content'.DS.'admirorcolumnizer'.DS.'scripts'.DS.'AC_helper.php');

	$AC = new AC_helper($this->params);  

	$doc = &JFactory::getDocument();
	$doc->addScript(JURI::root().'plugins/content/admirorcolumnizer/scripts/AC_jQuery.js');
	$doc->addScript(JURI::root().'plugins/content/admirorcolumnizer/scripts/autocolumn.min.js');

	if (preg_match_all("#{AC[^}]*}(.*?){/AC}|{ac[^}]*}(.*?){/ac}#s", $row->text, $matches, PREG_PATTERN_ORDER)>0)
	{	
		
		$html="";
		foreach($matches[0] as $matchKey => $matchValue)
		{	

			$html=$AC->AC_createColumns(preg_replace("/{.+?}/", "", $matchValue), $matchValue, $matchKey."_".rand(0,1000000), $doc->direction);
			$row->text = str_replace( $matchValue, $html , $row->text);
		  
		}

	}//if (preg_match_all("#{Admirorcolumnizer[^}]*}(.*?){/Admirorcolumnizer}#s", $row->text, $matches, PREG_PATTERN_ORDER)>0)

    }//onPrepareContent(&$row, &$params, $limitstart)

}//class plgContentAdmirorcolumnizer extends JPlugin
?>
