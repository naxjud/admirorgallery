<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
 
jimport( 'joomla.application.component.model' );

class AvcModelCollection extends JModel
{

	function checkJSON($itemName=null){
        
    if(version_compare(PHP_VERSION, '5.3.0') >= 0) { 
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
            break;
            case JSON_ERROR_DEPTH:
                JFactory::getApplication()->enqueueMessage('Maximum stack depth exceeded: '.$itemName, 'error');
            break;
            case JSON_ERROR_STATE_MISMATCH:
                JFactory::getApplication()->enqueueMessage('Underflow or the modes mismatch: '.$itemName, 'error');
            break;
            case JSON_ERROR_CTRL_CHAR:
                JFactory::getApplication()->enqueueMessage('Unexpected control character found: '.$itemName, 'error');
            break;
            case JSON_ERROR_SYNTAX:
                JFactory::getApplication()->enqueueMessage('Syntax error, malformed JSON: '.$itemName, 'error');
            break;
            case JSON_ERROR_UTF8:
                JFactory::getApplication()->enqueueMessage('Malformed UTF-8 characters, possibly incorrectly encoded: '.$itemName, 'error');
            break;
            default:
                JFactory::getApplication()->enqueueMessage(' Unknown error: '.$itemName, 'error');
            break;
        }
    }
       
    }

	function getCollection(){

		$mainframe = JFactory::getApplication();
        $AVC_STATE_COLLECTION = $mainframe->getUserStateFromRequest( "AVC_COLLECTION", "AVC_COLLECTION", $mainframe->getCfg("AVC_COLLECTION") );

		if(empty($AVC_STATE_COLLECTION)){

			$AVC_STATE_COLLECTION = '
			{
				"customer":{
					"name_and_surname":"",
					"street":"",
					"street_number":"",
					"zip":"",
					"city":"",
					"country":"",
					"phone":""
				},
				"additional_details":{
					"message":""
				}
			}
			';

		}

		$collection = json_decode($AVC_STATE_COLLECTION, true);
        $this->checkJSON("TEST_COLLECTION");

		return $collection;
	}

}
