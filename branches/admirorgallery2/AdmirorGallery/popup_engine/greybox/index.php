<?php
	defined('_JEXEC') or die('Restricted access');
	include_once(JPATH_BASE.DS.'plugins/content/AdmirorGallery/popup_engine/'.$ag->params['popupEngine'].'/include.php');
	$popup->rel = 'gb_imageset[AdmirorGallery'.$galleryCount.''.$articleID.']';
	$popup->cssClass= '';
?>
