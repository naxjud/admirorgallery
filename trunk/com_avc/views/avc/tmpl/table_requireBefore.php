<?php

// Author: Igor Kekeljevic, 2012.
// No direct access
defined('_JEXEC') or die('Restricted access');

$AVC_REQUIRE_BEFORE = '
//////////////////////////////
// ADMIMLIST DECLARE FUNCTIONS
//////////////////////////////

function unique(arr){
	var o = {}, i, l = arr.length, r = [];
    for(i=0; i<l;i+=1) o[arr[i]] = arr[i];
    for(i in o) r.push(o[i]);
    return r;
}

var AVC_RELS = {};
function AVC_SEARCH_UPDATE(searchValue){

    var having = "";

    if(searchValue){

		var AVC_RELS_filtered = {};
		Object.each(AVC_RELS, function(ROW_VALUE, ROW_KEY){
			Object.each(ROW_VALUE, function(ROW_FIELD_VALUE, ROW_FIELD_KEY){
				console.log( ROW_FIELD_VALUE["value"].toLowerCase().indexOf(searchValue.toLowerCase()) );
				if( ROW_FIELD_VALUE["value"].toLowerCase().indexOf(searchValue.toLowerCase()) >= 0 ){
					if(AVC_RELS_filtered[ROW_KEY] == undefined){
						AVC_RELS_filtered[ROW_KEY] = {};
					}
					AVC_RELS_filtered[ROW_KEY][ROW_FIELD_KEY] = ROW_FIELD_VALUE["key"];
				}
			});
		});

		var havingArray = new Array();
	    for (var i=0; i < AVC_FIELDLIST.length; i++) { 
	        havingArray.push(AVC_FIELDLIST[i]+\' LIKE \\\'%\'+searchValue+\'%\\\'\');
	    }


	    Object.each(AVC_RELS_filtered, function(ROW_VALUE, ROW_KEY){
	    	Object.each(ROW_VALUE, function(ROW_FIELD_VALUE, ROW_FIELD_KEY){
	    		havingArray.push(ROW_FIELD_KEY+\' LIKE \'+ROW_FIELD_VALUE);
	    	});
	    });


		havingArray = unique(havingArray);

	    having = havingArray.join(\' OR \');

    }   

    document.id(\'filter_search_value\').value = having;

}
';

$this->doc->addScriptDeclaration($AVC_REQUIRE_BEFORE);


// var AVC_RELS_filtered = Object.filter(AVC_RELS, function(value, key){
//     return value.search(/+searchValue+/i) > 0;
// });