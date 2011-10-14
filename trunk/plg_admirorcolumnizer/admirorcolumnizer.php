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
        // jimport needed by Joomla 1.6 and >
		jimport('joomla.html.parameter');
        $this->plugin = JPluginHelper::getPlugin('content', 'admirorcolumnizer');
        $this->params = new JParameter($this->plugin->params);
    }
	//Joomla 1.5
	public function onPrepareContent( &$row, &$params, $limitstart = 0 )
	{
		$row->text = $this->textToColumns($row->text);
	}
	//Joomla 1.6 and > function 
	public function onContentPrepare($context, &$row, &$params, $page = 0)
	{
		if(is_object($row)) {
			return $this->onPrepareContent($row, $params, $page);
		} else {
			$row = $this->textToColumns($row);
		}
		return true;
	}
	//This does all the work :)
	private function textToColumns($text)
	{
		if ( !preg_match("#{AC[^}]*}(.*?){/AC}|{ac[^}]*}(.*?){/ac}#s", strtoupper(($text))) ) {
			return;
		}	
		require_once (dirname(__FILE__).'/admirorcolumnizer/scripts/AC_helper.php');

		$AC = new AC_helper($this->params);  

		$doc = &JFactory::getDocument();
		if(version_compare(JVERSION,'1.6.0') >= 0) {
			$doc->addScript(JURI::root().'plugins/content/admirorcolumnizer/admirorcolumnizer/scripts/AC_jQuery.js');
			$doc->addScript(JURI::root().'plugins/content/admirorcolumnizer/admirorcolumnizer/scripts/autocolumn.min.js');
		} else {
			$doc->addScript(JURI::root().'plugins/content/admirorcolumnizer/scripts/AC_jQuery.js');
			$doc->addScript(JURI::root().'plugins/content/admirorcolumnizer/scripts/autocolumn.min.js');
		}
		

		if (preg_match_all("#{AC[^}]*}(.*?){/AC}|{ac[^}]*}(.*?){/ac}#s", $text, $matches, PREG_PATTERN_ORDER)>0)
		{	
			$html="";
			foreach($matches[0] as $matchKey => $matchValue)
			{	
				$html=$AC->AC_createColumns(preg_replace("/{.+?}/", "", $matchValue), $matchValue, $matchKey."_".rand(0,1000000), $doc->direction);
				$text = str_replace( $matchValue, $html , $text);
			}
		}
		return $text;
	}
}//class plgContentAdmirorcolumnizer extends JPlugin
?>
