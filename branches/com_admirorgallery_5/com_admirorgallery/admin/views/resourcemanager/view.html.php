<?php

/* ------------------------------------------------------------------------
  # com_admirorgallery - Admiror Gallery Component
  # ------------------------------------------------------------------------
  # author   Igor Kekeljevic & Nikola Vasiljevski
  # copyright Copyright (C) 2014 admiror-design-studio.com. All Rights Reserved.
  # @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
  # Websites: http://www.admiror-design-studio.com/joomla-extensions
  # Technical Support:  Forum - http://www.vasiljevski.com/forum/index.php
  # Version: 5.0.0
  ------------------------------------------------------------------------- */
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class AdmirorgalleryViewResourcemanager extends JViewLegacy {

    function display($tpl = null) {
        
        // Preloading joomla tools
        jimport('joomla.installer.helper');
        jimport('joomla.filesystem.file');
        jimport('joomla.filesystem.archive');
        jimport('joomla.html.pagination');
        jimport('joomla.filesystem.folder');
        JHTML::_('behavior.tooltip');
        
        $mainframe = JFactory::getApplication();
        $option = JRequest::getCmd('option');
        $AG_resourceType = JRequest::getVar('AG_resourceType'); // Current resource type
        
        JToolBarHelper::title(JText::_('COM_ADMIRORGALLERY_' . strtoupper($AG_resourceType)), $AG_resourceType);
        if (JFactory::getUser()->authorise('core.admin', 'com_admirorgallery')) {
            JToolbarHelper::custom('ag_install', 'ag_install', 'ag_install', 'JTOOLBAR_INSTALL', false, false);
            JToolbarHelper::deleteList('COM_ADMIRORGALLERY_ARE_YOU_SURE', 'ag_uninstall', 'JTOOLBAR_UNINSTALL');
        }
        
        // Loading JPagination vars
        $limitstart = $mainframe->getUserStateFromRequest($option . '.limitstart', 'limitstart', 0, 'int');
        $limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');

        // Read folder depending on $AG_resourceType
        $ag_resourceManager_installed = JFolder::folders(JPATH_SITE . '/plugins/content/admirorgallery/admirorgallery/' . $AG_resourceType); // N U
        sort($ag_resourceManager_installed);
        
        $this->assignRef('ag_resourceManager_installed',$ag_resourceManager_installed);
        $this->assignRef('limitstart',$limitstart);
        $this->assignRef('limit',$limit);
        $this->assignRef('AG_resourceType',$AG_resourceType);
        
        parent::display($tpl);
    }

}
