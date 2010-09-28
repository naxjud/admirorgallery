<?php

$ag_timeout=$AG->getParameter("timeout",5000);
$AG->loadJS($AG->currTemplateRoot.'jquery.innerfade.js');

// Form HTML code
$html = '
<!-- ======================= Admiror Gallery -->
<ul id="ag_innerFade'.$AG->getGalleryID().'" class="ag_reseter">
';
foreach ($AG->images as $imagesKey => $imageValue)
{
		$html .= '
<li>
'.$AG->writeImage($imageValue,'ag_thumbImg') .'
</li>';
}	
$html .= '</ul>

<script type="text/javascript">

jQuery(function(){

jQuery(\'#ag_innerFade'.$AG->getGalleryID().'\').innerfade({
	speed: \'slow\',
	timeout: '.$ag_timeout.',
	type: \'sequence\',
	containerheight: \''.$AG->params['frame_height'].'px\'
	}); 

});

</script>

<style type="text/css">
#ag_innerFade'.$AG->getGalleryID().'
{
	margin:0px;
	padding:0px;
}

#ag_innerFade'.$AG->getGalleryID().' li
{
	list-style:none;
	margin:0px;
	padding:0px;
}
.ag_thumbImg{
    width:'.$AG->params['frame_width'].'px;
    height:'.$AG->params['frame_height'].'px;
}
</style>

';	

?>
