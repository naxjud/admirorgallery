<?php

echo '<div class="form_items form_items1">';

// Create Form Field Label
echo '<label id="jform_enabled-lbl" for="jform_enabled">';
echo JText::_( strtoupper($field_alias));
echo '</label>';

$db       = JFactory::getDBO();
$conf     = JFactory::getConfig();
$dbprefix = $conf->get('dbprefix');

if(empty($field_params)){
	
    $query = $db->getQuery(true);
    $tables = $db->getTableList();
	echo '<select tabindex="'.$tabIndex.'" name="'.$field_alias.'" class="required editlinktip" title="'.JText::_('COM_CCS_TOOLTIPS_DROPLIST').'">';			
	echo '<option value="">'.JText::_("COM_CCS_SELECT_NONE").'</option>';
	foreach($tables as $result)// Add Dropbox item for any param founded
	{
		$result = substr($result, strlen($dbprefix), strlen($result));
		$selected=NULL;					
		if($result==$field_value)// Add Selected Value
		{
			$selected=' selected="selected"';
		}
    echo '<option value="'.$result.'"'.$selected.'>'.JText::_($result).'</option>';
	}	
	echo '</select>';

}else{
	$params_array = explode(",",$field_params);
    
	$query = $db->getQuery(true);
	$query->select($db->nameQuote($params_array[1]));
	$query->from($db->nameQuote($dbprefix.$params_array[0]));
	$query->order($db->getEscaped('ordering'.' '.'ASC'));
	$db->setQuery($query);
	$results = $db->loadAssocList();

	echo '<select tabindex="'.$tabIndex.'" name="'.$field_alias.'" class="required editlinktip" title="'.JText::_('COM_CCS_TOOLTIPS_DROPLIST').'">';			
	echo '<option value="">'.JText::_("COM_CCS_SELECT_NONE").'</option>';
	foreach($results as $result)// Add Dropbox item for any param founded
	{
		$selected=NULL;					
		if($result[$params_array[1]]==$field_value)// Add Selected Value
		{
			$selected=' selected="selected"';
		}
    echo '<option value="'.$result[$params_array[1]].'"'.$selected.'>'.JText::_($result[$params_array[1]]).'</option>';
	}	
	echo '</select>';
}

echo '</div>';