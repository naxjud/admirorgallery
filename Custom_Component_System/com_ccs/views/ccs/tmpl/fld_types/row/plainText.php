<?php

echo '<div class="form_items form_items2">';

// Create Form Field Label
echo '<label id="jform_enabled-lbl" for="jform_enabled">';
echo JText::_( strtoupper($field_alias));
echo '</label>';

echo '<textarea tabindex="'.$tabIndex.'" wrap="off" name="'.$field_alias.'" rows="6" class="required validate-text editlinktip">'.$field_value.'</textarea>';

echo '</div>';