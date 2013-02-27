<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

JFactory::getDocument()->addStyleSheet( JURI::root() . 'modules/mod_avc/templates/template.css' );
JFactory::getDocument()->addStyleSheet( JURI::root() . 'modules/mod_avc/templates/default/css/template.css' );

//var_dump($AVC->output);

// INSERT BREADCRUMBS
require dirname(dirname(dirname(__FILE__))) . DS . 'extensions' . DS . 'breadcrumbs'. DS .'index.php';

// INSERT SEARCH
// require dirname(dirname(dirname(__FILE__))) . DS . 'extensions' . DS . 'search'. DS .'index.php';

echo '<table cellpadding="0" cellspacing="0" border="0" class="AVC_LAYOUT_TABLE" width="100%">'."\n";

echo '<thead>'."\n";
echo '<tr>'."\n";
foreach ($AVC->output[0] as $fieldAlias => $fieldValue) {
	if($fieldAlias!="id"){
		echo '<th class="AVC_LAYOUT_HOVER" onclick="AVC_LAYOUT_ORDER(\''.$AVC->module_id.'\',\''.$fieldAlias.'\');" title="'.$fieldAlias.'">';
		echo JText::_($fieldAlias);
		echo '</th>'."\n";		
	}
		
}
echo '</tr>'."\n";
echo '</thead>'."\n";

echo '<tbody>'."\n";
foreach ($AVC->output as $rowIndex => $rowContent) {

$AVC_LAYOUT_OPTIONS = $rowContent[ $AVC->state_tmpl["vars"]["key"] ];
	
if($AVC_LAYOUT_OPTIONS){
	echo '<tr class="AVC_LAYOUT_HOVER" onclick="AVC_LAYOUT_OPEN(\''.$AVC->module_id.'\',[\''.$AVC_LAYOUT_OPTIONS.'\']);">'."\n";
}else{
	echo '<tr>'."\n";
}
	foreach ($rowContent as $fieldAlias => $fieldValue) {
		if($fieldAlias!="id"){
		echo '<td>';
		echo $fieldValue;
		echo '</td>'."\n";	
		}	
	}
	echo '</tr>'."\n";
}
echo '</tbody>'."\n";

echo '</table>';

// INSERT PAGINATION
require dirname(dirname(dirname(__FILE__))) . DS . 'extensions' . DS . 'pagination'. DS .'index.php';

echo '<p style="clear:both">&nbsp;</p>';
