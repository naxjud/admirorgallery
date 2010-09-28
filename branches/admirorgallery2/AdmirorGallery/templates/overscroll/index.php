<?php
// Joomla security code
defined('_JEXEC') or die('Restricted access');

$AG->loadJS($AG->currTemplateRoot.'scripts/jquery.overscroll.js');
$AG->loadJS($AG->currTemplateRoot.'scripts/jquery.disableTextSelect.js');
$AG->loadCSS($AG->currTemplateRoot.'listed.css');

$html=$AG->initPopup();
$html .='<div class="ag_overscroll_bodyHolder ag_overscroll" style="width:'.$AG->params['frame_width'].'px; height:'.$AG->params['frame_height'].'px">';
$html .= '<!-- ======================= Admiror Gallery -->
<div id="AdmirorGallery'.$AG->getGalleryID().'" class="ag_reseter">
  <div class="AdmirorOverscrollGallery">';
//disables new image tag
$AG->params['newImageTag']=false;
foreach ($AG->images as $imagesKey => $imageName)
{
// Loads values into $AG->imageInfo array for target image
$AG->getImageInfo($imageName);
$html .= '
    <table border="0" cellspacing="0" cellpadding="0" width="100%" class="ag_overscroll_item">
    <tbody>
    <tr><td class="ag_overscroll_thumbTd">
    <span class="ag_thumb'.$AG->params['template'].'">';
    $html.= $AG->writePopupThumb($imageName);
    $html .='</span></td><td class="ag_overscroll_info">
    <table border="0" cellspacing="0" cellpadding="0">
    <tbody>
    <tr><td class="ag_overscroll_description">
    '.$AG->writeDescription($imageName).'
    <tr><td class="ag_overscroll_imageStat">
    <span>W:'.$AG->imageInfo["width"].'px</span>
    <span>H:'.$AG->imageInfo["height"].'px</span>
    <span>S:'.$AG->imageInfo["size"].'</span>
    </td></tr>
    </td></tr></tbody></table>
    </td></tr></tbody></table>';
}
$html .='
    </div>
  </div>
</div>
<script type="text/javascript">
jQuery(function($) {
  $(".ag_overscroll_bodyHolder").overscroll({showThumbs: true, closedCursor:"'.$AG->pluginPath.$AG->currTemplateRoot.'scripts/closed.cur",openedCursor : "'.$AG->pluginPath.$AG->currTemplateRoot.'scripts/opened.cur"});
});
jQuery(function($) {
  $(".ag_overscroll_bodyHolder").overscroll({showThumbs: true, closedCursor:"'.$AG->pluginPath.$AG->currTemplateRoot.'scripts/closed.cur",openedCursor : "'.$AG->pluginPath.$AG->currTemplateRoot.'scripts/opened.cur"});
});
</script>
';
// Loads scripts needed for Popups, after gallery is created
$html.=$AG->endPopup();
?>
