<?php

$form_items_width = 1;
if( !empty( $FIELD_PARAMS["width"] ) ){
	$form_items_width = $FIELD_PARAMS["width"];
}
$form_items_height = 3;
if( !empty( $FIELD_PARAMS["height"] ) ){
	$form_items_height = $FIELD_PARAMS["height"];
}

echo '
<div class="form_items form_item_width_'. $form_items_width .' form_item_height_'. $form_items_height .'">
';

echo '
	<label id="jform_enabled-lbl" for="jform_enabled">
		' . JText::_( strtoupper($FIELD_TITLE)) . '
	</label>
';

echo '
	<input
		tabindex=\'\'
		type=\'hidden\'
		name=\'' . $FIELD_ALIAS . '\'
		id=\'' . $FIELD_ALIAS . '\'
		value=\'' . htmlspecialchars($FIELD_VALUE) . '\'
		class=\'\'
		title=\'\'
	/>
	';


$FIELD_VALUE = json_decode($FIELD_VALUE);

//////////////////////////////////////////////////////////////////////
//
// TEMPLATE FOR INPUT
//
echo '<div id="' . $FIELD_ALIAS . '_clone" style="position:absolute; left:-9999px">';

echo '<select onchange="avc_json_update(\'' . $FIELD_ALIAS . '\');">';
if(!empty($FIELD_PARAMS)){
  foreach($FIELD_PARAMS as $OPTION) {
    echo '<option value="' . $OPTION . '">' . $OPTION . '</option>';
  }
}
echo '</select>';

echo '
<input
  onkeyup="avc_json_update(\'' . $FIELD_ALIAS . '\');"
  tabindex=""
  type="text"
  name=""
  value=""
  class="width_auto"
  title="' . JText::_('COM_AVC_TOOLTIPS_VARCHAR') . '"
/>
<a href="#" class="avc_button" onclick="avc_json_remove(this,\'' . $FIELD_ALIAS . '\');return false;">' . JText::_('COM_AVC_DELETE') . '</a>
<br style="clear:both;" />
';

echo '</div>';
//
//////////////////////////////////////////////////////////////////////



//////////////////////////////////////////////////////////////////////
//
// SET INIT STATE
//
echo '<div class="AVC_frame" id="' . $FIELD_ALIAS . '_frame">';

$FIELD_JSONs="";
if(!empty($FIELD_VALUE)){
  foreach($FIELD_VALUE as $INDEX => $ENTRY) {
	foreach($ENTRY as $LABEL => $VALUE) {

    $FIELD_JSONs.= 'FIELD_JSONs[\'' . $FIELD_ALIAS . '\'][' . $INDEX . '] = new Array();'."\n";

    echo '<div>'."\n";

    //////////////////////////////////
    // Create droplist
    //////////////////////////////////
    echo '<select onchange="avc_json_update(\'' . $FIELD_ALIAS . '\');">';

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
      onkeyup="avc_json_update(\'' . $FIELD_ALIAS . '\');"
      tabindex="' . $TABINDEX . '"
      type="text"
      name=""
      value="' . $VALUE . '"
      class="width_auto"
      title="' . JText::_('COM_AVC_TOOLTIPS_VARCHAR') . '"
    />
    <a href="#" class="avc_button" onclick="avc_json_remove(this, \'' . $FIELD_ALIAS . '\');return false;">' . JText::_('COM_AVC_DELETE') . '</a>
    <br style="clear:both" />
    ';

    echo '</div>'."\n";

    $TABINDEX++;

	}
  }
}
echo '</div>';
//
////////////////////////////////////////////////////////////////////




echo '
<p>
<a class="avc_button" href="#" onclick="avc_json_add(\'' . $FIELD_ALIAS . '\'); return false;">' . JText::_('COM_AVC_ADD') . '</a>
</p>
';
	
echo '
</div>
';

