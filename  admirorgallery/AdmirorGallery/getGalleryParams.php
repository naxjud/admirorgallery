<?php
		defined('_JEXEC') or die('Restricted access');
		//check for inline parametars
		$inline_galleryStyle = ag_getAttribute("template",$match);
		$inline_height = ag_getAttribute("height",$match);
		$inline_newImageTag_ = ag_getAttribute("newImageTag",$match);
		$inline_newImageTag_days_ = ag_getAttribute("newImageDays",$match);
		$inline_sortImages = ag_getAttribute("sortByDate",$match);
		$inline_showSignature_ = ag_getAttribute("showSignature",$match);
		
		//setting parametars for current gallery, if there is no inline params default params are set
		if ($inline_galleryStyle)
			$_galleryStyle_= $inline_galleryStyle;
		else
			$_galleryStyle_=$default_galleryStyle_;
			
		if ($inline_height)
			$_height_= $inline_height;
		else
			$_height_=$default_height_;
			
		if ($inline_newImageTag_)
			$_newImageTag_=$inline_newImageTag_;
		else
			$_newImageTag_=$default_newImageTag_;
			
		if ($inline_newImageTag_days_)
			$_newImageTag_days_=$inline_newImageTag_days_;
		else
			$_newImageTag_days_=$default_newImageTag_days_;	
		
		if ($inline_sortImages)
			$_sortImages=$inline_sortImages;
		else
			$_sortImages=$default_sortImages;
			
		if ($inline_showSignature_)
			$_showSignature_=$inline_showSignature_;
		else
			$_showSignature_=$default_showSignature_;
?>