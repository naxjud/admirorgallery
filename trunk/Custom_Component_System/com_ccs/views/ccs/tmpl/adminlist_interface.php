<?php
// Author: Igor Kekeljevic, 2012.


// No direct access
defined('_JEXEC') or die('Restricted access');

$adminlist_interface='
var rows, cbs, rowID, cbID, keystroke, newDIV, dropTarget, myDrag, myEvent, isDragging;

function rowEdit(){
	document.id("task").value = "edit";
	document.adminForm.submit();
}

function rowSort(){
	document.id("adminListSorting").value = dropTarget;
	document.id("task").value = "adminListSorting";
	document.adminForm.submit();
}

function adminlist_select(){
	document.id(cbID).checked = true;
	document.id(rowID).addClass("active");
}

function adminlist_deselect(){
	document.id(cbID).checked = false;
	document.id(rowID).removeClass("active");
}

function adminlist_deselect_all(){
	for(var i=0; i<cbs.length; i++) {
		document.id("cb"+i).checked = false;
		document.id("row"+i).removeClass("active");
	}
}

function adminlist_drag_create_target(index,target_pos){
	// Get the Dimensions of a row
	var row_position = document.id("row"+index).getPosition();
	var row_dimensions = document.id("row"+index).getDimensions();

	if(target_pos=="bef"){
		target_pos_y = row_position.y-(row_dimensions.height/2);
	}else{
		target_pos_y = row_position.y+(row_dimensions.height/2);		
	}

	newDIV = document.createElement("div");
	document.body.appendChild(newDIV)
				 .set("class","adminlist_target")
				 .set("id",target_pos+document.id("cb"+index).get("value"))
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
	adminlist_disable_select();
	isDragging = true;
	// Create targets
	adminlist_drag_create_target(0,"bef");
	for(var i=0; i<cbs.length; i++) {
		adminlist_drag_create_target(i,"aft");
	}
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
	document.id(rowID).clone().inject(document.id("adminlist_clone"));
	myDrag = new Drag("adminlist_clone", {
	});
	myDrag.start(myEvent);
}

function adminlist_drag_end(){
	isDragging = false;
	myDrag.stop();
	document.getElements(".adminlist_target").each(function(item, index){
		item.destroy();
	});
	$$("#adminlist_clone").destroy();
	if(dropTarget==""){return;}
	if($("filter_order").get("value")!="ordering" || $("filter_order_Dir").get("value")!="asc"){
		var filter_order_confirm = confirm("'.JText::_(COM_CCS_CHANGE_ORDERING_NOTICE).'");
		if(filter_order_confirm){
			Joomla.tableOrdering(\'ordering\',\'asc\',\'\');
			return;
		}
	}
	rowSort();
}

function adminlist_disable_select(){
	document.body.setStyles({
	    "-webkit-user-select": "none",
	    "-khtml-user-select": "none",
	    "-moz-user-select": "none",
	    "-o-user-select": "none",
	    "user-select": "none" 
    });
}

function adminlist_enable_select(){
	document.body.setStyles({
	    "-webkit-user-select": "text",
	    "-khtml-user-select": "text",
	    "-moz-user-select": "text",
	    "-o-user-select": "text",
	    "user-select": "text" 
    });
}

function adminlist_mouseleave(){
	adminlist_drag_init();	
}

window.addEvent("domready", function(){

	cbs = document.getElementsByName("cid[]");
	rows = document.id("adminlist").getElements("tr");
	isDragging = false;

	// Detect keystroke, left click, right click and double click
	document.addEvents({
		"keydown": function(event){
			keystroke=event.key;
		},
		"keyup": function(event){		
			keystroke="";
		},
		"mouseup": function(event){	
			document.id(rowID).removeEvent("mouseleave",adminlist_mouseleave);		
			if(isDragging){
				adminlist_drag_end();
			}
			adminlist_enable_select();
		}
	});

	document.id("adminlist").getElement("tbody").getElements("tr").addEvents({
		"mousedown": function(e){

			prev_rowID = rowID;
			prev_cbID = cbID;
			rowID = this.get("id");
			cbID = "cb"+rowID.substring(3, rowID.length);		
			console.log("Left Click on: "+rowID);
			
			switch(keystroke){
				case "shift":					
					adminlist_disable_select();
					if(prev_rowID){						
						var from = parseInt(prev_rowID.substring(3, prev_rowID.length));
						var to = parseInt(rowID.substring(3, rowID.length));
						while (from!=to) {
							rowID = "row"+from;
							cbID = "cb"+from;
							adminlist_select();
							if(from>to){
								from--;
							}else{
								from++;
							}
						}	
						rowID = "row"+from;
						cbID = "cb"+from;
						adminlist_select();					
					}
				break;
				case "control":
					console.log("control is used");
					if(document.id(cbID).get("checked")){
						adminlist_deselect();
					}else{
						adminlist_select();
					}
				break;
				default:
					adminlist_deselect_all();
					adminlist_select();	
			}

			myEvent = e;
			document.id(rowID).addEvent("mouseleave",adminlist_mouseleave);
		},
		"dblclick": function(){
        	rowEdit();
    	},
    	"contextmenu": function(e){
			e.stop();
		}
	});

});
';

$this->doc->addScriptDeclaration($adminlist_interface);
