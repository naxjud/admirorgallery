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

function AVC_SEARCH_UPDATE(searchValue){

    var having = "";

    if(searchValue){

		var havingArray = new Array();

	    for (var i=0; i < AVC_FIELDLIST.length; i++) { 
	        havingArray.push(AVC_FIELDLIST[i]+\' LIKE \\\'%\'+searchValue+\'%\\\'\');
	    }

		havingArray = unique(havingArray);

	    having = havingArray.join(\' OR \');

    }   

    document.id(\'filter_search_value\').value = having;

}
';

$this->doc->addScriptDeclaration($AVC_REQUIRE_BEFORE);
