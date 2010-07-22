<?php
defined('_JEXEC') or die('Restricted access');
$AG->initPopup();
$AG->loadCSS('templates/'.$AG->params['template'].'/template.css');
// Form HTML code
$html = '<!-- ======================= Admiror Gallery -->
<div id="AdmirorGallery_'.$AG->params['template'].'_'.$AG->getGalleryID().'">
<table class="AdmirorGallery" cellspacing="0" cellpadding="0" border="0">
<tbody>
<tr>
<td>';
foreach ($AG->images as $imageKey => $imageName){
        $html.= '<span class="ag_thumb'.$AG->params['template'].'">';
        $html.= $AG->writePopupThumb($imageName);
        $html.='</span>';
}
$html .='
</td>
</tr>
</tbody>
</table>
</div>
';
$html.=$AG->popupEngine->jsInclude;
?>
