<?php

echo '
<div class="form_items form_items1">
	<label id="jform_enabled-lbl" for="jform_enabled">
		' . JText::_( strtoupper($FIELD_ALIAS)) . '
	</label>
	<span>' . htmlspecialchars($FIELD_VALUE) . '</span>
</div>
';