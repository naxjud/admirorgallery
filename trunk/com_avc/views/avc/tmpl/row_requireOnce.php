<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.calendar'); // Callendar Libraries




$JS_FIELD_FILELIST = '

///////////////////////////////////////////
// FIELD FILELIST
///////////////////////////////////////////

function avc_filelist_update(FIELD_NAME, FIELD_VALUE_PREFIX, FIELD_VALUE){

	if(FIELD_VALUE){	
		$(FIELD_NAME).set("value", FIELD_VALUE_PREFIX+FIELD_VALUE);
	}else{
		$(FIELD_NAME).set("value", "");
	}

}

';
$this->doc->addScriptDeclaration($JS_FIELD_FILELIST);



$JS_FIELD_JSON = '

///////////////////////////////////////////
// FIELD JSON
///////////////////////////////////////////

function avc_json_update(FIELD_NAME){
	
	var field_labels = $(FIELD_NAME+"_frame").getElements("select");
	var field_values = $(FIELD_NAME+"_frame").getElements("input");
	var output = new Array();

	field_labels.each(function(el,i){
		output.push(\'"\'+field_labels[i].get("value")+\'":"\'+field_values[i].get("value")+\'"\');
	});


	$(FIELD_NAME).set("value","{"+output.join()+"}");

}

function avc_json_add(FIELD_NAME){

	$(FIELD_NAME+"_clone").clone()
						  .inject($(FIELD_NAME+"_frame"))
						  .setStyles({
								"position":"relative",
								"left":"0"
							});

}

function avc_json_remove(item,FIELD_NAME){

	$(item).getParent("div").destroy();
	avc_json_update(FIELD_NAME);

}


';
$this->doc->addScriptDeclaration($JS_FIELD_JSON);


$JS_FIELD_AVC_VARLIMIT = '

///////////////////////////////////////////
// FIELD VARLIMIT
///////////////////////////////////////////

// AVC_VARLIMIT
var AVC_VARLIMIT=new Array();

// varcharLimited
function varcharLimited_validator(event,item_alias, item_id, item_value){
	if(event.keyCode==9){// Ignore TAB
		return;
	}
	if(event.keyCode==16){// Ignore SHIFT
		return;
	}

	// Update value for post
	AVC_VARLIMIT[item_alias][item_id]=item_value;
	$(item_alias).value=AVC_VARLIMIT[item_alias].join("");

	// Update focus field
	var current_value = $(item_alias+"_"+item_id).get("value");
	current_length = current_value.length;
	if(current_length>0){
		if ($(item_alias+"_"+(item_id+1))) {
			$(item_alias+"_"+(item_id+1)).selectRange(0,1).focus();
		}
	}else{
		if ($(item_alias+"_"+(item_id-1))) {
			$(item_alias+"_"+(item_id-1)).selectRange(0,1).focus();
		}
	}

}
';
$this->doc->addScriptDeclaration($JS_FIELD_AVC_VARLIMIT);



$JS_FIELD_IMG = '

///////////////////////////////////////////
// FIELD_IMG_ID
///////////////////////////////////////////

var FIELD_IMG_ID;

function jInsertFieldValue(url_value){
    if(url_value!=\'\'){
        $(\'img_\'+FIELD_IMG_ID).set("src","' . JURI::root() . '"+url_value);
    	$(FIELD_IMG_ID).value=url_value; 
    }else{
        $(\'img_\'+FIELD_IMG_ID).set("src","' . JURI::root() . 'administrator/components/com_avc/assets/images/no-image.png");
    	$(FIELD_IMG_ID).value = \'\'; 
    }
}

';
$this->doc->addScriptDeclaration($JS_FIELD_IMG);


$JS_FIELD_REL = '

///////////////////////////////////////////
// FIELD_REL
///////////////////////////////////////////
var AVC_REL = new Object();
function jInsertRelSelect(rel_name,rel_value){
    $(rel_name).value=rel_value;
    relOnChange(rel_name,rel_value);
}

function relOnChange(rel_name,rel_value){
	if(AVC_REL[rel_name][rel_value]){		
    	$(\'lbl_\'+rel_name).set(\'text\',AVC_REL[rel_name][rel_value]); 
	}
}

//////////////////////////////
// DECLARE DOM READY
//////////////////////////////

window.addEvent("domready", function(){

	$$("input.avc_rel").each(function(el,i){
		var rel_name = el.get(\'name\');
		var rel_value = el.get(\'value\');
		relOnChange(rel_name,rel_value);
	});

});


';

$this->doc->addScriptDeclaration($JS_FIELD_REL);






$JS_FIELD_MASONRY= '

///////////////////////////////////////////
// FIELD_MASONRY
///////////////////////////////////////////

//////////////////////////////
// DECLARE DOM READY
//////////////////////////////

window.addEvent("domready", function(){

$("masonry").masonry({
		singleMode: true,
		itemSelector: ".form_items"
	});

});


';

$this->doc->addScriptDeclaration($JS_FIELD_MASONRY);
