<?php

$ag->addJavaScript('/plugins/content/AdmirorGallery/templates/'.$ag->params['galleryStyle'].'/scripts/jquery.overscroll.js');
$ag->addJavaScript('/plugins/content/AdmirorGallery/templates/'.$ag->params['galleryStyle'].'/scripts/jquery.disableTextSelect.js');
$ag->addCSS('/plugins/content/AdmirorGallery/templates/'.$ag->params['galleryStyle'].'/listed.css');

$html = '';
$html.='
<div class="ag_overscroll_bodyHolder ag_overscroll" style="width:'.$ag->params['frame_width'].'px; height:'.$ag->params['frame_height'].'px">
';

$html .= '<!-- ======================= Admiror Gallery -->
<div id="AdmirorGallery'.$galleryCount.'_'.$ag->params['galleryStyle'].''.$articleID.'">
  <div class="AdmirorOverscrollGallery">
';
$popup->imgWrapS = '<span class="ag_overscroll_thumbSpan">';
$popup->imgWrapE = '</span>';
//disables new image tag
$ag->params['newImageTag']=false;
foreach ($ag->images as $imagesKey => $imageValue)
{
// Calculate $listed_imageSize
$imageInfo_array=agHelper::ag_imageInfo(JPATH_BASE .DS.$ag->params['rootFolder'].$ag->imagesFolderName.'/'.$imageValue);
$html .= '
    <table border="0" cellspacing="0" cellpadding="0" width="100%" class="ag_overscroll_item">
    <tbody>
    <tr><td class="ag_overscroll_thumbTd">
    <span class="ag_thumb'.$ag->params['galleryStyle'].'">';
    $html.= $ag->generatePopupHTML($popup,$imageValue);

    $html .='</span></td><td class="ag_overscroll_info">
    <table border="0" cellspacing="0" cellpadding="0">
    <tbody>
    <tr><td class="ag_overscroll_description">
    '.$ag->getDescription($imageValue).'
    <tr><td class="ag_overscroll_imageStat">
    <span>W:'.$imageInfo_array["width"].'px</span>
    <span>H:'.$imageInfo_array["height"].'px</span>
    <span>S:'.agHelper::ag_fileRoundSize($imageInfo_array['size']).'</span>
    </td></tr>
    </td></tr></tbody></table>
    </td></tr></tbody></table>';
}

$html .='
    </div>
  </div>
</div>
';
$html .= '
<script type="text/javascript">

jQuery(function($) {
  jQuery(".ag_overscroll_bodyHolder").overscroll();
});

</script>
';
$html.=$popup->jsInclude;
?>
