<?php
$doc->addScript($joomla_site_path.'/plugins/content/AdmirorGallery/templates/galleria/galleria.js');
$doc->addScript($joomla_site_path.'/plugins/content/AdmirorGallery/templates/galleria/themes/classic/galleria.classic.js');
$doc->addStyleSheet($joomla_site_path.'/plugins/content/AdmirorGallery/templates/galleria/galleria.css');


//get parametars
$default_frame_width_ = $pluginParams->get('frame_width','500');
$default_frame_height_ = $pluginParams->get('frame_height','300');
$defaultframe_color_= $pluginParams->get('framecolor','#fff');

$inline_frame_width_ = ag_getAttribute("frameWidth",$match);
$inline_frame_height_ = ag_getAttribute("frameHeight",$match);
$inline_frame_color_ = ag_getAttribute("framecolor",$match);

if ($inline_frame_width_)
	$_frame_width_=$inline_frame_width_;
else
	$_frame_width_=$default_frame_width_;
	
if ($inline_frame_height_)
	$_frame_height_=$inline_frame_height_;
else
	$_frame_height_=$default_frame_height_;
	
if ($inline_frame_color_)
	$_frame_color_=$inline_frame_color_;
else
	$_frame_color_=$defaultframe_color_;

$_newImageTag_=0;
// Form HTML code
$html = '<div id="AdmirorGallery'.$galleryCount.'-'.$articleID.'" style="width:'.$_frame_width_.'px; height:'.$_frame_height_.'px;">';
if (!empty($images)){
	foreach ($images as $imagesKey => $imagesValue){

			include (JPATH_BASE.DS.'plugins/content/AdmirorGallery/imageHTMLout.php');
	}
}
$html .='</div>    
	<script>
    // Classic theme is now loaded using <script> instead
    // You can still use loadTheme if you like, either works.
    jQuery(\'#AdmirorGallery'.$galleryCount.'-'.$articleID.'\').galleria({
        image_crop: false,
        transition: \'fade\',
		max_scale_ratio: 1
    });
    </script>';
$html.='<style type="text/css">
.galleria-container{position:relative;overflow:hidden;background:'.$_frame_color_.';}
</style>'
?>