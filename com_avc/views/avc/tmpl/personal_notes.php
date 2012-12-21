<?php

// Author: Igor Kekeljevic, 2012.
// No direct access
defined('_JEXEC') or die('Restricted access');
$avc_notes_content = '';
if (isset($_COOKIE['AVC_NOTES'])) {
    $avc_notes_content = $_COOKIE['AVC_NOTES'];
}

echo '
<hr style="clear:both;" id="AVC_notes_widthSetter" />
<br />
<div style="margin-left:5px">
<fieldset class="adminform">
<label>
' . JText::_("AVC_NOTES") . '
</label>
<div id="avc_notes_wrap" style="display:block; white-space: pre-wrap; word-wrap: break-word; position:absolute;z-index:-1"></div>
<textarea spellcheck="false" style="display:block; resize: none !important; overflow:hidden;" id="avc_notes">' . $avc_notes_content . '</textarea>
' . JText::_("AVC_NOTES_DESC") . '
</fieldset>
<div>
<br />
';

$AVC_NOTES_JS = '

function avc_notes_update(){
    var content = ""+$$("#avc_notes").get("value");
    $$("#avc_notes_wrap").set("html",content.replace(/\n/g, "<br />")+"<br /><br />");
    Cookie.write(\'AVC_NOTES\', content, {duration: 99999});
    $$("#avc_notes").setStyle("height",$$("#avc_notes_wrap").getStyle("height"));
}

window.addEvent("domready", function(){

    // Update widths
    var css_nodes_width = $("AVC_notes_widthSetter").getComputedSize().totalWidth-20;
    $$("#avc_notes").setStyle("width",css_nodes_width+"px");
    $$("#avc_notes_wrap").setStyle("width",css_nodes_width+"px");

    avc_notes_update();

    $$("#avc_notes").addEvents({
        "keyup": function(e){
            avc_notes_update();
        }
    });

});
';
$this->doc->addScriptDeclaration($AVC_NOTES_JS);

// ############################################### PERSONAL NOTES
?>