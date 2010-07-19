<?php

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

class SCREENS_admirorgallery 
	{
		function _VIEW($view)
		{
			if($view=='') $view='default';
			require_once (JPATH_BASE.DS.'components/com_admirorgallery/screens/'.$view.'.php');
		}
       
}

?>
