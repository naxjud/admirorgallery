<?php

echo '<div class="form_items">';

// Create Form Field Label
echo '<label id="jform_enabled-lbl" for="jform_enabled">';
echo JText::_( strtoupper($FIELD_ALIAS));
echo '</label>';

echo '<textarea tabindex="'.$TABINDEX.'" wrap="off" name="'.$FIELD_ALIAS.'" rows="6" class="required validate-text editlinktip">'.$FIELD_VALUE.'</textarea>';

echo '</div>';