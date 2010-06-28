<?php
 
// Load Functions
if(!function_exists("imageInfo")){
  include(JPATH_BASE .DS.'/plugins/content/AdmirorGallery/imageInfo.php');
}
if(!function_exists("fileRoundSize")){
  include(JPATH_BASE .DS.'/plugins/content/AdmirorGallery/fileRoundSize.php');
}
$doc->addStyleSheet($joomla_site_path.'/plugins/content/AdmirorGallery/templates/artistic/template.css');

// Form HTML code
$html = '<!-- ======================= Admiror Gallery -->
<div id="AdmirorGallery'.$galleryCount.'('.$_galleryStyle_.''.$articleID.')">
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tbody>
<tr>
<td class="agArtistic_galleryHolder">
';
isset($customTag) ? $tempTag=$customTag : $tempTag='';
if (!empty($images))
{
	foreach ($images as $imagesKey => $imagesValue)
	{
	
	    // Calculate $listed_imageSize
	    $agArtistic_imageInfo_array=imageInfo(JPATH_BASE .DS.$rootFolder.$imagesFolder_name.'/'.$imagesValue);
	
			$html .= '<span class="ag_thumb'.$_galleryStyle_.'">';
			include (JPATH_BASE.DS.'plugins/content/AdmirorGallery/imageHTMLout.php');
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
$html .='</div>';
//if there is any extra script that needs to be put after gallery html.
if (isset($jsInclude)) 
$html.=$jsInclude;
?>
