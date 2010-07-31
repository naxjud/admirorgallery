<?php

$AG->loadCSS($AG->currTemplateRoot.'jquery.jcarousel.css');
$AG->loadJS($AG->currTemplateRoot.'jquery.jcarousel.js');

// Form HTML code
$html = '
<!-- ======================= Admiror Gallery -->
<div id="AG_'.$AG->getGalleryID().'" class="AG_'.$AG->params['template'].'">
<ul id="ag_carousel'.$AG->getGalleryID().'">
';

foreach ($AG->images as $imageKey => $imageName)
{
$html .= '
<li>
    <img id="slide-img-'.($imageKey+1).'" src="'.$AG->imagesFolderPath.$imageName.'" class="slide" alt=""  style="width:'.$AG->params['frame_width'].'px; height:'.$AG->params['frame_height'].'px;"/>
</li>
';
}
$html .= '</ul>';
$html .= '<div class="jcarousel-control" style="text-align: center;">';
foreach ($AG->images as $imageKey => $imageName)
{
  $html .= '<a href="#" rel="'.($imageKey+1).'">&nbsp;</a>';
}
$html .= '</div></div>';
$AG->insertJSCode('
jQuery(function(){
function mycarousel_initCallback(carousel){
      jQuery(\'#AG_'.$AG->getGalleryID().' .jcarousel-control a\').bind(\'click\', function() {
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
jQuery(\'#ag_carousel'.$AG->getGalleryID().'\').jcarousel({
			scroll: 1,
			auto: 10,
			wrap: \'last\',
			initCallback: mycarousel_initCallback
		});
jQuery("#AG_'.$AG->getGalleryID().' .jcarousel-container-horizontal").css({width:"'.$AG->params['frame_width'].'px"})
jQuery("#AG_'.$AG->getGalleryID().' .jcarousel-clip-horizontal").css({width:"'.$AG->params['frame_width'].'px"})
jQuery(\'.jcarousel-control a[rel="1"]\').css({backgroundPosition:"left -20px", cursor:"default"});
});');
$html .= '<style type="text/css">
#AG_'.$AG->getGalleryID().' .jcarousel-list li,#AG_'.$AG->getGalleryID().' .jcarousel-item,#AG_'.$AG->getGalleryID().'{width:'.$AG->params['frame_width'].'px;}
#AG_'.$AG->getGalleryID().' .jcarousel-list li,#AG_'.$AG->getGalleryID().' .jcarousel-item,#AG_'.$AG->getGalleryID().' .jcarousel-clip{height:'.$AG->params['frame_height'].'px;}
#AG_'.$AG->getGalleryID().' ul,#AG_'.$AG->getGalleryID().' li{background-image:none;padding:0;}
</style>';
?>
