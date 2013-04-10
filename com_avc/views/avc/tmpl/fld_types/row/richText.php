<?php

echo '<div class="form_items">';    

// Create Form Field Label
echo '<label id="AVC_frame jform_enabled-lbl" for="jform_enabled">';
echo JText::_( strtoupper($FIELD_ALIAS));
echo '</label>';
$editor =& JFactory::getEditor();
echo $editor->display($FIELD_ALIAS, $FIELD_VALUE, '400', '500', '60', '10', false);
echo '<p>&nbsp;</p>';

echo '</div>';