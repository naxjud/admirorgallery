<?php
// Joomla security code
defined('_JEXEC') or die('Restricted access');

$AG->loadCSS($AG->currTemplateRoot.'jquery.jcarousel.css');
$AG->loadCSS($AG->currTemplateRoot.'tango/skin.css');
$AG->loadJS($AG->currTemplateRoot.'jquery.jcarousel.js');
$html=$AG->initPopup();
// Form HTML code
$html ='<!-- ======================= Admiror Gallery -->
<div id="AG_'.$AG->getGalleryID().'" class="AG_'.$AG->params['template'].'">
<ul id="ag_carousel_'.$AG->getGalleryID().'" class="jcarousel-skin-tango">';
foreach ($AG->images as $imageKey => $imageName){
		$html .= '<li>
			<table border="0" cellspacing="0" cellpadding="0"><tbody><tr><td>';
			$html.= $AG->writePopupThumb($imageName);
		$html .= '</td><td class="ag_cv_description">
			'.$AG->writeDescription($imageName).'
			</td></tr></tbody></table>
			</li>';
}	
	
$html .= '
</ul>
</div>

<script type="text/javascript">

jQuery(function(){

var ac_carousel_width = jQuery("#AG_'.$AG->getGalleryID().'").width();

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
    
jQuery(\'#ag_carousel_'.$AG->getGalleryID().'\').jcarousel({
			scroll: 3,
			auto: 5,
       			vertical: true,
			initCallback: mycarousel_initCallback

		});

jQuery("#AG_'.$AG->getGalleryID().' .jcarousel-skin-tango .jcarousel-clip-vertical").css({width:ac_carousel_width+"px"});

jQuery("#AG_'.$AG->getGalleryID().' .jcarousel-list li").css({width:ac_carousel_width+"px"});

jQuery("#AG_'.$AG->getGalleryID().' .jcarousel-skin-tango .jcarousel-prev-vertical").css({left:((ac_carousel_width/2)+6)+"px"});

jQuery("#AG_'.$AG->getGalleryID().' .jcarousel-skin-tango .jcarousel-next-vertical").css({left:((ac_carousel_width/2)+6)+"px"});

});

</script>

<style type="text/css">

#AG_'.$AG->getGalleryID().' .jcarousel-list li,
#AG_'.$AG->getGalleryID().' .jcarousel-item
{
	height:'.$AG->params['th_height'].'px;
}

#AG_'.$AG->getGalleryID().' .jcarousel-skin-tango .jcarousel-clip-vertical
{
	height:'.($AG->params['th_height']*3+3).'px;
}

</style>';	
$html.=$AG->endPopup();
?>
