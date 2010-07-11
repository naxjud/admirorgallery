<?php

$ag->addCSS('/plugins/content/AdmirorGallery/templates/'.$ag->params['galleryStyle'].'/jquery.jcarousel.css');
$ag->addJavaScript('/plugins/content/AdmirorGallery/templates/'.$ag->params['galleryStyle'].'/jquery.jcarousel.js');

// Form HTML code
$html = '
<!-- ======================= Admiror Gallery -->
<div id="ag_wrap'.$galleryCount.''.$articleID.'" class="ag_wrap">
<ul id="ag_carousel'.$galleryCount.''.$articleID.'">
';

foreach ($ag->images as $imagesKey => $imageValue)
{
		$html .= '
<li>
<img id="slide-img-'.($imagesKey+1).'" src="'.$ag->sitePath.$ag->params['rootFolder'].$ag->imagesFolderName.'/'.$imageValue.'" class="slide" alt=""  style="width:'.$ag->params['frame_width'].'px; height:'.$ag->params['frame_height'].'px;"/>
</li>
';

}	
	
$html .= '
</ul>
';

$html .= '
    <div class="jcarousel-control">
';

foreach ($ag->images as $imagesKey => $imagesValue)
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

jQuery("#ag_wrap'.$galleryCount.''.$articleID.' .jcarousel-container-horizontal").css({width:"'.$ag->params['frame_width'].'px"})

jQuery("#ag_wrap'.$galleryCount.''.$articleID.' .jcarousel-clip-horizontal").css({width:"'.$ag->params['frame_width'].'px"})

jQuery(\'.jcarousel-control a[rel="1"]\').css({backgroundPosition:"left -20px", cursor:"default"});

});

</script>

<style type="text/css">

#ag_wrap'.$galleryCount.''.$articleID.' .jcarousel-list li,
#ag_wrap'.$galleryCount.''.$articleID.' .jcarousel-item,
#ag_wrap'.$galleryCount.''.$articleID.'
{
	width:'.$ag->params['frame_width'].'px;
}

#ag_wrap'.$galleryCount.''.$articleID.' .jcarousel-list li,
#ag_wrap'.$galleryCount.''.$articleID.' .jcarousel-item,
#ag_wrap'.$galleryCount.''.$articleID.' .jcarousel-clip
{
	height:'.$ag->params['frame_height'].'px;
}
#ag_wrap'.$galleryCount.''.$articleID.' ul,
#ag_wrap'.$galleryCount.''.$articleID.' li
{
  background-image:none;
  padding:0;
}

</style>

';	

?>
