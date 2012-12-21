<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

// ADD WRAPPER
echo '
<input type="text" name="p" title="'.JText::_("COM_AVC_BREADCRUMBMENU_FILTER").'" id="AVC_menu_input" />
<ul id="AVC_menu"></ul>
';

$JS_AVC_menu = '';

$JS_AVC_menu.= '


// ======================================================
// MENU - DECLARE VARS
// ======================================================

var newLI,newA,newIMG;

var AVC_menu_items = new Object();
AVC_menu_items[0] = new Object();
AVC_menu_items[0]["id"]=0;
AVC_menu_items[0]["name"]="' . JText::_("COM_AVC_HOME") . '";
AVC_menu_items[0]["image"]="'. JURI::root() . 'administrator/components/com_avc/assets/images/icon-64-avc.png";
AVC_menu_items[0]["layout"]="default";
';

foreach ($this->views as $key => $view) {

    // Create JS Array from PHP Menu List Array
    $JS_AVC_menu.= 'AVC_menu_items[' . ($key+1) . '] = new Object();' . "\n";
    $JS_AVC_menu.= 'AVC_menu_items[' . ($key+1) . ']["id"]="' . $view["id"] . '";' . "\n";
    $JS_AVC_menu.= 'AVC_menu_items[' . ($key+1) . ']["name"]="' . JText::_($view["title"]) . '";' . "\n";
    $JS_AVC_menu.= 'AVC_menu_items[' . ($key+1) . ']["layout"]="table";' . "\n";
    
    // Is image exists
    if (!empty ($view["icon_path"])) {
        $view_image = JURI::root() . $view["icon_path"];
    } else {
        $view_image = JURI::root() . 'administrator/components/com_avc/assets/images/icon-64-avc.png';
    }
    $JS_AVC_menu.= 'AVC_menu_items[' . ($key+1) . ']["image"]="' . $view_image . '";' . "\n";

}

$JS_AVC_menu.= '

// ======================================================
// MENU - DECLARE FUNCTIONS
// ======================================================

function AVC_menu_exec(curr_view_id,layout){

	$(\'curr_view_id\').value=curr_view_id;

	if($(\'filter_order\')){
		$(\'filter_order\').value=\'\';
	}
	if($(\'filter_order_Dir\')){
		$(\'filter_order_Dir\').value=\'\';
	}
	if($(\'filter_search_value\')){
		$(\'filter_search_value\').value=\'\';
	}
	if($(\'filter_order_Dir\')){
		$(\'filter_order_Dir\').value=\'\';
	}

	$(\'layout\').value=layout;

	$(\'adminForm\').submit();
}

function AVC_menu_render_item(index){

	newLI = document.createElement("li");
	newA = document.createElement("a");
	$("AVC_menu").appendChild(newLI)
					   .appendChild(newA)
					   .set("id","AVC_menu_"+index)
					   .appendText(AVC_menu_items[index]["name"])
					   .addEvents({ 
									"click": function(){ 
										AVC_menu_exec(AVC_menu_items[index]["id"],AVC_menu_items[index]["layout"]);
									}
								 }); 
					   ;

	// MARK CURRENT
	if(AVC_menu_items[index]["id"]=='.$this->curr_view_id.'){
		newIMG = document.createElement("img");
		newIMG.inject($("AVC_menu_"+index),"top")
			  .set("src",AVC_menu_items[index]["image"])
			  ;
	}

}

function AVC_menu_render_AVC_menu(){

	var tokens = Array();

	Object.each(AVC_menu_items, function (item, key){
		AVC_menu_render_item(key);
		tokens.push(item["name"]);
	});

	// MENU AUTOCOMPLETER
	new Autocompleter.Local("AVC_menu_input", tokens,
	{
		"minLength": 1, // We need at least 1 character
		"selectMode": "type-ahead", // Instant completion
		"overflow": true, // Overflow for more entries
		"multiple": false // Tag support, by default comma separated
	});

	$("AVC_menu_input").set("value","");

	$("AVC_menu_input").addEvent("keydown", function(event)
	{
		if(event.key=="enter")
		{
			for(index in AVC_menu_items)
			{
				if(this.get("value") == AVC_menu_items[index]["name"])
				{
					
					AVC_menu_exec(AVC_menu_items[index]["id"],AVC_menu_items[index]["layout"]);

				}
			}
		}
	});
}

';

$JS_AVC_menu.= '
// ======================================================
// MENU - INIT MENU
// ======================================================

window.addEvent("domready", function(){ 
	AVC_menu_render_AVC_menu();
});
'. "\n";

$this->doc->addScriptDeclaration($JS_AVC_menu);

$this->doc->addScript(JURI::root() . 'administrator/components/com_avc/assets/js/Observer.js');
$this->doc->addScript(JURI::root() . 'administrator/components/com_avc/assets/js/Autocompleter.js');
$this->doc->addScript(JURI::root() . 'administrator/components/com_avc/assets/js/Autocompleter.Local.js');