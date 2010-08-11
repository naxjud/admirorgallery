<?php

// Joomla security code
defined('_JEXEC') or die('Restricted access');

// Load CSS from current template folder
$AG->loadCSS($AG->currTemplateRoot.'template.css');
// Reset $html variable from previous entery and load it with scripts needed for Popups
$html=$AG->initPopup();
// Form HTML code, with unique ID and Class Name
$html.='<!-- ======================= Admiror Gallery -->
<div id="AG_'.$AG->getGalleryID().'" class="AG_'.$AG->params['template'].'">
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tbody>
<tr>
<td class="ag_galleryHolder_gray">
';
// Loops over the array of images inside target gallery folder, adding wrapper with SPAN tag and write Popup thumbs inside this wrapper
foreach ($AG->images as $imageKey => $imageName)
{
	// Loads values into $AG->imageInfo array for target image
    $AG->getImageInfo($imageName);
    $html .= '<span class="ag_thumb">';
    $html .= $AG->writePopupThumb($imageName);
    $html .='<span class="ag_description">'.$AG->writeDescription($imageName).'</span>';
    $html .='<span class="ag_size">'.$AG->imageInfo["size"].'</span>';
    $html .='</span>';
}			
$html .='</td></tr></tbody></table><!-- Admiror Gallery --></div>';
// Loads scripts needed for Popups, after gallery is created
$html.=$AG->endPopup();
?>
