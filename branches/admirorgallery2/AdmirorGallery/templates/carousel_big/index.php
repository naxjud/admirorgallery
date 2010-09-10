<?php

// Joomla security code
defined('_JEXEC') or die('Restricted access');

$AG->loadCSS($AG->currTemplateRoot.'jquery.jcarousel.css');
$AG->loadJS($AG->currTemplateRoot.'jquery.jcarousel.js');

// Form HTML code
$html = '
<!-- ======================= Admiror Gallery -->
<div id="AG_'.$AG->getGalleryID().'" class="AG_'.$AG->params['template'].' ag_wrap">
<ul>
';

foreach ($AG->images as $imagesKey => $imageValue)
{
		$html .= '
<li>
<img id="slide-img-'.($imagesKey+1).'" src="'.$AG->sitePath.$AG->params['rootFolder'].$AG->imagesFolderName.'/'.$imageValue.'" class="slide" alt=""  style="width:'.$AG->params['frameWidth'].'px; height:'.$AG->params['frameHeight'].'px;"/>
</li>
';

}	
	
$html .= '
</ul>
';

$html .= '
    <div class="jcarousel-control">
';

foreach ($AG->images as $imagesKey => $imagesValue)
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
    
jQuery(\'#AG_'.$AG->getGalleryID().'\').jcarousel({
			scroll: 1,
			auto: 10,
			wrap: \'last\',
			initCallback: mycarousel_initCallback

		});

jQuery("#AG_'.$AG->getGalleryID().' .jcarousel-container-horizontal").css({width:"'.$AG->params['frameWidth'].'px"})

jQuery("#AG_'.$AG->getGalleryID().' .jcarousel-clip-horizontal").css({width:"'.$AG->params['frameWidth'].'px"})

jQuery(\'.jcarousel-control a[rel="1"]\').css({backgroundPosition:"left -20px", cursor:"default"});

});

</script>

<style type="text/css">

#AG_'.$AG->getGalleryID().' .jcarousel-list li,
#AG_'.$AG->getGalleryID().' .jcarousel-item,
#AG_'.$AG->getGalleryID().'
{
	width:'.$AG->params['frameWidth'].'px;
}

#AG_'.$AG->getGalleryID().' .jcarousel-list li,
#AG_'.$AG->getGalleryID().' .jcarousel-item,
#AG_'.$AG->getGalleryID().' .jcarousel-clip
{
	height:'.$AG->params['frameHeight'].'px;
}
#AG_'.$AG->getGalleryID().' ul,
#AG_'.$AG->getGalleryID().' li
{
  background-image:none;
  padding:0;
}

</style>

';	

?>
