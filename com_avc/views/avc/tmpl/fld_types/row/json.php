<?php


echo '
<div class="form_items">
	<label id="jform_enabled-lbl" for="jform_enabled">
		' . JText::_( strtoupper($FIELD_TITLE)) . '
	</label>
';

echo '
	<input
		tabindex=""
		type="hidden"
		name="' . $FIELD_NAME . '"
		id="' . $FIELD_NAME . '"
		value="' . htmlspecialchars($FIELD_VALUE) . '"
		class=""
		title=""
	/>
	';

echo '<div id="' . $FIELD_NAME . '_clone" style="position:absolute; left:-9999px">';



$FIELD_VALUE = json_decode($FIELD_VALUE);


echo '<select onchange="avc_json_update(\'' . $FIELD_NAME . '\');">';
if(!empty($FIELD_PARAMS)){
	foreach($FIELD_PARAMS as $OPTION) {
		echo '<option value="' . $OPTION . '">' . $OPTION . '</option>';
	}
}
echo '</select>';
echo '
<input
	onkeyup="avc_json_update(\'' . $FIELD_NAME . '\');"
	tabindex=""
	type="text"
	name=""
	value=""
	class="width_auto"
	title="' . JText::_('COM_AVC_TOOLTIPS_VARCHAR') . '"
/>
<a href="#" class="avc_button" onclick="avc_json_remove(this,\'' . $FIELD_NAME . '\');return false;">' . JText::_('COM_AVC_DELETE') . '</a>
<br style="clear:both" />
';

echo '</div>';

echo '<div class="AVC_frame" id="' . $FIELD_NAME . '_frame">';

$count=0;
if(!empty($FIELD_VALUE)){
	foreach($FIELD_VALUE as $LABEL => $VALUE) {

		$FIELD_JSONs.= 'FIELD_JSONs[\'' . $FIELD_NAME . '\'][' . $count . '] = new Array();'."\n";

		echo '<div>'."\n";

		//////////////////////////////////
		// Create droplist
		//////////////////////////////////
		echo '<select onchange="avc_json_update(\'' . $FIELD_NAME . '\');">';
		if(!empty($FIELD_PARAMS)){
			foreach($FIELD_PARAMS as $OPTION) {
				$select = "";
				if($OPTION == $LABEL){
					$select = "selected";
				}
				echo '<option value="' . $OPTION . '" ' . $select . '>' . $OPTION . '</option>';
			}
		}
		echo '</select>';

		echo '
		<input
			onkeyup="avc_json_update(\'' . $FIELD_NAME . '\');"
			tabindex="' . $TABINDEX . '"
			type="text"
			name=""
			value="' . $VALUE . '"
			class="width_auto"
			title="' . JText::_('COM_AVC_TOOLTIPS_VARCHAR') . '"
		/>
		<a href="#" class="avc_button" onclick="avc_json_remove(this, \'' . $FIELD_NAME . '\');return false;">' . JText::_('COM_AVC_DELETE') . '</a>
		<br style="clear:both" />
		';

		echo '</div>'."\n";

		$TABINDEX++;
		$count++;

	}
}
echo '
</div>
<p>
<a class="avc_button" href="#" onclick="avc_json_add(\'' . $FIELD_NAME . '\'); return false;">' . JText::_('COM_AVC_ADD') . '</a>
</p>
';

echo '
</div>
';

