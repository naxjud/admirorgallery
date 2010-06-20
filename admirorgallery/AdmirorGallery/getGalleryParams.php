<?php
		defined('_JEXEC') or die('Restricted access');
		
		//setting parametars for current gallery, if there is no inline params default params are set
		
		$_galleryStyle_= ag_getParams("template",$match,$default_galleryStyle_);
		$_height_= ag_getParams("height",$match,$default_height_);
		$_newImageTag_=ag_getParams("newImageTag",$match,$default_newImageTag_);
		$_newImageTag_days_= ag_getParams("newImageDays",$match,$default_newImageTag_days_);
		$_sortImages=ag_getParams("sortByDate",$match,$default_sortImages);
		$_showSignature_=ag_getParams("showSignature",$match,$default_showSignature_);
		$_overlayEngine_=ag_getParams("overlayEngine",$match,$default_overlayEngine_);

?>