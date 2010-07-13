<?php
defined('_JEXEC') or die('Restricted access');
$ag->addCSS('/plugins/content/AdmirorGallery/templates/'.$ag->params['galleryStyle'].'/template.css');
// Form HTML code
$html = '<!-- ======================= Admiror Gallery -->
<div id="AdmirorGallery'.$galleryCount.'('.$ag->params['galleryStyle'].''.$articleID.')">
<table class="AdmirorGallery" cellspacing="0" cellpadding="0" border="0">
<tbody>
<tr>
<td>';
foreach ($ag->images as $imagesKey => $imageValue){
        $html.= '<span class="ag_thumb'.$ag->params['galleryStyle'].'">';
        $html.= $ag->generatePopupHTML($popup,$imageValue);
        $html.='</span>';
}
$html .='
</td>
</tr>
</tbody>
</table>
</div>
';
$html.=$popup->jsInclude;
?>
