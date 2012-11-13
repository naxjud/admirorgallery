<?php

$ag_timeout=agGallery::readInlineParam("timeout",$match,5000);

$ag->addJavaScript('/plugins/content/AdmirorGallery/templates/innerFade/jquery.innerfade.js');


// Form HTML code
$html = '
<!-- ======================= Admiror Gallery -->
<ul id="ag_innerFade'.$galleryCount.''.$articleID.'">
';

foreach ($ag->images as $imagesKey => $imageValue)
{
		$html .= '
<li>
<img src="'.$ag->sitePath.$ag->params['rootFolder'].$ag->imagesFolderName.'/'.$imageValue.'" class="ag_thumbImg"   style="width:'.$ag->params['frame_width'].'px; height:'.$ag->params['frame_height'].'px;"/>
</li>
';

}	
	
$html .= '
</ul>

<script type="text/javascript">

jQuery(function(){

jQuery(\'#ag_innerFade'.$galleryCount.''.$articleID.'\').innerfade({
	speed: \'slow\',
	timeout: '.$ag_timeout.',
	type: \'sequence\',
	containerheight: \''.$ag->params['frame_height'].'px\'
	}); 

});

</script>

<style type="text/css">
#ag_innerFade'.$galleryCount.''.$articleID.'
{
	margin:0px;
	padding:0px;
}

#ag_innerFade'.$galleryCount.''.$articleID.' li
{
	list-style:none;
	margin:0px;
	padding:0px;
}
</style>

';	

?>
