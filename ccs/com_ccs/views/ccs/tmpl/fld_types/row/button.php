<?php

echo '<div class="form_items form_items1">';

// Create Form Field Label
echo '<label id="jform_enabled-lbl" for="jform_enabled">';
echo JText::_(strtoupper($field_alias));
echo '</label>';

$javascript__label = explode("|", $field_params);
echo '<input type="button" class="pointer width_auto" onclick="' . $javascript__label[0] . '" value="' . JText::_($javascript__label[1]) . '" />';

echo '</div>';