<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

// var_export($AVC->state_history);

$JS_AVC_layout = '
///////////////////////////////////////
//
// AVC LAYOUT MODULE '.$AVC->module_id.'
//
///////////////////////////////////////

AVC_LAYOUT_HISTORY["module'.$AVC->module_id.'"]='.json_encode($AVC->state_history["module".$AVC->module_id]).';
AVC_TEMPLATE["module'.$AVC->module_id.'"]='.json_encode($AVC->state_tmpl).';
AVC_LAYOUT_SCROLLTO = "'.$AVC->state_scrollTo.'";

window.addEvent("domready", function(){

    AVC_LAYOUT_FORMATING("'.$AVC->module_id.'");

});

';
JFactory::getDocument()->addScriptDeclaration($JS_AVC_layout);

echo "\n"."\n".'<!-- |||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||| -->'."\n";
echo '<!-- || -->'."\n";
echo '<!-- || AVC LAYOUT MODULE '.$AVC->module_id.' -->'."\n";
echo '<!-- || -->'."\n";
echo '<!-- |||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||| -->'."\n";

echo '<div id="AVC_LAYOUT_'.$AVC->module_id.'">';

require  JPATH_ROOT . DS . 'modules' . DS . 'mod_avc' . DS . 'templates' . DS . $AVC->state_tmpl["name"] . DS . 'index.php';

echo '</div>'."\n";
echo '<!-- |||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||| -->'."\n";

?>