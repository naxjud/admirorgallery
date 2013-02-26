<?php

echo '
<div class="form_items form_items1">
	<label id="jform_enabled-lbl" for="jform_enabled">
		' . JText::_( strtoupper($FIELD_TITLE)) . '
	</label>
	<input
		tabindex="' . $TABINDEX . '"
		type="text"
		name="' . $FIELD_ALIAS . '"
		value="' . htmlspecialchars($FIELD_VALUE) . '"
		class=""
		title="' . JText::_('COM_AVC_TOOLTIPS_VARCHAR') . '"
	/>
</div>
';