<?php


$doc->addStyleSheet($joomla_site_path.'/plugins/content/AdmirorGallery/templates/carousel/jquery.jcarousel.css');
$doc->addStyleSheet($joomla_site_path.'/plugins/content/AdmirorGallery/templates/carousel/tango/skin.css');
$doc->addScript($joomla_site_path.'/plugins/content/AdmirorGallery/templates/carousel/jquery.jcarousel.js');


// Form HTML code
$html = '
<!-- ======================= Admiror Gallery -->
<div id="ag_wrap'.$galleryCount.''.$articleID.'" style="display:block;">
<ul id="ag_carousel'.$galleryCount.''.$articleID.'" class="jcarousel-skin-tango">
';
isset($customTag) ? $tempTag=$customTag : $tempTag='';
foreach ($images as $imagesKey => $imagesValue)
{
		$html .= '
<li>
<a href="'.$joomla_site_path.$rootFolder.$imagesFolder_name.'/'.$imagesValue.'" class="ag_thumbLink '.$cssClass.'" rel="'.$rel.'" '.$tempTag.' title="';
	  $html .= htmlspecialchars(strip_tags($imagesDescritions[$imagesValue]));
		$html .= '" alt="';
		$html .= htmlspecialchars(strip_tags($imagesDescritions[$imagesValue]));
		$html .= '" target="_blank"><span class="ag_thumbSpan">';
			
		$fileStat=stat($imagesFolder.$imagesValue);
		$fileAge=time()-$fileStat['ctime']; 
		if((int)$fileAge < (int)($_newImageTag_days_*24*60*60) && $_newImageTag_==1){
			$html .= '<span class="ag_newTag"><img src="'.$joomla_site_path.'/plugins/content/AdmirorGallery/newTag.gif" class="ag_newImageTag" /></span>';		
		}				
		
$html .= '<img src="'.$joomla_site_path.'/plugins/content/AdmirorGallery/thumbs/'.$imagesFolder_name.'/'.$imagesValue.'" class="ag_thumbImg" />';
	
$html .= '
</span></a>
</li>
';

}	
	
$html .= '
</ul>
</div>

<script type="text/javascript">

jQuery(function(){

var ac_carousel_width = jQuery("#ag_wrap'.$galleryCount.''.$articleID.'").width()-82;
var ac_carousel_num = Math.floor(ac_carousel_width/'.$default_height_.');

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

jQuery("#ag_wrap'.$galleryCount.''.$articleID.' .jcarousel-skin-tango .jcarousel-container-horizontal").css({width:('.$default_height_.'*ac_carousel_num)+"px"})

jQuery("#ag_wrap'.$galleryCount.''.$articleID.' .jcarousel-skin-tango .jcarousel-clip-horizontal").css({width:('.$default_height_.'*ac_carousel_num)+"px"})

});

</script>

<style type="text/css">

#ag_wrap'.$galleryCount.''.$articleID.' .jcarousel-list li,
#ag_wrap'.$galleryCount.''.$articleID.' .jcarousel-item,
#ag_wrap'.$galleryCount.''.$articleID.' .jcarousel-skin-tango .jcarousel-item,
#ag_wrap'.$galleryCount.''.$articleID.' .jcarousel-skin-tango .ag_thumbLink,
#ag_wrap'.$galleryCount.''.$articleID.' .jcarousel-skin-tango .ag_thumbImg
{
	width:'.$default_height_.'px;
}

#ag_wrap'.$galleryCount.''.$articleID.' .jcarousel-list li,
#ag_wrap'.$galleryCount.''.$articleID.' .jcarousel-item,
#ag_wrap'.$galleryCount.''.$articleID.' .jcarousel-skin-tango .jcarousel-clip-horizontal,
#ag_wrap'.$galleryCount.''.$articleID.' .jcarousel-skin-tango .jcarousel-item,
#ag_wrap'.$galleryCount.''.$articleID.' .jcarousel-skin-tango .ag_thumbLink,
#ag_wrap'.$galleryCount.''.$articleID.' .jcarousel-skin-tango .ag_thumbImg
{
	height:'.$default_height_.'px;
}

#ag_wrap'.$galleryCount.''.$articleID.' .jcarousel-skin-tango .jcarousel-next-horizontal,
#ag_wrap'.$galleryCount.''.$articleID.' .jcarousel-skin-tango .jcarousel-prev-horizontal
{
	top:'.($default_height_/2+6).'px;
}

</style>

';	
if (isset($jsInclude)) 
$html.=$jsInclude;		

?>
