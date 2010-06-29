<?php

//get parametars
$default_frame_width_ = $pluginParams->get('frame_width','500');
$default_frame_height_ = $pluginParams->get('frame_height','300');


$inline_frame_width_ = ag_getAttribute("frameWidth",$match);
$inline_frame_height_ = ag_getAttribute("frameHeight",$match);


if ($inline_frame_width_)
	$_frame_width_=$inline_frame_width_;
else
	$_frame_width_=$default_frame_width_;
	
if ($inline_frame_height_)
	$_frame_height_=$inline_frame_height_;
else
	$_frame_height_=$default_frame_height_;

$ag_timeout = 5000;
$inline_innerFade_timeout = ag_getAttribute("timeout",$match);
if ($inline_innerFade_timeout){$ag_timeout=$inline_innerFade_timeout;}


$doc->addScript($joomla_site_path.'/plugins/content/AdmirorGallery/templates/innerFade/jquery.innerfade.js');


// Form HTML code
$html = '
<!-- ======================= Admiror Gallery -->
<ul id="ag_innerFade'.$galleryCount.''.$articleID.'">
';

foreach ($images as $imagesKey => $imagesValue)
{
		$html .= '
<li>
<img src="'.$joomla_site_path.$rootFolder.$imagesFolder_name.'/'.$imagesValue.'" class="ag_thumbImg"   style="width:'.$_frame_width_.'px; height:'.$_frame_height_.'px;"/>
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
	containerheight: \''.$_frame_height_.'px\'
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
