<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

// ADD WRAPPER
echo '
<ul id="AVC_menu"></ul>
';

$JS_AVC_menu = '';

$JS_AVC_menu.= '


// ======================================================
// MENU - DECLARE VARS
// ======================================================

var newH1,newUL,newLI,newA,newIMG, AVC_menu_groups;

var AVC_menu_items = new Object();
AVC_menu_items[0] = new Object();
AVC_menu_items[0]["id"]=0;
AVC_menu_items[0]["name"]="' . JText::_("COM_AVC_HOME") . '";
AVC_menu_items[0]["image"]="'. JURI::root() . 'administrator/components/com_avc/assets/images/icon-64-avc.png";
AVC_menu_items[0]["layout"]="default";
AVC_menu_items[0]["group_alias"]="";
';

$counter = 1;
foreach ($this->views as $key => $view) {

    // Create JS Array from PHP Menu List Array
    $JS_AVC_menu.= 'AVC_menu_items['.$counter.'] = new Object();' . "\n";
    $JS_AVC_menu.= 'AVC_menu_items['.$counter.']["id"]=' . $key . ';' . "\n";
    $JS_AVC_menu.= 'AVC_menu_items['.$counter.']["name"]="' . JText::_($view["name"]) . '";' . "\n";
    $JS_AVC_menu.= 'AVC_menu_items['.$counter.']["layout"]="table";' . "\n";
    $JS_AVC_menu.= 'AVC_menu_items['.$counter.']["group_alias"]="'. JText::_($view["group_alias"]) .'";' . "\n";
    
    // Is image exists
    if (!empty ($view["icon_path"])) {
        $view_image = JURI::root() . $view["icon_path"];
    } else {
        $view_image = JURI::root() . 'administrator/components/com_avc/assets/images/icon-64-avc.png';
    }
    $JS_AVC_menu.= 'AVC_menu_items['.$counter.']["image"]="' . $view_image . '";' . "\n";

    $counter++;

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

function AVC_menu_render_item(index, parent){

	newLI = document.createElement("li");
	newA = document.createElement("a");
	$(parent).appendChild(newLI)
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
	if(AVC_menu_items[index]["id"]=="'.$this->curr_view_id.'"){
		newIMG = document.createElement("img");
		newIMG.inject($("AVC_menu_"+index),"top")
			  .set("src",AVC_menu_items[index]["image"])
			  ;
	  	$("AVC_menu_"+index).addClass("AVC_menu_current");
	}

}

';

$JS_AVC_menu.= '
// ======================================================
// MENU - INIT MENU
// ======================================================
AVC_menu_groups = new Array();
window.addEvent("domready", function(){ 
	Object.each(AVC_menu_items, function (item, key){
		// AVC_menu_render_item(key);
		AVC_menu_groups.push( item["group_alias"] );
	});

	AVC_menu_groups = AVC_menu_groups.unique();

	Object.each(AVC_menu_groups, function (groupitem, groupkey){

		newLI = document.createElement("li");
		newH1 = document.createElement("h1");
		newUL = document.createElement("ul");
		var title = groupitem;
		if(title == ""){title = "' . JText::_("COM_AVC_UNCATEGORISED") . '"};
		$("AVC_menu").appendChild(newLI)
					 .set("class","AVC_menu_group_wrap")
					 .set("id","AVC_menu_group_wrap_"+groupkey)
					 .appendChild(newH1)
					 .appendText(title);

		$("AVC_menu_group_wrap_"+groupkey).appendChild(newUL)
										   .set("id","AVC_menu_group_"+groupkey);

		Object.each(AVC_menu_items, function (menuitem, menukey){
			if(menuitem["group_alias"] == groupitem){
				AVC_menu_render_item(menukey,"AVC_menu_group_"+groupkey);
			}
		});

	});

});
'. "\n";

$this->doc->addScriptDeclaration($JS_AVC_menu);

$this->doc->addScript(JURI::root() . 'administrator/components/com_avc/assets/js/Observer.js');
$this->doc->addScript(JURI::root() . 'administrator/components/com_avc/assets/js/Autocompleter.js');
$this->doc->addScript(JURI::root() . 'administrator/components/com_avc/assets/js/Autocompleter.Local.js');