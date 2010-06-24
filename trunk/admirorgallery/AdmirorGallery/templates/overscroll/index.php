<?php

//get parametars
$default_frame_width_ = $pluginParams->get('frame_width','500');
$default_frame_height_ = $pluginParams->get('frame_height','300');
$defaultframe_color_=$pluginParams->get('framecolor','blue');

$inline_frame_width_ = ag_getAttribute("frameWidth",$match);
$inline_frame_height_ = ag_getAttribute("frameHeight",$match);
$inline_frame_color_ = ag_getAttribute("framecolor",$match);

if ($inline_frame_width_)
	$_frame_width_=$inline_frame_width_;
else
	$_frame_width_=$default_frame_width_;
	
if ($inline_frame_height_)
	$_frame_height_=$inline_frame_height_;
else
	$_frame_height_=$default_frame_height_;
	
if ($inline_frame_color_)
	$_frame_color_=$inline_frame_color_;
else
	$_frame_color_=$defaultframe_color_;


$doc->addScript($joomla_site_path.'/plugins/content/AdmirorGallery/templates/overscroll/scripts/jquery.overscroll.js');
$doc->addScript($joomla_site_path.'/plugins/content/AdmirorGallery/templates/overscroll/scripts/jquery.disableTextSelect.js');

// Load Functions
if(!function_exists("imageInfo")){
  include(JPATH_BASE.DS.'/plugins/content/AdmirorGallery/imageInfo.php');
}
if(!function_exists("fileRoundSize")){
  include(JPATH_BASE.DS.'/plugins/content/AdmirorGallery/fileRoundSize.php');
}

$doc->addStyleSheet($joomla_site_path.'/plugins/content/AdmirorGallery/templates/overscroll/listed.css');

$html = '';

$html .= '
<script type="text/javascript">

jQuery(function($) {
  jQuery(".ag_overscroll_bodyHolder").overscroll();
  jQuery(".ag_overscroll_bodyHolder").disableTextSelect();
});

</script>

<div class="ag_overscroll_bodyHolder ag_overscroll_'.$_frame_color_.'" style="width:'.$_frame_width_.'px; height:'.$_frame_height_.'px">
';

$html .= '<!-- ======================= Admiror Gallery -->
<div id="AdmirorGallery'.$galleryCount.'('.$_galleryStyle_.''.$articleID.')">
  <div class="AdmirorOverscrollGallery">
';
isset($customTag) ? $tempTag=$customTag : $tempTag='';
if (!empty($images))
{
	foreach ($images as $imagesKey => $imagesValue)
	{
	
	// Calculate $listed_imageSize
	$imageInfo_array=imageInfo(JPATH_BASE .DS.$rootFolder.$imagesFolder_name.'/'.$imagesValue);	
	
		$html .= '
		<table border="0" cellspacing="0" cellpadding="0" width="100%" class="ag_overscroll_item">
    <tbody>
		<tr><td class="ag_overscroll_thumbTd">
		<span class="ag_thumb'.$_galleryStyle_.'">
		<a href="'.$joomla_site_path.$rootFolder.$imagesFolder_name.'/'.$imagesValue.'" class="'.$cssClass.'" rel="'.$rel.'" '.$tempTag.' title="';
	  $html .= htmlspecialchars(strip_tags($imagesDescritions[$imagesValue]));
		$html .= '" alt="';
		$html .= htmlspecialchars(strip_tags($imagesDescritions[$imagesValue]));
		$html .= '" target="_blank"><span class="ag_overscroll_thumbSpan">';
			
		$html .= '<img src="'.$joomla_site_path.'/plugins/content/AdmirorGallery/thumbs/'.$imagesFolder_name.'/'.$imagesValue.'" class="ag_overscroll_thumbImg" />';
			
		$html .= '</span></a></span></td>';
			
		$html .='<td class="ag_overscroll_info">
		<table border="0" cellspacing="0" cellpadding="0">
    <tbody>
		<tr><td class="ag_overscroll_description">
		'.htmlspecialchars(strip_tags($imagesDescritions[$imagesValue])).'
		<tr><td class="ag_overscroll_imageStat">
		<span>W:'.$imageInfo_array["width"].'px</span>
		<span>H:'.$imageInfo_array["height"].'px</span>
		<span>S:'.fileRoundSize($imageInfo_array['size']).'</span>
		</td></tr>
		</td></tr></tbody></table>
		</td></tr></tbody></table>';
	}
}

$html .='
    </div>
  </div>
</div>
';
if (isset($jsInclude)) 
$html.=$jsInclude;	
?>
