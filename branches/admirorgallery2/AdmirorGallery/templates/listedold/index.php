<?php

$ag->addCSS('/plugins/content/AdmirorGallery/templates/'.$ag->params['galleryStyle'].'/listed.css');
// Form HTML code
$html = '<!-- ======================= Admiror Gallery -->
<div id="AdmirorGallery'.$galleryCount.'('.$ag->params['galleryStyle'].''.$articleID.')">
<div class="AdmirorListedGallery">
';
foreach ($ag->images as $imagesKey => $imageValue)
{

// Calculate $listed_imageSize
$imageInfo_array=agHelper::ag_imageInfo(JPATH_BASE .DS.$ag->params['rootFolder'].$ag->imagesFolderName.'/'.$imageValue);

$html .= '
    <table border="0" cellspacing="0" cellpadding="0" width="100%" class="ag_item">
    <tbody>
    <tr><td class="ag_thumbTd">
    <span class="ag_thumb'.$ag->params['galleryStyle'].'">';
    $popup->imgWrapS = '<span class="ag_thumbSpan">';
    $popup->imgWrapE = '</span>';
    $html.= $ag->generatePopupHTML($popup,$imageValue);
    $html .='</span></td>
    <td class="ag_info">
    <table border="0" cellspacing="0" cellpadding="0">
    <tbody>
    <tr><td class="ag_description">
    '.$ag->getDescription($imageValue).'
    <tr><td class="ag_imageStat">
    <span>W:'.$imageInfo_array["width"].'px</span>
    <span>H:'.$imageInfo_array["height"].'px</span>
    <span>S:'.agHelper::ag_fileRoundSize($imageInfo_array['size']).'</span>
    </td></tr>
    </td></tr></tbody></table>
    </td></tr></tbody></table>';
}
$html .='
</div>
<!-- Admiror Gallery -->';
$html .='</div>';
$html.=$popup->jsInclude;

?>

