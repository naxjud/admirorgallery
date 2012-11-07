<?php
/*------------------------------------------------------------------------
# com_admirorgallery - Admiror Gallery Component
# ------------------------------------------------------------------------
# author   Igor Kekeljevic & Nikola Vasiljevski
# copyright Copyright (C) 2011 admiror-design-studio.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.admiror-design-studio.com/joomla-extensions
# Technical Support:  Forum - http://www.vasiljevski.com/forum/index.php
# Version: 4.5.0
-------------------------------------------------------------------------*/
// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.html.toolbar');

class AdmirorgalleryHelperToolbar extends JObject
{
      public static function getToolbar() {


	      $bar = new JToolBar( 'toolbar' );

	      // The first parameter is the button type. have a look a the JToolbar or JButton docs for a full list of these.
	      // The second parameter is the class to apply to the button ( this will help us to apply an image to it as in the backend )
	      // The third parameter is the text to display on the button.
	      // The fourth is the task to set. When the button is pressed the javascript submitButton function is called and the hidden field 'task' is set to this value. We will see this later in our template file.
	      // The fifth states whether a selection must be made from an admin list before continuing.
	      $bar->appendButton( 'Standard','AG_apply','COM_ADMIRORGALLERY_APPLY_DESC','AG_apply', false);
	      $bar->appendButton( 'Standard','AG_reset','COM_ADMIRORGALLERY_RESET_DESC','AG_reset', false);

	      return $bar->render();

      }

}

?>