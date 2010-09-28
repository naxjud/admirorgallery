<?php
// Joomla security code
defined('_JEXEC') or die('Restricted access');

$AG->loadCSS($AG->currTemplateRoot.'jquery.jcarousel.css');
$AG->loadCSS($AG->currTemplateRoot.'tango/skin.css');
$AG->loadJS($AG->currTemplateRoot.'jquery.jcarousel.js');
$html=$AG->initPopup();
// Form HTML code
$html ='<!-- ======================= Admiror Gallery -->
<div id="AG_'.$AG->getGalleryID().'" class="ag_reseter AG_'.$AG->params['template'].'">
<ul id="ag_carousel_'.$AG->getGalleryID().'" class="jcarousel-skin-tango">';
foreach ($AG->images as $imageKey => $imageName){
		$html .= '<li>';
		$html.= $AG->writePopupThumb($imageName);
		$html .= '</li>';
}	
$html .= '</ul></div>

<script type="text/javascript">
jQuery(function(){

var ac_carousel_width = jQuery("#AG_'.$AG->getGalleryID().'").width()-82;
var ac_carousel_num = Math.floor(ac_carousel_width/'.$AG->params['th_height'].');

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
			scroll: ac_carousel_num,
			auto: 5,
                        initCallback: mycarousel_initCallback

		});

jQuery("#AG_'.$AG->getGalleryID().' .jcarousel-skin-tango .jcarousel-container-horizontal").css({width:('.$AG->params['th_height'].'*ac_carousel_num)+"px"})

jQuery("#AG_'.$AG->getGalleryID().' .jcarousel-skin-tango .jcarousel-clip-horizontal").css({width:('.$AG->params['th_height'].'*ac_carousel_num)+"px"})

});

</script>

<style type="text/css">

#AG_'.$AG->getGalleryID().' .jcarousel-list li,
#AG_'.$AG->getGalleryID().' .jcarousel-item,
#AG_'.$AG->getGalleryID().' .jcarousel-skin-tango .jcarousel-item,
#AG_'.$AG->getGalleryID().' .jcarousel-skin-tango .ag_thumbLink,
#AG_'.$AG->getGalleryID().' .jcarousel-skin-tango .ag_thumbImg
{
	width:'.($AG->params['th_height']).'px;
}

#AG_'.$AG->getGalleryID().' .jcarousel-list li,
#AG_'.$AG->getGalleryID().' .jcarousel-item,
#AG_'.$AG->getGalleryID().' .jcarousel-skin-tango .jcarousel-clip-horizontal,
#AG_'.$AG->getGalleryID().' .jcarousel-skin-tango .jcarousel-item,
#AG_'.$AG->getGalleryID().' .jcarousel-skin-tango .ag_thumbLink,
#AG_'.$AG->getGalleryID().' .jcarousel-skin-tango .ag_thumbImg
{
	height:'.$AG->params['th_height'].'px;
}

#AG_'.$AG->getGalleryID().' .jcarousel-skin-tango .jcarousel-next-horizontal,
#AG_'.$AG->getGalleryID().' .jcarousel-skin-tango .jcarousel-prev-horizontal
{
	top:'.($AG->params['th_height']/2+6).'px;
}
#AG_'.$AG->getGalleryID().' .jcarousel-list li,
#AG_'.$AG->getGalleryID().' .jcarousel-item
{
	height:'.$AG->params['th_height'].'px;
}
#AG_'.$AG->getGalleryID().' ul,
#AG_'.$AG->getGalleryID().' li
{
  background-image:none;
  padding:0;
}
</style>
';	
$html.=$AG->endPopup();

?>
