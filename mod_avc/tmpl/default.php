<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

// var_export($AVC->state_history);
//var_dump($AVC->state_history_snapshot);

$JS_AVC_layout = '
///////////////////////////////////////
//
// AVC LAYOUT MODULE '.$AVC->module_id.'
//
///////////////////////////////////////
';

$JS_AVC_layout.= '
AVC_LAYOUT_HISTORY["'.$AVC->group.'"]='.json_encode($AVC->state_history[$AVC->group]).';
AVC_TEMPLATE["'.$AVC->module_id.'"]='.json_encode($AVC->state_tmpl).';
AVC_LAYOUT_VISUALS = '.json_encode($AVC->state_visuals).';
AVC_COLLECTION = '.json_encode($AVC->collection).';

if(AVC_LAYOUT_VISUALS["accordion"]){	
	AVC_LAYOUT_ACCORDION = AVC_LAYOUT_VISUALS["accordion"];
}

window.addEvent("domready", function(){

    AVC_LAYOUT_FORMATING("'.$AVC->group.'", "'.$AVC->module_id.'");

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