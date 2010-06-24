<?php
// Load Functions
if(!function_exists("imageInfo")){
  include(JPATH_BASE .DS.'/plugins/content/AdmirorGallery/imageInfo.php');
}
if(!function_exists("fileRoundSize")){
  include(JPATH_BASE .DS.'/plugins/content/AdmirorGallery/fileRoundSize.php');
}
$doc->addStyleSheet($joomla_site_path.'/plugins/content/AdmirorGallery/templates/listed/listed.css');
// Form HTML code
$html = '<!-- ======================= Admiror Gallery -->
<div id="AdmirorGallery'.$galleryCount.'('.$_galleryStyle_.''.$articleID.')">
<div class="AdmirorListedGallery">
';
isset($customTag) ? $tempTag=$customTag : $tempTag='';
if (!empty($images))
{
	foreach ($images as $imagesKey => $imagesValue)
	{
	
	// Calculate $listed_imageSize
	$imageInfo_array=imageInfo(JPATH_BASE .DS.$rootFolder.$imagesFolder_name.'/'.$imagesValue);	
	
		$html .= '
		<table border="0" cellspacing="0" cellpadding="0" width="100%" class="ag_item">
    <tbody>
		<tr><td class="ag_thumbTd">
		<span class="ag_thumb'.$_galleryStyle_.'">';
		$imgWrapS = '<span class="ag_thumbSpan">';
		$imgWrapE = '</span>';
		include (JPATH_BASE.DS.'plugins/content/AdmirorGallery/imageHTMLout.php');			
		$html .='</span></td>
		<td class="ag_info">
		<table border="0" cellspacing="0" cellpadding="0">
    <tbody>
		<tr><td class="ag_description">
		'.htmlspecialchars(strip_tags($imagesDescritions[$imagesValue])).'
		<tr><td class="ag_imageStat">
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
<!-- Admiror Gallery -->';
$html .='</div>';
if (isset($jsInclude)) 
$html.=$jsInclude;	

?>

