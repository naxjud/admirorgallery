<?php
// Load Functions
if(!function_exists("imageInfo")){
  include(JPATH_BASE .DS.'/plugins/content/AdmirorGallery/templates/listed/imageInfo.php');
}
if(!function_exists("fileRoundSize")){
  include(JPATH_BASE .DS.'/plugins/content/AdmirorGallery/templates/listed/fileRoundSize.php');
}
$doc->addScript($joomla_site_path.'/plugins/content/AdmirorGallery/slimbox2.js');
$doc->addStyleSheet($joomla_site_path.'/plugins/content/AdmirorGallery/templates/listed/listed.css');
// Form HTML code
$html = '<!-- ======================= Admiror Gallery -->
<div class="AdmirorGallery'.$galleryCount.'('.$_galleryStyle_.')">
<div class="AdmirorListedGallery">
';

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
		<a href="'.$joomla_site_path.$rootFolder.$imagesFolder_name.'/'.$imagesValue.'" rel="lightbox[AdmirorGallery'.$galleryCount.']'.$articleID.'" title="';
	  $html .= $imagesDescritions[$imagesValue];
		$html .= '" alt="';
		$html .= $imagesDescritions[$imagesValue];
		$html .= '" target="_blank"><span class="ag_thumbSpan">';
			
		$fileStat=stat($imagesFolder.$imagesValue);
		$fileAge=time()-$fileStat['ctime']; 
		if((int)$fileAge < (int)($_newImageTag_days_*24*60*60) && $_newImageTag_==1){
			$html .= '<span class="ag_newTag"><img src="'.$joomla_site_path.'/plugins/content/AdmirorGallery/templates/'.$_galleryStyle_.'/newTag.gif" class="ag_newImageTag" /></span>';		
		}				
		
		$html .= '<img src="'.$joomla_site_path.'/plugins/content/AdmirorGallery/thumbs/'.$imagesFolder_name.'/'.$imagesValue.'" class="ag_thumbImg" />';
			
		$html .= '</span></a></td>';
			
		$html .='<td class="ag_info">
		<table border="0" cellspacing="0" cellpadding="0">
    <tbody>
		<tr><td class="ag_description">
		'.$imagesDescritions[$imagesValue].'
		<tr><td class="ag_imageStat">
		<span>W:'.$imageInfo_array["width"].'px</span>
		<span>H:'.$imageInfo_array["height"].'px</span>
		<span>S:'.fileRoundSize($imageInfo_array['size']).'</span>
		</td></tr>
		</td></tr></tbody></table>
		</td></tr></tbody></table>';
	}
}

if($_showSignature_==1){
  $html .='<div class="ag_label">';
}else{
  $html .='<div style="display:none">';
}
$html .='
<a href="http://www.admiror-design-studio.com" class="ag_linkAdmiror"><span>Admiror</span></a><a href="http://www.vasiljevski.com" class="ag_linkGallery"><span>Gallery</span></a>
</div>
</div>
<!-- Admiror Gallery -->';
$html .='</div>';
?>
