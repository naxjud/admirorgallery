<?php

echo '<div class="form_items">';    

// Create Form Field Label
echo '<label id="jform_enabled-lbl" for="jform_enabled">';
echo JText::_( strtoupper($field_alias));
echo '</label>';
$editor =& JFactory::getEditor();
echo $editor->display($field_alias, $field_value, '400', '500', '60', '10', false);
echo '<p>&nbsp;</p>';

echo '</div>';