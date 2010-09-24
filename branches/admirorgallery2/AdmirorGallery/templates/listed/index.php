<?php

// Joomla security code
defined('_JEXEC') or die('Restricted access');

// Reset $html variable from previous entery and load it with scripts needed for Popups
$html=$AG->initPopup();

// Load CSS from current template folder
$AG->loadCSS($AG->currTemplateRoot.'listed.css');

// Form HTML code, with unique ID and Class Name
$html.='<!-- ======================= Admiror Gallery -->
<style type="text/css">
.AG_listed .ag_thumbTd a:hover{border-bottom:2px solid #'.$AG->params['highliteColor'].';}
.AG_listed a .ag_imageThumb{background-color:#'.$AG->params['foregroundColor'].';}
.AG_listed .ag_description, .AG_listed .ag_imageStat span{color:#'.$AG->params['foregroundColor'].';}
</style>
<div id="AG_'.$AG->getGalleryID().'" class="AG_'.$AG->params['template'].'">
';
foreach ($AG->images as $imageKey => $imageName)
{

// Loads values into $AG->imageInfo array for target image
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

// Loads scripts needed for Popups, after gallery is created
$html.=$AG->endPopup();

?>

