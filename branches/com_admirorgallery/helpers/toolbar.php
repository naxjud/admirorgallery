<?php 

// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.html.toolbar');

class AdmirorgalleryHelperToolbar extends JObject
{        
      function getToolbar() {


	      $bar = new JToolBar( 'toolbar' );

	      // The first parameter is the button type. have a look a the JToolbar or JButton docs for a full list of these.
	      // The second parameter is the class to apply to the button ( this will help us to apply an image to it as in the backend )
	      // The third parameter is the text to display on the button.
	      // The fourth is the task to set. When the button is pressed the javascript submitButton function is called and the hidden field 'task' is set to this value. We will see this later in our template file.
	      // The fifth states whether a selection must be made from an admin list before continuing.
	      $bar->appendButton( 'Standard','folderNew',JText::_( 'New Folder' ),'folderNew', false);
	      $bar->appendButton( 'Standard','upload-file',JText::_( 'Upload' ),'upload', false);
	      $bar->appendButton( 'Standard','remove',JText::_( 'Delete' ),'remove', false );
	      $bar->appendButton( 'Standard','rename',JText::_( 'Rename' ),'rename', false );
	      $bar->appendButton( 'Standard','priority',JText::_( 'Set Priority' ),'priority', false );
	      $bar->appendButton( 'Standard','description',JText::_( 'Write Captions' ),'description', false );
			
	      return $bar->render();

      }

}

?>