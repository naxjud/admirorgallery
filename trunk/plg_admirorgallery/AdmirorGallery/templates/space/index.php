<?php
$ag->addCSS('/plugins/content/AdmirorGallery/templates/'.$ag->params['galleryStyle'].'/space.css');
$ag->addJavaScript('/plugins/content/AdmirorGallery/templates/'.$ag->params['galleryStyle'].'/eye.js');
$ag->addJavaScript('/plugins/content/AdmirorGallery/templates/'.$ag->params['galleryStyle'].'/utils.js');
$ag->addJavaScript('/plugins/content/AdmirorGallery/templates/'.$ag->params['galleryStyle'].'/spacegallery.js');

// Form HTML code
$html = '<!-- ======================= Admiror Gallery -->
<div class="AdmirorGallery'.$galleryCount.'_'.$ag->params['galleryStyle'].'_'.$articleID.'">
<style type="text/css">
    #myGallery'.$galleryCount.' {
        width: '.$ag->params['frame_width'].'px;
        height: '.$ag->params['frame_height'].'px;
    }
    #myGallery'.$galleryCount.' img {
    }
    a.loading {
        background: #fff url(ajax_small.gif) no-repeat center;
    }
</style>
<div id="myGallery'.$galleryCount.'" class="spacegallery">
';

foreach ($ag->images as $imagesKey => $imageValue){
        $html .='<img src="'.$ag->sitePath.$ag->params['rootFolder'].$ag->imagesFolderName.'/'.$imageValue.'" alt="'.htmlspecialchars(strip_tags($ag->getDescription($imageValue))).'" title="'.htmlspecialchars(strip_tags($ag->getDescription($imageValue))).'">';
}

$html .='</div>';
$html .='<script type="text/javascript">
    jQuery("#myGallery'.$galleryCount.'").spacegallery({loadingClass: "loading"});
</script>
';
$html .='</div>';
?>
