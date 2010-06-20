<?php
$doc->addScript($joomla_site_path.'/plugins/content/AdmirorGallery/slimbox2.js');
$doc->addStyleSheet($joomla_site_path.'/plugins/content/AdmirorGallery/templates/classic/template.css');
// Form HTML code
$html = '<!-- ======================= Admiror Gallery -->
<div id="AdmirorGallery'.$galleryCount.'('.$_galleryStyle_.''.$articleID.')">
<table class="AdmirorGallery" cellspacing="0" cellpadding="0" border="0">
<tbody>
<tr>
<td>';
isset($customTag) ? $tempTag=$customTag : $tempTag='';
if (!empty($images)){
	foreach ($images as $imagesKey => $imagesValue){
		$html .= '<span class="ag_thumb'.$_galleryStyle_.'"><a href="'.$joomla_site_path.$rootFolder.$imagesFolder_name.'/'.$imagesValue.'" class="'.$cssClass.'" rel="'.$rel.'" '.$tempTag.' title="';
    $html .= $imagesDescritions[$imagesValue];
		$html .= '" alt="';
		$html .= $imagesDescritions[$imagesValue];
		$html .= '" target="_blank">';
		
		$fileStat=stat($imagesFolder.$imagesValue);
		$fileAge=time()-$fileStat['ctime']; 
		if((int)$fileAge < (int)($_newImageTag_days_*24*60*60) && $_newImageTag_==1){
			$html .= '<span class="ag_newTag"><img src="'.$joomla_site_path.'/plugins/content/AdmirorGallery/templates/'.$_galleryStyle_.'/newTag.gif" class="ag_newImageTag" /></span>';		
		}				
		
			$html .= '<img src="'.$joomla_site_path.'/plugins/content/AdmirorGallery/thumbs/'.$imagesFolder_name.'/'.$imagesValue.'" class="ag_imageThumb" />';
			$html .= '</a></span>';
			
	}
}

$html .='
</td>
</tr>

<tr>
';

if($_showSignature_==1){
  $html .='<td class="ag_label">';
}else{
  $html .='<td style="display:none">';
}

$html .='<a href="http://www.admiror-design-studio.com" class="ag_linkAdmiror"><span>Admiror</span></a><a href="http://www.vasiljevski.com" class="ag_linkGallery"><span>Gallery</span></a>
</td>
</tr>
</tbody>
</table>
</div>
';
if (isset($jsInclude)) 
$html.=$jsInclude;					
?>
