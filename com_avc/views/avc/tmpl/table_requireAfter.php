<?php

// Author: Igor Kekeljevic, 2012.
// No direct access
defined('_JEXEC') or die('Restricted access');

$adminlist_interface = '
// DECLARE VARS
var keystroke,cb_lastClicked,myEvent,myDrag,ordering;

ordering = false;
';

foreach ($this->fieldsArray as $key => $value) {
	if($value == "ordering"){
		$adminlist_interface.= '
			ordering = true;
		';
	}
}

$adminlist_interface.= '
//////////////////////////////
// ADMIMLIST DECLARE FUNCTIONS
//////////////////////////////

var AVC_FIELDLIST = new Array('.'"'.implode('","', $this->fieldsArray).'"'.');

function adminlist_select(rowID){
	$(rowID).addClass("active");
	$(rowID).removeClass("hover");
	$(rowID).getElement(".cid").checked = true;
	cb_lastClicked=rowID;
	console.log(cb_lastClicked);
}

function adminlist_deselect(rowID){
	$(rowID).removeClass("active");
	$(rowID).removeClass("hover");
	$(rowID).getElement(".cid").checked = false;
}

function adminlist_deselect_all(){

	var list=$("adminlist");
    list.getElements(".cid").each(function(el, i)
    {
		el.checked = false;
	});
	list.getElements("tr").removeClass("active");

}


// DECLARE FUNCTIONS FOR DRAG AND DROP

function adminlist_mouseleave(){
	if(ordering==false){
		alert("' . JText::_("COM_AVC_CANNOT_ORDERING_NOTICE") . '");
		$(cb_lastClicked).removeEvent("mouseleave",adminlist_mouseleave);
		return;
	}

	// CHECK IS COLUMN SORTED BY ORDERING
	if($("filter_order").get("value")!="ordering" || $("filter_order_Dir").get("value")!="asc"){
		var filter_order_confirm = confirm("' . JText::_("COM_AVC_CHANGE_ORDERING_NOTICE") . '");
		if(filter_order_confirm){
			tableOrdering("ordering","asc","");
			return;
		}
	}else{		
		adminlist_drag_init();
	}
}

function adminlist_drag_create_target(index,target_pos){
	// Get the Dimensions of a row
	var row_position = $("row_"+index).getPosition();
	var row_dimensions = $("row_"+index).getDimensions();

	if(target_pos=="bef"){
		target_pos_y = row_position.y-(row_dimensions.height/2);
	}else{
		target_pos_y = row_position.y+(row_dimensions.height/2);		
	}

	var newDIV = document.createElement("div");
	document.body.appendChild(newDIV)
				 .set("class","adminlist_target")
				 .set("id",target_pos+"_"+$$("#row_"+index+" .cid").get("value"))
				 .setStyles({
				 	top: target_pos_y+"px",
				 	left: row_position.x+"px",
				 	width: row_dimensions.width+"px",
				 	height: row_dimensions.height+"px",
				 	"background-position": "-2000px center"
				 })
				 .addEvents({
					 "mouseover": function(event){
					 	dropTarget = this.get("id");
					 	this.setStyle("background-position","0 center");
					 },
					 "mouseleave": function(event){					 	
					 	dropTarget = "";
					 	this.setStyle("background-position","-2000px center");
					 }
				 });
}

function adminlist_drag_init(){	
	// Create targets
	adminlist_drag_create_target(0,"bef");
	$$("#adminlist tbody tr .cid").each(function(el,i){
		adminlist_drag_create_target(i,"aft");
	});
	// Create clone
	var clone = document.createElement("table");
	clone.set("id","adminlist_clone")
 		 .setStyles({
				"opacity": 0.9,
				"position": "absolute",
				"top": myEvent.page.y+"px",
			 	"left": myEvent.page.x+7+"px"
			})
		 .inject(document.body);
	$(cb_lastClicked).clone().set("id","myVideo").inject($("adminlist_clone"));
	myDrag = new Drag("adminlist_clone", {
	});
	myDrag.start(myEvent);
}

function adminlist_drag_end(){

	// REMOVE MOUSELEAVE LISTENER
	if($(cb_lastClicked)){
		$(cb_lastClicked).removeEvent("mouseleave",adminlist_mouseleave);
	}

	if(myDrag){
		myDrag.stop();
		document.getElements(".adminlist_target").each(function(item, index){
			item.destroy();
		});
		$$("#adminlist_clone").destroy();

		// CALCULATE POSITION AND TARGET	
		if(dropTarget==""){return;}
		var target = dropTarget.split("_");
		if(target[1]==$$("#"+cb_lastClicked+" .cid").get("value")){
			return;
		}else{
			$("ordering_id").value = $$("#"+cb_lastClicked+" .cid").get("value");
			$("ordering_pos").value = target[0];
			$("ordering_targetId").value = target[1];
			$("task").value = "ordering";
			$("adminForm").submit();
		}
	}
}

//////////////////////////////
// ADMIMLIST DECLARE DOM READY
//////////////////////////////

window.addEvent("domready", function(){

	var JTooltips = new Tips($$(\'.hasTip\'),{
		maxTitleChars: 50,
		fixed: false
	}); 

	// Trancate long text
	$$(".avc_fld_types_table_default").each(function(el, i){
		el.set( "text", el.get("text").truncate(200, "...", " ") );
	});

	$$("#adminlist tbody tr").setStyles({
			    "cursor": "pointer"
	});

	// STANDARD ROW EVENTS	
	if($("tmpl")){

		$("adminlist").getElement("tbody").getElements("tr").addEvents({
			"mousedown": function(e){
				e.preventDefault();
				window.parent.jInsertRelSelect($("target_field").value, this.getElement(".cid").value);  
				window.parent.SqueezeBox.close();
	    	},
			mouseover: function(){
			    $(this).addClass("hover");
			},
			mouseleave: function(){
			    $(this).removeClass("hover");
			}
		});

	}else{

		$$("#adminlist img").set("draggable","false");
		$$("#adminlist a").set("draggable","false");
	    

		// KEYSTROKE LISTENER
		document.addEvents({
			"keydown": function(event){
				keystroke=event.key;
				if (event.key == \'n\' && event.control && event.alt){
					Joomla.submitbutton(\'add\');
				}
				if (event.key == \'d\' && event.control && event.alt){
					Joomla.submitbutton(\'duplicate\');
				}
				if (event.key == \'r\' && event.control && event.alt){
					Joomla.submitbutton(\'refresh\');
				}
			},
			"keyup": function(event){		
				keystroke="";
			},
			"mouseup": function(event){	
				adminlist_drag_end();
			}
		});

		////////////////////////////////////////////////////////////////////////////////////////////////////// ADD EVENTS FOR TR
		//
		$("adminlist").getElement("tbody").getElements("tr").addEvents({
			"mousedown": function(e){

				e.preventDefault();

				switch(keystroke){
					case "shift":	
						if(cb_lastClicked){						
							var from = parseInt(cb_lastClicked.substring(4, cb_lastClicked.length));
							var to = parseInt(this.id.substring(4, this.id.length));
							while (from!=to) {
								adminlist_select("row_"+from);
								if(from>to){
									from--;
								}else{
									from++;
								}
							}	
							adminlist_select("row_"+from);					
						}
					break;
					case "control":
						if($(this).getElement(".cid").get("checked")){
							adminlist_deselect(this.id);
						}else{
							adminlist_select(this.id);
						}
					break;
					default:
						adminlist_deselect_all();
						adminlist_select(this.id);	
						myEvent = e;
						$(this).addEvent("mouseleave",adminlist_mouseleave);				
				}
				
			},
			"dblclick": function(){  
				$("task").value = "edit";
				document.adminForm.submit();
	    	},
	    	"contextmenu": function(e){
				e.stop();
			},
			mouseover: function(){
			    $(this).addClass("hover");
			},
			mouseleave: function(){
			    $(this).removeClass("hover");
			}
		});
	}

});

';

$this->doc->addScriptDeclaration($adminlist_interface);
