<?php
 
$ag->addCSS('/plugins/content/AdmirorGallery/templates/'.$ag->params['galleryStyle'].'/css/jd.gallery.css');
$ag->addJavaScript('/plugins/content/AdmirorGallery/templates/'.$ag->params['galleryStyle'].'/scripts/mootools.namespaced.js');
$ag->addJavaScript('/plugins/content/AdmirorGallery/templates/'.$ag->params['galleryStyle'].'/scripts/jd.gallery.namespaced.js');

// Form HTML code
$html = '<!-- ======================= Admiror Gallery -->
<div id="AdmirorGallery'.$galleryCount.'_'.$ag->params['galleryStyle'].'_'.$articleID.'">
';
    foreach ($ag->images as $imagesKey => $imageValue)
    {

 $html.='<div class="imageElement"> <h3>'.$ag->getDescription($imageValue).'</h3>
  <a href="'.$ag->sitePath.$ag->params['rootFolder'].$ag->imagesFolderName.'/'.$imageValue.'" title="" class="open"></a>
  <img src="'.$ag->sitePath.$ag->params['rootFolder'].$ag->imagesFolderName.'/'.$imageValue.'" class="full" />
  <img src="'.$ag->sitePath.'/plugins/content/AdmirorGallery/thumbs/'.$ag->imagesFolderName.'/'.$imageValue.'" class="thumbnail" /></div>';
    }

$html .='';
$html .='</div>
<script type="text/javascript">
			function startGallery() {
				var myGallery = new gallery(Moo.$(\'AdmirorGallery'.$galleryCount.'_'.$ag->params['galleryStyle'].'_'.$articleID.'\'), {
					timed: false
				});
			}
			window.onDomReady(startGallery);
		</script>
<style type="text/css">
#AdmirorGallery'.$galleryCount.'_'.$ag->params['galleryStyle'].'_'.$articleID.'
{
	width: 460px;
	height: 345px;
	z-index:5;
	display: none;
	border: 1px solid #000;
}
</style>';
?>
