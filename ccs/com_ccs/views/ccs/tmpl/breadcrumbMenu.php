<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

$JS_breadcrumbMenu_script = '
// Declare global vars
var breadcrumbMenu_wrap,newInput,newDIV,newSPAN,newUL,newLI,newH1,newIMG,newA,newBR,newTABLE,newTR,newTD,cache_array,cache_boolean,cache_str,cache_int,cache_obj,newSPAN;

var currChildren = Array();
var currParents = Array();

function breadcrumbMenu_render_item(wrapperID,menuItemID,hasChildren,isParent)// wrapper id(string), menu item id(string), has children(boolean)
{

		newLI = document.createElement("li");
		$(wrapperID).appendChild(newLI).set("id", "breadcrumbMenu_linkRow_"+menuItemID);

		if(isParent){
			$("breadcrumbMenu_linkRow_"+menuItemID).set("class","breadcrumbMenu_linkRow_parent");
		}else{
			$("breadcrumbMenu_linkRow_"+menuItemID).set("class","breadcrumbMenu_linkRow_child");
		}

		// Prepare table wrapping structure
		var timer;
		newTABLE = document.createElement("table");
		newTR = document.createElement("tr");
		newTD = document.createElement("td");
		newA = document.createElement("a");
		$("breadcrumbMenu_linkRow_"+menuItemID).appendChild(newTABLE)
														 .appendChild(newTR)
														 .appendChild(newTD)
														 .appendChild(newA)
														 .set("href","#")
														 .set("id","breadcrumbMenu_link_"+menuItemID)
														 .addEvents({ 
															"click": function(){ 
																$(\'layout\').value=breadcrumbMenu_items[menuItemID]["layout"];
																$(\'filter_order\').value=\'\';
																$(\'filter_order_Dir\').value=\'\';
																$(\'filter_search_value\').value=\'\';
																$(\'filter_order_Dir\').value=\'\';
																$(\'alias\').value=breadcrumbMenu_items[menuItemID]["alias"];
																$(\'adminForm\').submit();
															}
														 });  

		if(menuItemID == breadcrumbMenu_items_activeItem){
			$("breadcrumbMenu_link_"+menuItemID).set("class", "breadcrumbMenu_link_active");
			if(breadcrumbMenu_items[menuItemID]["image"]!=""){				
				newIMG = document.createElement("img");
				$("breadcrumbMenu_link_"+menuItemID).appendChild(newIMG)
															  .set("src","' . JURI::root() . '"+breadcrumbMenu_items[menuItemID]["image"])
															  .set("width","64");
			}
		}

		$("breadcrumbMenu_link_"+menuItemID).appendText(breadcrumbMenu_items[menuItemID]["name"]);
				
		if(hasChildren){
   		    newTD = document.createElement("td");
			newA = document.createElement("a");
			$("breadcrumbMenu_linkRow_"+menuItemID).getElements("tr")[0].appendChild(newTD)
																			   .set("class","breadcrumbMenu_link_arrowWrap")
																			   .appendChild(newA)
																			   .set("id","breadcrumbMenu_link_arrowWrap_"+menuItemID)
																			   .set("href","#")
																			   .set("onClick", "$(\'breadcrumbmenuState\').value=\'"+menuItemID+"\'; breadcrumbMenu_render(\'"+menuItemID+"\',true);return false;")
																			   .appendText("◄");
			cache_obj = $("breadcrumbMenu_link_"+menuItemID).getSize();
			$("breadcrumbMenu_link_arrowWrap_"+menuItemID).setStyle("line-height",cache_obj.y+"px");
		}




}

function breadcrumbMenu_getChildren(currItem){
	var currChildren = Array();
	for(var i in breadcrumbMenu_items)
	{
	    if(breadcrumbMenu_items[i]["parent_alias"] == breadcrumbMenu_items[currItem]["alias"])
	    {
		    currChildren.push(i);
	    }
	}
	return currChildren;	
}

function breadcrumbMenu_getParents(currItem)
{
	var currParents = Array();
	var cache_str=currItem;
	var cache_boolean=true;
	while (cache_boolean)
	{
		cache_boolean=false;
		for(i in breadcrumbMenu_items)
		{
			if(breadcrumbMenu_items[cache_str]["parent_alias"] == breadcrumbMenu_items[i]["alias"])// Search parent_id
			{
				cache_boolean=true;
				cache_str=i;
				currParents.push(cache_str);
			}
		}
	}
	currParents.reverse();// Reverse order
	return currParents;
}

function breadcrumbMenu_render(currItem,useAnim)
{

	var i=0;
	currParents = breadcrumbMenu_getParents(currItem);
	currChildren = breadcrumbMenu_getChildren(currItem);

	// Prepare
	$("breadcrumbMenu_input").set("value","");
	$("breadcrumbMenu_input").setAttribute("tabIndex", 0);
	$("breadcrumbMenu_input").focus();
	breadcrumbMenu_wrap = $("breadcrumbMenu");
	breadcrumbMenu_wrap.empty();

	// -------------------------------------------------------------------------------- Render Parents
	for(i=0; i<currParents.length; i++)
	{
		cache_str=currParents[i];	
		breadcrumbMenu_render_item("breadcrumbMenu",currParents[i],true,true);
	}

	// -------------------------------------------------------------------------------- Render Current
	cache_boolean=true;
	cache_array=breadcrumbMenu_getChildren(currItem);
	if(cache_array.length==0)// Children not found
	{
		cache_boolean=false;
	}
	breadcrumbMenu_render_item("breadcrumbMenu",currItem,cache_boolean,cache_boolean);

	// -------------------------------------------------------------------------------- Render Children
	for(i=0; i<currChildren.length; i++)
	{
		cache_boolean=true;
		cache_array=breadcrumbMenu_getChildren(currChildren[i]);
		if(cache_array.length==0)// Children not found
		{
			cache_boolean=false;
		}
		breadcrumbMenu_render_item("breadcrumbMenu",currChildren[i],cache_boolean,false);
	}

	// Slide In Menu Animation for navigation
	if(useAnim)
	{
		var myFx = new Fx.Slide(breadcrumbMenu_wrap, {
			duration: 440,
			transition: Fx.Transitions.Back.easeOut
		});
		myFx.hide().slideIn();
	}

	css_addButtonStyles( $$("#breadcrumbMenu a") );

	$$("td.breadcrumbMenu_link_arrowWrap a").each(function(item){
		item.setStyles({
			"line-height": item.getParent().getParent().getElement("td:nth-child(first) a").getComputedSize().height+"px",
			"padding": "0px"
		});
	});
	
}

function breadcrumbMenu_getParentID(itemID){
	var parentItem=breadcrumbMenu_items[itemID]["parent_alias"];
	var parentID="";
	for(var ii in breadcrumbMenu_items)
	{
		if(breadcrumbMenu_items[ii]["alias"]==parentItem){
			parentID=ii;
		}
	}
	if(parentID==""){
		parentID=breadcrumbMenu_items_default;
	}
	return parentID;
}

function breadcrumbMenu_filter(){
	var tokens = Array();
	for(i in breadcrumbMenu_items)
	{
		tokens.push(breadcrumbMenu_items[i]["name"]);
	}

	// Our instance for the element with id "breadcrumbMenu_input"
	new Autocompleter.Local("breadcrumbMenu_input", tokens,
	{
		"minLength": 1, // We need at least 1 character
		"selectMode": "type-ahead", // Instant completion
		"overflow": true, // Overflow for more entries
		"multiple": false // Tag support, by default comma separated
	});

	$("breadcrumbMenu_input").addEvent("keydown", function(event)
	{
		if(event.key=="enter")
		{
			for(i in breadcrumbMenu_items)
			{
				if(this.get("value") == breadcrumbMenu_items[i]["name"])
				{
					
					parentID = breadcrumbMenu_getParentID(i);
					$(\'breadcrumbmenuState\').value=parentID;
					$(\'layout\').value=breadcrumbMenu_items[i][\'layout\'];
					$(\'filter_order\').value=\'\';
					$(\'filter_order_Dir\').value=\'\';
					$(\'alias\').value=breadcrumbMenu_items[i][\'alias\'];
					$(\'adminForm\').submit();

				}
			}
		}
	});
}

window.addEvent("domready", function(){ 

	breadcrumbMenu_filter();
	var currItem = $("breadcrumbmenuState").value;
	if(breadcrumbMenu_items_valid_ids.indexOf(currItem)==-1){
		currItem=breadcrumbMenu_items_default ;
	}
	breadcrumbMenu_render(currItem,false);
	
});

';

$this->doc->addScript(JURI::root() . 'administrator/components/com_ccs/assets/js/Observer.js');
$this->doc->addScript(JURI::root() . 'administrator/components/com_ccs/assets/js/Autocompleter.js');
$this->doc->addScript(JURI::root() . 'administrator/components/com_ccs/assets/js/Autocompleter.Local.js');
$this->doc->addScriptDeclaration($JS_breadcrumbMenu_script);

$JS_breadcrumbMenu_items = 'var breadcrumbMenu_items_default="COM_CCS_HOME";' . "\n";
$JS_breadcrumbMenu_items.= 'var breadcrumbMenu_items_valid_ids = new Array();' . "\n";
$JS_breadcrumbMenu_items.= 'var breadcrumbMenu_items = new Object();' . "\n";
$JS_breadcrumbMenu_items.= 'breadcrumbMenu_items["COM_CCS_HOME"] = new Object();' . "\n";
$JS_breadcrumbMenu_items.= 'breadcrumbMenu_items["COM_CCS_HOME"]["alias"]="COM_CCS_HOME";' . "\n";
$JS_breadcrumbMenu_items.= 'breadcrumbMenu_items["COM_CCS_HOME"]["name"]="' . JText::_("COM_CCS_HOME") . '";' . "\n";
$JS_breadcrumbMenu_items.= 'breadcrumbMenu_items["COM_CCS_HOME"]["image"]="";' . "\n";
$JS_breadcrumbMenu_items.= 'breadcrumbMenu_items["COM_CCS_HOME"]["parent_alias"]="";' . "\n";
$JS_breadcrumbMenu_items.= 'breadcrumbMenu_items["COM_CCS_HOME"]["layout"]="default";' . "\n";

$JS_breadcrumbMenu_items.= 'var breadcrumbMenu_items_activeItem="COM_CCS_HOME";' . "\n";

foreach ($this->menuItems as $menuItem) {
    if ($this->alias == $menuItem["db_alias"]) {// Set Active Item
        $JS_breadcrumbMenu_items.= 'breadcrumbMenu_items_activeItem="' . $menuItem["id"] . '";' . "\n";
    }
    $JS_breadcrumbMenu_items.= 'breadcrumbMenu_items_valid_ids.push("' . $menuItem["id"] . '");' . "\n";

    // Create JS Array from PHP Menu List Array
    $JS_breadcrumbMenu_items.= 'breadcrumbMenu_items["' . $menuItem["id"] . '"] = new Object();' . "\n";
    $JS_breadcrumbMenu_items.= 'breadcrumbMenu_items["' . $menuItem["id"] . '"]["alias"]="' . $menuItem["db_alias"] . '";' . "\n";
    $JS_breadcrumbMenu_items.= 'breadcrumbMenu_items["' . $menuItem["id"] . '"]["name"]="' . JText::_($menuItem["db_alias"]) . '";' . "\n";
    if (!empty($menuItem["db_image"])) {
        $JS_breadcrumbMenu_items.= 'breadcrumbMenu_items["' . $menuItem["id"] . '"]["image"]="' . $menuItem["db_image"] . '";' . "\n";
    } else {
        $JS_breadcrumbMenu_items.= 'breadcrumbMenu_items["' . $menuItem["id"] . '"]["image"]="";' . "\n";
    }
    $parent_alias = $menuItem["parent_db_alias"];
    if (empty($parent_alias)) {
        $parent_alias = "COM_CCS_HOME";
    }
    $JS_breadcrumbMenu_items.= 'breadcrumbMenu_items["' . $menuItem["id"] . '"]["parent_alias"]="' . $parent_alias . '";' . "\n";
    $JS_breadcrumbMenu_items.= 'breadcrumbMenu_items["' . $menuItem["id"] . '"]["layout"]="table";' . "\n";
}
$this->doc->addScriptDeclaration($JS_breadcrumbMenu_items);