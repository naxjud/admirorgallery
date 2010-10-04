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
<div id="AG_'.$AG->getGalleryID().'" class="ag_reseter AG_listed">
';

$ag_images_count = sizeof($AG->images);
$ag_images_col1 = array_slice($AG->images, 0, $ag_images_count/2);
$ag_images_col2 = array_slice($AG->images, $ag_images_count/2);

// Loads values into $AG->imageInfo array for target image
$AG->getImageInfo($imageName);

$html .= '
    <table border="0" cellspacing="0" cellpadding="0" width="100%">
    <tbody>
    <tr><td>
';

foreach ($ag_images_col1 as $imageKey => $imageName)
{
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

$html .='</td><td style="width:10px;">&nbsp;</td><td>';

foreach ($ag_images_col2 as $imageKey => $imageName)
{
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

$html .='</td></tr></tbody></table>';

$html .='<!-- Admiror Gallery --></div>';

// Loads scripts needed for Popups, after gallery is created
$html.=$AG->endPopup();

?>

