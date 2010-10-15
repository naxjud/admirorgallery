<?php

// Admiror Gallery Component, version 1.0
// Authors: Vasiljevski & Kekeljevic, 2010.
 

defined( '_JEXEC' ) or die( 'Restricted access' );

require_once( JApplicationHelper::getPath( 'admin_html' ) ); 
require_once( JApplicationHelper::getPath( 'toolbar' ) );

$doc = &JFactory::getDocument();
$doc->addStyleSheet(JURI::base().'components/com_admirorgallery/css/template.css');

if(file_exists(JPATH_SITE.'/plugins/content/AdmirorGallery.xml')){

    // SET VALID FAMILY VERSION
    $ag_plugin_versionFamily_valid = 2;

    // LOAD ADMIRORGALLERY.XML FOR PLUGIN
    $ag_plugin_xml =& JFactory::getXMLParser( 'simple' );
    $ag_plugin_xml->loadFile( JPATH_SITE.'/plugins/content/AdmirorGallery.xml' );

    // GET VERSION FAMILY
    $ag_versionArray = explode(".", $ag_plugin_xml->document->version[0]->data());
    $ag_plugin_versionFamily = intval($ag_versionArray[0]);

    // CHECK FAMILY VERSION
    if($ag_plugin_versionFamily >= $ag_plugin_versionFamily_valid){
	SCREENS_admirorgallery::_VIEW($task);
    }else{
	echo JText::_( "Admiror Gallery Plugin older than version 2.0 are not supported by this component.");
    }

}else{

    echo JText::_( "Admiror Gallery Plugin not installed.");

}

TOOLBAR_admirorgallery::_VIEW($task);


?>
