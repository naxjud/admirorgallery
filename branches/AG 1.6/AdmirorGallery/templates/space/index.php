<?php

// Joomla security code
defined('_JEXEC') or die('Restricted access');

$AG->loadCSS($AG->currTemplateRoot.'space.css');
$AG->loadJS($AG->currTemplateRoot.'eye.js');
$AG->loadJS($AG->currTemplateRoot.'utils.js');
$AG->loadJS($AG->currTemplateRoot.'spacegallery.js');

// Form HTML code
$html = '<!-- ======================= Admiror Gallery -->
<div class="AG_'.$AG->getGalleryID().'">
<style type="text/css">
    #myGallery'.$AG->getGalleryID().' {
        width: '.$AG->params['frame_width'].'px;
        height: '.$AG->params['frame_height'].'px;
    }
    #myGallery'.$AG->getGalleryID().' img {
    }
    a.loading {
        background: #fff url(ajax_small.gif) no-repeat center;
    }
</style>
<div id="myGallery'.$AG->getGalleryID().'" class="spacegallery">';
foreach ($AG->images as $imagesKey => $imageValue){
        $html .= $AG->writeImage($imageValue);
}
$html .='</div>';
$html .='<script type="text/javascript">
    jQuery("#myGallery'.$AG->getGalleryID().'").spacegallery({loadingClass: "loading"});
</script>
';
$html .='</div>';
?>
