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
		$html .= '<li>
			<table border="0" cellspacing="0" cellpadding="0"><tbody><tr><td>';
			$html.= $ag->generatePopupHTML($popup,$imageValue);
		$html .= '</td><td class="ag_cv_description">
			'.$ag->getDescription($imageValue).'
			</td></tr></tbody></table>
			</li>';
}	
	
$html .= '
</ul>
</div>

<script type="text/javascript">

jQuery(function(){

var ac_carousel_width = jQuery("#ag_wrap'.$galleryCount.''.$articleID.'").width();

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
			scroll: 3,
			auto: 5,
       			vertical: true,
			initCallback: mycarousel_initCallback

		});

jQuery("#ag_wrap'.$galleryCount.''.$articleID.' .jcarousel-skin-tango .jcarousel-clip-vertical").css({width:ac_carousel_width+"px"});

jQuery("#ag_wrap'.$galleryCount.''.$articleID.' .jcarousel-list li").css({width:ac_carousel_width+"px"});

jQuery("#ag_wrap'.$galleryCount.''.$articleID.' .jcarousel-skin-tango .jcarousel-prev-vertical").css({left:((ac_carousel_width/2)+6)+"px"});

jQuery("#ag_wrap'.$galleryCount.''.$articleID.' .jcarousel-skin-tango .jcarousel-next-vertical").css({left:((ac_carousel_width/2)+6)+"px"});

});

</script>

<style type="text/css">

#ag_wrap'.$galleryCount.''.$articleID.' .jcarousel-list li,
#ag_wrap'.$galleryCount.''.$articleID.' .jcarousel-item
{
	height:'.$ag->params['th_height'].'px;
}

#ag_wrap'.$galleryCount.''.$articleID.' .jcarousel-skin-tango .jcarousel-clip-vertical
{
	height:'.($ag->params['th_height']*3+3).'px;
}

</style>

';	
$html.=$popup->jsInclude;
?>
