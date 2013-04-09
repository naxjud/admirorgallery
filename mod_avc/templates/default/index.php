<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

$showTemplate = true;
if( empty($AVC->output) && empty($AVC->state_having["search"]) ){
	$showTemplate = false;
}
if($showTemplate){

JFactory::getDocument()->addStyleSheet( JURI::root() . 'modules/mod_avc/templates/template.css' );
JFactory::getDocument()->addStyleSheet( JURI::root() . 'modules/mod_avc/templates/default/css/template.css' );

//var_dump($AVC->output);

// INSERT BREADCRUMBS
require dirname(dirname(dirname(__FILE__))) . DS . 'extensions' . DS . 'breadcrumbs'. DS .'index.php';

echo '<hr class="AVC_SEPARATOR_LARGE" />';

echo '<h1>'.JText::_($AVC->state_view_name).'</h1>';

// INSERT SEARCH
require dirname(dirname(dirname(__FILE__))) . DS . 'extensions' . DS . 'search'. DS .'index.php';

echo '<table cellpadding="0" cellspacing="0" border="0" class="AVC_LAYOUT_TABLE" width="100%">'."\n";

echo '<thead>'."\n";
echo '<tr>'."\n";
foreach ($AVC->state_fieldNames as $fieldValue) {
	echo '<th class="AVC_LAYOUT_HOVER" onclick="AVC_LAYOUT_ORDER(\''.$AVC->group.'\', \''.$AVC->module_id.'\',\''.$fieldValue.'\');" title="'.$fieldValue.'">';
	echo JText::_($fieldValue);
	echo '</th>'."\n";		
}
echo '</tr>'."\n";
echo '</thead>'."\n";

echo '<tbody>'."\n";

if( !empty($AVC->output) ){

	foreach ($AVC->output as $rowIndex => $rowContent) {
	if(!empty( $AVC->state_tmpl["vars"]["key"] )){	
		if(!empty($rowContent[ $AVC->state_tmpl["vars"]["key"] ])){	
			$AVC_LAYOUT_OPTIONS = '\''.$rowContent[ $AVC->state_tmpl["vars"]["key"] ].'\',\''.$rowContent["title"].'\'';
		}
	}	
	if(!empty($AVC_LAYOUT_OPTIONS)){
		echo '<tr class="AVC_LAYOUT_HOVER" onclick="AVC_LAYOUT_OPEN(\''.$AVC->group.'\', \''.$AVC->module_id.'\',['.$AVC_LAYOUT_OPTIONS.']);">'."\n";
	}else{
		echo '<tr>'."\n";
	}
		foreach ($rowContent as $fieldAlias => $fieldValue) {
			echo '<td>';
			echo $fieldValue;
			echo '</td>'."\n";		
		}
		echo '</tr>'."\n";
	}
	
}//if(!empty($AVC->output)){

echo '</tbody>'."\n";

echo '</table>';

// INSERT PAGINATION
require dirname(dirname(dirname(__FILE__))) . DS . 'extensions' . DS . 'pagination'. DS .'index.php';

echo '<p style="clear:both">&nbsp;</p>';


}//if($showTemplate){