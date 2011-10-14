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

class AC_helper {
	var $params =  array();
	var $staticParams = array();
	function  __construct($globalParams) { 
		// Default parameters
		$this->staticParams['columns'] = $globalParams->get('ac_columns', 2);
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
		// ---------------------------------------------------------- LANG DIRECTION RELATED PARAMS
		if($langDirection == "ltr"){
			$AC_langDirection="left";
			$AC_marginSide="right";
		}else{
			$AC_langDirection="right";
			$AC_marginSide="left";
		}
		// ---------------------------------------------------------- GET PARAMS
		$this->params['columns'] = $this->AC_getAttribute("columns",$matchValue,$this->staticParams['columns'],2);
		$this->params['spacing'] = $this->AC_getAttribute("spacing",$matchValue,$this->staticParams['spacing'],10);

		$html="<!-- AdmirorColumnizer -->"."\n";
		$html.='<div class="AC_columnizer_'.$id.'"><p>'."\n";
		$html.=$source_html."\n";
		$html.='</p></div><div style="clear:both"></div>'."\n";
		$html.="
		<script type='text/javascript'>
			AC_jQuery(function(){
				var ac_cols_parent_width = AC_jQuery('.AC_columnizer_".$id."').width();
				var ac_cols_spacing = ".$this->params['spacing'].";
				var ac_cols_num = ".$this->params['columns'].";
				var ac_cols_width = Math.floor((ac_cols_parent_width-((ac_cols_num-1)*ac_cols_spacing)-1)/ac_cols_num);

				AC_jQuery('.AC_columnizer_".$id."').columnize({
					width : (ac_cols_width-ac_cols_spacing),
					columns : ".$this->params['columns'].",
					float: '".$AC_langDirection."',
					lastNeverTallest: 'true',
					doneFunc : function(){
						AC_jQuery('.AC_columnizer_".$id." .column').width(ac_cols_width);
					}
				})

			});
		</script>
		<style type='text/css'>
			.AC_columnizer_".$id." .column{ margin:0; padding:0; margin-".$AC_marginSide.": ".$this->params['spacing']."px;}
			.AC_columnizer_".$id." .column p{ text-align:".$AC_langDirection.";}
			.AC_columnizer_".$id." .last.column{ margin-".$AC_marginSide.": 0; }
		</style>
		";
		return $html;
	}
}





  
  
  

  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  

?>
