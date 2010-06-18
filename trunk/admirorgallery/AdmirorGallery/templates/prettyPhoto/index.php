<?php
$doc->addScript($joomla_site_path.'/plugins/content/AdmirorGallery/templates/prettyPhoto/jquery.prettyPhoto.js');
$doc->addStyleSheet($joomla_site_path.'/plugins/content/AdmirorGallery/templates/prettyPhoto/prettyPhoto.css');

// Form HTML code
$html = '<!-- ======================= Admiror Gallery -->
<div class="AdmirorPrettyPhotoGallery">
<table class="AdmirorPrettyPhotoGallery" cellspacing="0" cellpadding="0" border="0">
<tbody>
<tr>
<td>
';

if (!empty($images)){
	foreach ($images as $imagesKey => $imagesValue){
		$html .= '<span class="ag_thumb"><a href="'.$joomla_site_path.$rootFolder.$imagesFolder_name.'/'.$imagesValue.'" rel="prettyPhoto'.$galleryCount.'[AdmirorGallery'.$galleryCount.']'.$articleID.'" title="';
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
<script type="text/javascript" charset="utf-8">
        jQuery(document).ready(function(){
                jQuery("a[rel^=\'prettyPhoto'.$galleryCount.'\']").prettyPhoto();
        });
</script>
</div>
';

?>