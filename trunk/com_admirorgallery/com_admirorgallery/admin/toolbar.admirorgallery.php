<?php

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

class TOOLBAR_admirorgallery{
	function _CONFIGURATION(){

	}
	function _VIEW($view)
	{
		switch ($view) {
			 case 'templates':
				JToolBarHelper::title( JText::_( 'Templates' ), 'templates' );
				JToolBarHelper :: custom( 'install', 'install','install', JText::_( 'Install' ), false, false );
				JToolBarHelper :: custom( 'remove', 'remove','remove', JText::_( 'Remove' ), false, false );
				JToolBarHelper :: custom( 'manual', 'manual','manual', JText::_( 'Help' ), false, false );
			 break;
			 case 'popups':
				JToolBarHelper::title( JText::_( 'Popups' ), 'popupsThemes' );
				JToolBarHelper :: custom( 'manual', 'manual','manual', JText::_( 'Help' ), false, false );
			 break;
			 case 'descriptions':
				JToolBarHelper::title( JText::_( 'Descriptions' ), 'descriptions.png' );
				JToolBarHelper :: custom( 'description', 'description','description', JText::_( 'Create' ), false, false );
				JToolBarHelper :: custom( 'manual', 'manual','manual', JText::_( 'Help' ), false, false );
			 break;
			default:
				JToolBarHelper::title( JText::_( 'Control Panel' ), 'default.png' );
				JToolBarHelper :: custom( 'manual', 'manual','manual', JText::_( 'Help' ), false, false );
		}
	}
}

?>
