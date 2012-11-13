<?php

$ag->addCSS('/plugins/content/AdmirorGallery/templates/'.$ag->params['galleryStyle'].'/template.css');

// Form HTML code
$html = '<!-- ======================= Admiror Gallery -->
<div id="AdmirorGallery'.$galleryCount.'('.$ag->params['galleryStyle'].''.$articleID.')">
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tbody>
<tr>
<td class="agArtistic_galleryHolder">
';
foreach ($ag->images as $imagesKey => $imageValue)
{
    // Calculate $listed_imageSize
    $agArtistic_imageInfo_array=agHelper::ag_imageInfo(JPATH_BASE .DS.$ag->params['rootFolder'].$ag->imagesFolderName.'/'.$imageValue);
    $html .= '<span class="ag_thumb'.$ag->params['galleryStyle'].'">';
    $html.= $ag->generatePopupHTML($popup,$imageValue);
    $html .='<span class="agArtistic_description">'.$ag->getDescription($imageValue).'</span>';
    $html .='<span class="agArtistic_size">'.agHelper::ag_fileRoundSize($agArtistic_imageInfo_array['size']).'</span>';
    $html .='</span>';
}			
$html .='
</td>
</tr>
</tbody>
</table>
';
$html .='</div>';
//if there is any extra script that needs to be put after gallery html.
$html.=$popup->jsInclude;
?>
