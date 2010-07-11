<?php

$ag->addCSS('/plugins/content/AdmirorGallery/templates/'.$ag->params['galleryStyle'].'/jquery.jcarousel.css');
$ag->addCSS('/plugins/content/AdmirorGallery/templates/'.$ag->params['galleryStyle'].'/tango/skin.css');
$ag->addJavaScript('/plugins/content/AdmirorGallery/templates/'.$ag->params['galleryStyle'].'/jquery.jcarousel.js');

// Form HTML code
$html = '
<!-- ======================= Admiror Gallery -->
<div id="ag_wrap'.$galleryCount.''.$articleID.'" style="display:block;">
<ul id="ag_carousel'.$galleryCount.''.$articleID.'" class="jcarousel-skin-tango">
';
$popup->cssClass.=' ag_thumbLink';
$popup->imgWrapS = '<span class="ag_thumbSpan">';
$popup->imgWrapE = '</span>';
foreach ($ag->images as $imagesKey => $imageValue)
{
		$html .= '<li>';
		$html.= $ag->generatePopupHTML($popup,$imageValue);
		$html .= '</li>';
}	
	
$html .= '</ul>
</div>

<script type="text/javascript">

jQuery(function(){

var ac_carousel_width = jQuery("#ag_wrap'.$galleryCount.''.$articleID.'").width()-82;
var ac_carousel_num = Math.floor(ac_carousel_width/'.$ag->params['th_height'].');

function mycarousel_initCallback(carousel)
		{
			// Disable autoscrolling if the user clicks the prev or next button.
			carousel.buttonNext.bind(\'click\', function() {
			carousel.startAuto(0);
			});

			carousel.buttonPrev.bind(\'click\', function() {
			carousel.startAuto(0);
			});

			// Pause autoscrolling if the user moves with the cursor over the clip.
			carousel.clip.hover(function() {
			carousel.stopAuto();
			}, function() {
			carousel.startAuto();
			});
		};
    
jQuery(\'#ag_carousel'.$galleryCount.''.$articleID.'\').jcarousel({
			scroll: ac_carousel_num,
			auto: 5,
			initCallback: mycarousel_initCallback

		});

jQuery("#ag_wrap'.$galleryCount.''.$articleID.' .jcarousel-skin-tango .jcarousel-container-horizontal").css({width:('.$ag->params['th_height'].'*ac_carousel_num)+"px"})

jQuery("#ag_wrap'.$galleryCount.''.$articleID.' .jcarousel-skin-tango .jcarousel-clip-horizontal").css({width:('.$ag->params['th_height'].'*ac_carousel_num)+"px"})

});

</script>

<style type="text/css">

#ag_wrap'.$galleryCount.''.$articleID.' .jcarousel-list li,
#ag_wrap'.$galleryCount.''.$articleID.' .jcarousel-item,
#ag_wrap'.$galleryCount.''.$articleID.' .jcarousel-skin-tango .jcarousel-item,
#ag_wrap'.$galleryCount.''.$articleID.' .jcarousel-skin-tango .ag_thumbLink,
#ag_wrap'.$galleryCount.''.$articleID.' .jcarousel-skin-tango .ag_thumbImg
{
	width:'.$ag->params['th_height'].'px;
}

#ag_wrap'.$galleryCount.''.$articleID.' .jcarousel-list li,
#ag_wrap'.$galleryCount.''.$articleID.' .jcarousel-item,
#ag_wrap'.$galleryCount.''.$articleID.' .jcarousel-skin-tango .jcarousel-clip-horizontal,
#ag_wrap'.$galleryCount.''.$articleID.' .jcarousel-skin-tango .jcarousel-item,
#ag_wrap'.$galleryCount.''.$articleID.' .jcarousel-skin-tango .ag_thumbLink,
#ag_wrap'.$galleryCount.''.$articleID.' .jcarousel-skin-tango .ag_thumbImg
{
	height:'.$ag->params['th_height'].'px;
}

#ag_wrap'.$galleryCount.''.$articleID.' .jcarousel-skin-tango .jcarousel-next-horizontal,
#ag_wrap'.$galleryCount.''.$articleID.' .jcarousel-skin-tango .jcarousel-prev-horizontal
{
	top:'.($ag->params['th_height']/2+6).'px;
}
#ag_wrap'.$galleryCount.''.$articleID.' .jcarousel-list li,
#ag_wrap'.$galleryCount.''.$articleID.' .jcarousel-item
{
	height:'.$ag->params['th_height'].'px;
}
#ag_wrap'.$galleryCount.''.$articleID.' ul,
#ag_wrap'.$galleryCount.''.$articleID.' li
{
  background-image:none;
  padding:0;
}
</style>
';	
$html.=$popup->jsInclude;

?>
