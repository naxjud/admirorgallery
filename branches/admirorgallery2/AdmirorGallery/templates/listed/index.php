<?php
$AG->initPopup();
$AG->loadCSS('templates/'.$AG->params['template'].'/listed.css');
// Form HTML code
$html = '<!-- ======================= Admiror Gallery -->
<div id="AdmirorGallery_'.$AG->params['template'].'_'.$AG->getGalleryID().')" class="AdmirorListedGallery">
';
foreach ($AG->images as $imageKey => $imageName)
{
// Load values into $AG->imageInfo for target image
$AG->getImageInfo($imageName);

$html .= '
    <table border="0" cellspacing="0" cellpadding="0" width="100%" class="ag_item">
    <tbody>
    <tr><td class="ag_thumbTd">
    <span class="ag_thumb'.$AG->params['template'].'">';
    $html.= $AG->writePopupThumb($imageName);
    $html .='</span></td>
    <td class="ag_info">
    <table border="0" cellspacing="0" cellpadding="0">
    <tbody>
    <tr><td class="ag_description">
    '.$AG->writeDescription($imageName).'
    <tr><td class="ag_imageStat">
    <span>W:'.$AG->imageInfo["width"].'px</span>
    <span>H:'.$AG->imageInfo["height"].'px</span>
    <span>S:'.$AG->imageInfo["size"].'</span>
    </td></tr>
    </td></tr></tbody></table>
    </td></tr></tbody></table>';
}
$html .='<!-- Admiror Gallery --></div>';
$html.=$AG->endPopup();
?>

