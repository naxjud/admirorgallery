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
			 break;
			 case 'popups':
				JToolBarHelper::title( JText::_( 'Popups' ), 'popupsThemes' );
				JToolBarHelper :: custom( 'install', 'install','install', JText::_( 'Install' ), false, false );
				JToolBarHelper :: custom( 'remove', 'remove','remove', JText::_( 'Remove' ), false, false );
			 break;
			 case 'image-manager':
				JToolBarHelper::title( JText::_( 'Image manager' ), 'image-manager.png' );
				JToolBarHelper :: custom( 'bookmarkAdd', 'bookmarkAdd','bookmarkAdd', JText::_( 'Add Gallery' ), false, false );
				JToolBarHelper :: custom( 'bookmarkRemove', 'bookmarkRemove','bookmarkRemove', JText::_( 'Remove Gallery' ), false, false );
				JToolBarHelper :: divider();
				JToolBarHelper :: custom( 'folderNew', 'folderNew','folderNew', JText::_( 'New Folder' ), false, false );
				JToolBarHelper :: custom( 'upload', 'upload','upload', JText::_( 'Upload' ), false, false );
				JToolBarHelper :: custom( 'rename', 'rename','rename', JText::_( 'Rename' ), false, false );
				JToolBarHelper :: custom( 'remove', 'remove','remove', JText::_( 'Delete' ), false, false );
				JToolBarHelper :: custom( 'priority', 'priority','priority', JText::_( 'Set Priority' ), false, false );
				JToolBarHelper :: divider();
				JToolBarHelper :: custom( 'description', 'description','description', JText::_( 'Write Captions' ), false, false );
			 break;
			default:
				JToolBarHelper::title( JText::_( 'Control Panel' ), 'default.png' );
// 				JToolBarHelper :: custom( 'manual', 'manual','manual', JText::_( 'Help' ), false, false );
		}
	}
}

?>
