<?php

// Load Functions
if(!function_exists("imageInfo")){
  include(JPATH_BASE .DS.'/plugins/content/AdmirorGallery/templates/listed/imageInfo.php');
}

$doc->addStyleSheet($joomla_site_path.'/plugins/content/AdmirorGallery/templates/carousel-vertical/jquery.jcarousel.css');
$doc->addStyleSheet($joomla_site_path.'/plugins/content/AdmirorGallery/templates/carousel-vertical/tango/skin.css');
$doc->addScript($joomla_site_path.'/plugins/content/AdmirorGallery/templates/carousel-vertical/jquery.jcarousel.js');


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
<div id="ag_wrap'.$galleryCount.''.$articleID.'" style="display:block;">
<ul id="ag_carousel'.$galleryCount.''.$articleID.'" class="jcarousel-skin-tango">
';

foreach ($images as $imagesKey => $imagesValue)
{
		$html .= '
<li>
<table border="0" cellspacing="0" cellpadding="0"><tbody><tr><td>
<a href="'.$joomla_site_path.$rootFolder.$imagesFolder_name.'/'.$imagesValue.'" class="ag_thumbLink '.$cssClass.'" rel="'.$rel.'" '.$tempTag.' title="';
	  $html .= htmlspecialchars(strip_tags($imagesDescritions[$imagesValue]));
		$html .= '" alt="';
		$html .= htmlspecialchars(strip_tags($imagesDescritions[$imagesValue]));
		$html .= '" target="_blank"><span class="ag_thumbSpan">';
			
		$fileStat=stat($imagesFolder.$imagesValue);
		$fileAge=time()-$fileStat['ctime']; 
		if((int)$fileAge < (int)($_newImageTag_days_*24*60*60) && $_newImageTag_==1){
			$html .= '<span class="ag_newTag"><img src="'.$joomla_site_path.'/plugins/content/AdmirorGallery/templates/'.$_galleryStyle_.'/newTag.gif" class="ag_newImageTag" /></span>';		
		}				
		
$html .= '<img src="'.$joomla_site_path.'/plugins/content/AdmirorGallery/thumbs/'.$imagesFolder_name.'/'.$imagesValue.'" class="ag_thumbImg" />';
	
$html .= '
</span></a>
</td><td class="ag_cv_description">
'.$imagesDescritions[$imagesValue].'
</td></tr></tbody></table>
</li>
';

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
	height:'.$_height_.'px;
}

#ag_wrap'.$galleryCount.''.$articleID.' .jcarousel-skin-tango .jcarousel-clip-vertical
{
	height:'.($_height_*3+3).'px;
}

</style>

';	

?>
