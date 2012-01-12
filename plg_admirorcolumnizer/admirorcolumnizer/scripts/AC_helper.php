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

// no direct access
defined('_JEXEC') or die('Restricted access');

class AC_helper {
	var $params =  array();
	var $staticParams = array();
	function  __construct($globalParams) { 
		// Default parameters
		$this->staticParams['columnsWidth'] = $globalParams->get('ac_columnsWidth', 2);
		$this->staticParams['spacing'] = $globalParams->get('ac_spacing', 10);
	}
	//Gets the atributes value by name, else returns false
	private function AC_getAttribute($attrib, $tag, $default) {
		//get attribute from html tag
		$tag = str_replace("}","",$tag);
		$re = '/' . preg_quote($attrib) . '=([\'"])?((?(1).+?|[^\s>]+))(?(1)\1)/is';
		if (preg_match($re, $tag, $match)) {
			return urldecode($match[2]);
		}
		return $default;
	}
	function AC_createColumns($source_html, $matchValue, $id, $langDirection) {
		
		$this->params['columnsWidth'] = $this->AC_getAttribute("columnsWidth",$matchValue,$this->staticParams['columnsWidth'],200);
		$this->params['spacing'] = $this->AC_getAttribute("spacing",$matchValue,$this->staticParams['spacing'],10);

		$html="";

		// Change style to lang direction
		if($langDirection == "ltr"){
			$AC_landDirectionStyles="text-align:left; float:left; margin:0 ".$this->params['spacing']."px ".$this->params['spacing']."px 0;";
		}else{
			$AC_landDirectionStyles="text-align:right; float:right; margin:0 0 ".$this->params['spacing']."px ".$this->params['spacing']."px;";
		}

		// Split string at separators
		$columnsArray=explode("ACBR",$source_html);
		foreach($columnsArray as $key => $value){
			$AC_margin=$this->params['spacing'];
			if(count($columnsArray)-1 == $key){// Check is last
				if($langDirection == "ltr"){// Check lang
					$AC_landDirectionStyles.="margin-right:0;";
				}else{
					$AC_landDirectionStyles.="margin-left:0;";
				}
			}
			$html.='<div style="display:inline-block; width:'.$this->params['columnsWidth'].'px; '.$AC_landDirectionStyles.'">'.$value.'</div>';
		}
		$html.='<br style="clear:both;" />';

		return $html;
	}
}





  
  
  

  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  

?>
