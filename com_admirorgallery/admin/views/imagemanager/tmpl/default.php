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
    <div class="span10">
        <div class="well well-small">
            <form action="<?php echo JRoute::_('index.php?option=com_admirorgallery&view=imagemanager'); ?>" 
                  method="post" 
                  name="adminForm" 
                  id="adminForm" 
                  enctype="multipart/form-data">

                <input type="hidden" name="option" value="com_admirorgallery" />
                <input type="hidden" name="task" value="" />
                <input type="hidden" name="boxchecked" value="0" />
                <input type="hidden" name="view" value="imagemanager" />
                <input type="hidden" name="controller" value="imagemanager" />
                <input type="hidden" name="AG_itemURL" value="<?php echo $this->ag_init_itemURL; ?>" id="AG_input_itemURL" />

                <?php
                if (file_exists(JPATH_SITE . $this->ag_init_itemURL)) {
                    if (is_dir(JPATH_SITE . $this->ag_init_itemURL)) {
                        $ag_init_itemType = "folder";
                    } else {
                        $ag_init_itemType = "file";
                    }
                    require_once (JPATH_ROOT . DIRECTORY_SEPARATOR . 'administrator' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_admirorgallery' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'imagemanager' . DIRECTORY_SEPARATOR . 'tmpl' . DIRECTORY_SEPARATOR . 'default_' . $ag_init_itemType . '.php');
                } else {
                    $ag_error[] = Array(JText::_('AG_FOLDER_OR_IMAGE_NOT_FOUND'), $this->ag_init_itemURL);
                    JError::raiseWarning('3', JText::_('AG_FOLDER_OR_IMAGE_NOT_FOUND') . '<br>' . $this->ag_init_itemURL);
                    $ag_preview_content = '
                                    <div class="ag_screenSection_title">
                                         ' . $this->ag_init_itemURL . '
                                    </div>';
                    return;
                }
                ?>
                <!--Include JavaScript-->
                <?php require_once (JPATH_ROOT . DIRECTORY_SEPARATOR . 'administrator' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_admirorgallery' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'imagemanager' . DIRECTORY_SEPARATOR . 'tmpl' . DIRECTORY_SEPARATOR . 'default_script.php'); ?>
                <!--In front end add toolbar-->
                <?php
                if ($this->ag_front_end == 'true') {
                    require_once( JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'toolbar.php' );
                    ?>
                    <div class="AG_border_color AG_border_width AG_toolbar">
                        <?php echo AdmirorgalleryHelperToolbar::getToolbar(); ?>
                    </div>
                <?php } ?>
                <!--Include panel HTML-->
               <?php require_once (JPATH_ROOT . DIRECTORY_SEPARATOR . 'administrator' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_admirorgallery' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'imagemanager' . DIRECTORY_SEPARATOR . 'tmpl' . DIRECTORY_SEPARATOR . 'default_panel.php'); ?>
            </form>
        </div>
    </div>
</div>