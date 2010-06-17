<?php
 
// Load Functions
if(!function_exists("imageInfo")){
  include(JPATH_BASE .DS.'/plugins/content/AdmirorGallery/templates/artistic/imageInfo.php');
}
if(!function_exists("fileRoundSize")){
  include(JPATH_BASE .DS.'/plugins/content/AdmirorGallery/templates/artistic/fileRoundSize.php');
}
$doc->addScript($joomla_site_path.'/plugins/content/AdmirorGallery/slimbox2.js');
$doc->addStyleSheet($joomla_site_path.'/plugins/content/AdmirorGallery/templates/artistic/template.css');

// Form HTML code
$html = '<!-- ======================= Admiror Gallery -->
<div class="AdmirorGallery'.$galleryCount.'('.$_galleryStyle_.')">
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tbody>
<tr>
<td class="agArtistic_galleryHolder">
';

if (!empty($images))
{
	foreach ($images as $imagesKey => $imagesValue)
	{
	
	    // Calculate $listed_imageSize
	    $agArtistic_imageInfo_array=imageInfo(JPATH_BASE .DS.$rootFolder.$imagesFolder_name.'/'.$imagesValue);
	
			$html .= '<span class="agArtistic_item"><a href="'.$joomla_site_path.$rootFolder.$imagesFolder_name.'/'.$imagesValue.'" rel="lightbox[AdmirorGallery'.$galleryCount.']" title="';
	$html .= $imagesDescritions[$imagesValue];
			$html .= '" alt="';
			$html .= $imagesDescritions[$imagesValue];
			$thumb_file = $joomla_site_path.'/plugins/content/AdmirorGallery/thumbs/'.$imagesFolder_name.'/'.$imagesValue;			
			$html .= '" target="_blank">';
			
			$fileStat=stat($imagesFolder.$imagesValue);
		  $fileAge=time()-$fileStat['ctime']; 
			if((int)$fileAge < (int)($_newImageTag_days_*24*60*60) && $_newImageTag_==1){
			  $html .= '<span class="agArtistic_newTag"><img src="'.$joomla_site_path.'/plugins/content/AdmirorGallery/templates/'.$_galleryStyle_.'/newTag.gif" class="agArtistic_newImageTag" /></span>';		
		  }			
				$html .= '<img src="'.$thumb_file.'" bo$(function() {rder="0" />';
				$html .='</a>';	
				$html .='<span class="agArtistic_description">'.$imagesDescritions[$imagesValue].'</span>';	
				$html .='<span class="agArtistic_size">'.fileRoundSize($agArtistic_imageInfo_array['size']).'</span>';	
				$html .='</span>';			
	}			

}

$html .='
</td>
</tr>
</tbody>
</table>
';

if($_showSignature_==1){
  $html .='<div class="AdmirorGallery_label">';
}else{
  $html .='<div style="display:none">';
}
$html .=' 
<a href="http://www.admiror-design-studio.com" class="AdmirorGallery_linkAdmiror"><span>Admiror</span></a><a href="http://www.vasiljevski.com" class="AdmirorGallery_linkGallery"><span>Gallery</span></a>
</div>
<!-- Admiror Gallery -->

';
$html .='</div>';
?>
