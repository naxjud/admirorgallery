<?php
	defined('_JEXEC') or die('Restricted access');
	$ag->addJavaScriptCode('jQuery(document).ready(function(){
	jQuery().piroBox({
		  my_speed: 400, //animation speed
		  bg_alpha: 0.5, //background opacity
		  slideShow : true, // true == slideshow on, false == slideshow off
		  slideSpeed : 4, //slideshow
		  close_all : \'.piro_close, .piro_overlay\' // add class .piro_overlay(with comma)if you want overlay click close piroBox
		  });
	});')

?>