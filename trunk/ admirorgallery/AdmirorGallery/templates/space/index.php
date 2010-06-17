<?php
$doc->addStyleSheet($joomla_site_path.'/plugins/content/AdmirorGallery/templates/space/space.css');
$doc->addScript($joomla_site_path.'/plugins/content/AdmirorGallery/templates/space/eye.js');
$doc->addScript($joomla_site_path.'/plugins/content/AdmirorGallery/templates/space/utils.js');
$doc->addScript($joomla_site_path.'/plugins/content/AdmirorGallery/templates/space/spacegallery.js');
//$doc->addScriptDeclaration('');

//get parametars
$default_frame_width_ = $pluginParams->get('frame_width','500');
$default_frame_height_ = $pluginParams->get('frame_height','300');
$defaultframe_color_=$pluginParams->get('framecolor','blue');

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

// Form HTML code
$html = '<!-- ======================= Admiror Gallery -->
<div class="AdmirorGallery'.$galleryCount.'('.$_galleryStyle_.')">
<style type="text/css">
    #myGallery'.$galleryCount.' {
        width: '.$_frame_width_.'px;
        height: '.$_frame_height_.'px;
    }
    #myGallery'.$galleryCount.' img {
    }
    a.loading {
        background: #fff url(ajax_small.gif) no-repeat center;
    }
</style>
<div id="myGallery'.$galleryCount.'" class="spacegallery">
';

if (!empty($images)){
	foreach ($images as $imagesKey => $imagesValue){
           // if ($_Space_useThumbs_)
           // {
           //     $html .='<img src="'.$joomla_site_path.'/plugins/content/AdmirorGallery/thumbs/'.$imagesFolder_name.'/'.$imagesValue.'" alt="'.$imagesDescritions[$imagesValue].'">';//
           // }
           // else
            //{
                $html .='<img src="'.$joomla_site_path.$rootFolder.$imagesFolder_name.'/'.$imagesValue.'" alt="'.$imagesDescritions[$imagesValue].'" title="'.$imagesDescritions[$imagesValue].'">';//'<img src="'.$joomla_site_path.'/plugins/content/AdmirorGallery/thumbs/'.$imagesFolder_name.'/'.$imagesValue.'" alt="'.$imagesDescritions[$imagesValue].'">';//
            //}
	
	}
}

$html .='</div>';
if($_showSignature_==1){
  $html .='<div class="AdmirorGallery_label">';
}else{
  $html .='<div style="display:none">';
}
$html .='<a href="http://www.admiror-design-studio.com" class="AdmirorGallery_linkAdmiror"><span>Admiror</span></a><a href="http://www.vasiljevski.com" class="AdmirorGallery_linkGallery"><span>Gallery</span></a>';
$html .='</div>';
$html .='<script type="text/javascript">
    
    //var space'.$galleryCount.' =
    jQuery("#myGallery'.$galleryCount.'").spacegallery({loadingClass: "loading"});
   // jQuery(document).keypress(function(e) {
   //   switch(e.keyCode) {
         // User pressed "up" arrow
   //      case 38:
   //      {

  //          jQuery("#myGallery'.$galleryCount.'").last().click();
  //          return false;
  //       }
 //        break;
  //   }
 //  });

</script>
';
$html .='</div>';
?>
