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
defined('_JEXEC') or die('Restricted access');
//Check if plugin is installed, othervise don't show view
if (!is_dir(JPATH_SITE . '/plugins/content/admirorgallery/')) {
    return;
}
?>

<div class="row-fluid">
    <div class="span2">
        <div class="well well-small">
            <div class="module-title nav-header"><?php echo JText::_('COM_ADMIRORGALLERY_MENU'); ?></div>
            <?php echo $this->sidebar; ?>
        </div>
    </div>
    <div class="span7"> 
        <div class="well well-small">
            <?php echo JText::_('COM_ADMIRORGALLERY_DESCRIPTION'); ?>
        </div>
    </div>
    <div class="span3"> 
        <div class="well well-small">
            <div class="module-title nav-header"> <?php echo JText::_('COM_ADMIRORGALLERY_COMPONENT_VERSION'); ?> </div>
            <ul class="unstyled list-striped">
                <?php
                $ag_admirorgallery_xml = JFactory::getXML(JPATH_COMPONENT_ADMINISTRATOR . '/com_admirorgallery.xml');
                if ($ag_admirorgallery_xml) {
                    echo '<li>' . JText::_('COM_ADMIRORGALLERY_COMPONENT_VERSION') . '&nbsp;' . $ag_admirorgallery_xml->version . "</li>";
                    echo '<li>' . JText::_('COM_ADMIRORGALLERY_PLUGIN_VERSION') . '&nbsp;' . $ag_admirorgallery_xml->plugin_version . "</li>";
                    echo '<li>' . JText::_('COM_ADMIRORGALLERY_BUTTON_VERSION') . '&nbsp;' . $ag_admirorgallery_xml->button_version . "</li>";
                }
                ?>
            </ul>
        </div>
    </div>
</div>
