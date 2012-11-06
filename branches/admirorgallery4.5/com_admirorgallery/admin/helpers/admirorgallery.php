<?php
// No direct access to this file
defined('_JEXEC') or die;

/**
 * AdmirorGallery component helper.
 */
abstract class AdmirorGalleryHelper
{
	/**
	 * Configure the Linkbar.
	 */
	public static function addSubmenu($submenu,$type)
	{
		JSubMenuHelper::addEntry(JText::_('COM_ADMIRORGALLERY_CONTROL_PANEL'),
		                         'index.php?option=com_admirorgallery&amp;controller=admirorgallery', $submenu == 'control_panel');
		JSubMenuHelper::addEntry(JText::_('COM_ADMIRORGALLERY_TEMPLATES'),
		                         'index.php?option=com_admirorgallery&amp;view=resourcemanager&amp;AG_resourceType=templates',
		                         $type == 'templates');
		JSubMenuHelper::addEntry(JText::_('COM_ADMIRORGALLERY_POPUPS'),
		                         'index.php?option=com_admirorgallery&amp;view=resourcemanager&amp;AG_resourceType=popups',
		                         $type == 'popups');
		JSubMenuHelper::addEntry(JText::_('COM_ADMIRORGALLERY_IMAGE_MANAGER'),
		                         'index.php?option=com_admirorgallery&amp;view=imagemanager',
		                         $submenu == 'imagemanager');
	}
}

?>
