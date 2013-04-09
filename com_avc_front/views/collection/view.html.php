<?php

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.view');

class AvcViewCollection extends JView
{

	protected $doc;
	protected $collection;

    function display($tpl = null)
    {

		$this->doc = JFactory::getDocument();
    	$this->collection = $this->get('Collection');

        parent::display($tpl);

        // Add after default head scirpt & styles loaded
        $this->doc->addStyleSheet( JURI::root() . '/modules/mod_avc/templates/template.css' );
        $this->doc->addStyleSheet( JURI::root() . '/components/com_avc/assets/template.css' ); 

    }
}