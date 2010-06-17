<?php
$doc->addScript($joomla_site_path.'/plugins/content/AdmirorGallery/templates/galleria/jquery.galleria.js');
//$doc->addScriptDeclaration('jQuery.noConflict();');
$doc->addStyleSheet($joomla_site_path.'/plugins/content/AdmirorGallery/templates/galleria/galleria.css');
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

<style type="text/css">
#main_image
{
  width:'.$_frame_width_.'px;
  height:'.$_frame_height_.'px;
}

.AdmirorGallery'.$galleryCount.'
{
  width:'.$_frame_width_.'px;
  display:block;
}
</style>


<div class="AdmirorGallery'.$galleryCount.'-'.$articleID.'">';
if (!empty($images)){
	foreach ($images as $imagesKey => $imagesValue){
            $html.='<div id="main_image"></div>';
            break;
        }
}
$html.='<ul class="ag_galleria'.$galleryCount.'">';
$activTag = 'class="active"';
if (!empty($images)){
	foreach ($images as $imagesKey => $imagesValue){

                $html .='<li '.$activTag.'><a href="'.$joomla_site_path.$rootFolder.$imagesFolder_name.'/'.$imagesValue.'" title="'.$imagesDescritions[$imagesValue].'"><img src="'.$joomla_site_path.'/plugins/content/AdmirorGallery/thumbs/'.$imagesFolder_name.'/'.$imagesValue.'" alt="'.$imagesDescritions[$imagesValue].'"></a></li>';
		$activTag='';
	}
}

$html .='</ul>';

$html .='<p class="nav"><a onclick="jQuery.galleria.prev(); return false;" href="#">« previous</a> | <a onclick="jQuery.galleria.next(); return false;" href="#">next »</a></p>';

if($_showSignature_==1){
  $html .='<div class="AdmirorGallery_label">';
}else{
  $html .='<div style="display:none">';
}

$html .='<a href="http://www.admiror-design-studio.com" class="AdmirorGallery_linkAdmiror"><span>Admiror</span></a><a href="http://www.vasiljevski.com" class="AdmirorGallery_linkGallery"><span>Gallery</span></a>';
$html .='</div>';

$html.='<script type="text/javascript">
jQuery(function($) {

		jQuery(".ag_galleria'.$galleryCount.'").addClass("gallery_small"); // adds new class name to maintain degradability

		jQuery("ul.ag_galleria'.$galleryCount.'").galleria({
			history   : false, // activates the history object for bookmarking, back-button etc.
			clickNext : true, // helper for making the image clickable
			insert    : "#main_image", // the containing selector for our main image
			onImage   : function(image,caption,thumb) { // let"s add some image effects for demonstration purposes

				// fade in the image & caption
				if(! (jQuery.browser.mozilla && navigator.appVersion.indexOf("Win")!=-1) ) { // FF/Win fades large images terribly slow
					image.css("display","none").fadeIn(1000);
				}
				caption.css("display","none").fadeIn(1000);

				// fetch the thumbnail container
				var _li = thumb.parents("li");

				// fade out inactive thumbnail
				_li.siblings().children("img.selected").fadeTo(500,0.3);

				// fade in active thumbnail
				thumb.fadeTo("fast",1).addClass("selected");

				// add a title for the clickable image
				image.attr("title","Next image >>");
			},
			onThumb : function(thumb) { // thumbnail effects goes here

				// fetch the thumbnail container
				var _li = thumb.parents("li");

				// if thumbnail is active, fade all the way.
				var _fadeTo = _li.is(".active") ? "1" : "0.3";

				// fade in the thumbnail when finnished loading
				thumb.css({display:"none",opacity:_fadeTo}).fadeIn(1500);

				// hover effects
				thumb.hover(
					function() { thumb.fadeTo("fast",1); },
					function() { _li.not(".active").children("img").fadeTo("fast",0.3); } //
				)
			}
		});
	});
</script>';
$html .='</div>';
?>
