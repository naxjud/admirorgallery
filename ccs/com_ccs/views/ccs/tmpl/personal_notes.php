<?php

// Author: Igor Kekeljevic, 2012.
// No direct access
defined('_JEXEC') or die('Restricted access');
$ccs_notes_content = '';
if (isset($_COOKIE['CCS_NOTES'])) {
    $ccs_notes_content = $_COOKIE['CCS_NOTES'];
}

echo '
<hr style="clear:both;" />
<div id="CCS_notes_widthSetter" style="padding:5px;">
<fieldset>
<label>
' . JText::_("CCS_NOTES") . '
</label>
<div id="ccs_notes_wrap" style="white-space: pre-wrap; word-wrap: break-word; position:absolute;z-index:-1"></div>
<textarea spellcheck="false" style="display:block; resize: none !important; overflow:hidden;" id="ccs_notes">' . $ccs_notes_content . '</textarea>
' . JText::_("CCS_NOTES_DESC") . '
</fieldset>
</div>
<br />
';

$CCS_NOTES_JS = '

function ccs_notes_update(){
    var content = ""+$$("#ccs_notes").get("value");
    $$("#ccs_notes_wrap").set("html",content.replace(/\n/g, "<br />")+"<br /><br />");
    Cookie.write(\'CCS_NOTES\', content, {duration: 99999});
    $$("#ccs_notes").setStyle("height",$$("#ccs_notes_wrap").getStyle("height"));
}

window.addEvent("domready", function(){

    // Update widths
    var css_nodes_width = $("CCS_notes_widthSetter").getComputedSize().width-10;
    $$("#ccs_notes").setStyle("width",css_nodes_width+"px");
    $$("#ccs_notes_wrap").setStyle("width",css_nodes_width+"px");

    ccs_notes_update();

    $$("#ccs_notes").addEvents({
        "keyup": function(e){
            ccs_notes_update();
        }
    });

});
';
$this->doc->addScriptDeclaration($CCS_NOTES_JS);

// ############################################### PERSONAL NOTES
?>