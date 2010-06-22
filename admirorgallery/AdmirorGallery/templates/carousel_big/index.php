<?php


$doc->addStyleSheet($joomla_site_path.'/plugins/content/AdmirorGallery/templates/carousel_big/jquery.jcarousel.css');
$doc->addScript($joomla_site_path.'/plugins/content/AdmirorGallery/templates/carousel_big/jquery.jcarousel.js');

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


// Form HTML code
$html = '
<!-- ======================= Admiror Gallery -->
<div id="ag_wrap'.$galleryCount.''.$articleID.'" class="ag_wrap">
<ul id="ag_carousel'.$galleryCount.''.$articleID.'">
';

foreach ($images as $imagesKey => $imagesValue)
{
		$html .= '
<li>
<img id="slide-img-'.($imagesKey+1).'" src="'.$joomla_site_path.$rootFolder.$imagesFolder_name.'/'.$imagesValue.'" class="slide" alt=""  style="width:'.$_frame_width_.'px; height:'.$_frame_height_.'px;"/>
</li>
';

}	
	
$html .= '
</ul>
';

$html .= '
    <div class="jcarousel-control">
';

foreach ($images as $imagesKey => $imagesValue)
{
  $html .= '
        <a href="#" rel="'.($imagesKey+1).'">&nbsp;</a>
  ';
}
$html .= '
    </div>
';

$html .= '
</div>

<script type="text/javascript">

jQuery(function(){

function mycarousel_initCallback(carousel)
		{
      jQuery(\'#ag_wrap'.$galleryCount.''.$articleID.' .jcarousel-control a\').bind(\'click\', function() {
        carousel.stopAuto();
        carousel.scroll(jQuery.jcarousel.intval(jQuery(this).attr("rel")));
        carousel.startAuto();
        return false;
      });

		
			// Pause autoscrolling if the user moves with the cursor over the clip.
			carousel.clip.hover(function() {
			carousel.stopAuto();
			}, function() {
			carousel.startAuto();
			});
		};
    
jQuery(\'#ag_carousel'.$galleryCount.''.$articleID.'\').jcarousel({
			scroll: 1,
			auto: 10,
			wrap: \'last\',
			initCallback: mycarousel_initCallback

		});

jQuery("#ag_wrap'.$galleryCount.''.$articleID.' .jcarousel-container-horizontal").css({width:"'.$_frame_width_.'px"})

jQuery("#ag_wrap'.$galleryCount.''.$articleID.' .jcarousel-clip-horizontal").css({width:"'.$_frame_width_.'px"})

jQuery(\'.jcarousel-control a[rel="1"]\').css({backgroundPosition:"left -20px", cursor:"default"});

});

</script>

<style type="text/css">

#ag_wrap'.$galleryCount.''.$articleID.' .jcarousel-list li,
#ag_wrap'.$galleryCount.''.$articleID.' .jcarousel-item,
#ag_wrap'.$galleryCount.''.$articleID.'
{
	width:'.$_frame_width_.'px;
}

#ag_wrap'.$galleryCount.''.$articleID.' .jcarousel-list li,
#ag_wrap'.$galleryCount.''.$articleID.' .jcarousel-item
{
	height:'.$_frame_height_.'px;
}

</style>

';	

?>
