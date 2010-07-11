<?php
$ag->addJavaScript('/plugins/content/AdmirorGallery/templates/'.$ag->params['galleryStyle'].'/galleria.js');
$ag->addJavaScript('/plugins/content/AdmirorGallery/templates/'.$ag->params['galleryStyle'].'/themes/classic/galleria.classic.js');
$ag->addCSS('/plugins/content/AdmirorGallery/templates/'.$ag->params['galleryStyle'].'/galleria.css');


$ag->params['newImageTag']= false;
// Form HTML code
$html = '<div id="AdmirorGallery'.$galleryCount.'-'.$articleID.'" style="width:'.$ag->params['frame_width'].'px; height:'.$ag->params['frame_height'].'px;">';
$html.= $ag->generateImagesHTML($popup);
$html.='</div>    
	<script>
    jQuery(\'#AdmirorGallery'.$galleryCount.'-'.$articleID.'\').galleria({
        image_crop: false,
        transition: \'fade\',
		max_scale_ratio: 1
    });
    </script>';
$html.='<style type="text/css">
.galleria-container{position:relative;overflow:hidden;background:#fff;}
</style>'
?>