<?php
// Joomla security code
defined('_JEXEC') or die('Restricted access');

$AG->loadCSS($AG->currTemplateRoot.'css/jd.gallery.css');
$AG->loadJS($AG->currTemplateRoot.'scripts/mootools.namespaced.js');
$AG->loadJS($AG->currTemplateRoot.'scripts/jd.gallery.namespaced.js');

// Form HTML code
$html = '<!-- ======================= Admiror Gallery -->
<div class="ag_reseter" id="AG_'.$AG->getGalleryID().'">';
foreach ($AG->images as $imagesKey => $imageValue){
 $html.='<div class="imageElement"> <h3>'.$AG->writeDescription($imageValue).'</h3>
  <a href="'.$AG->imagesFolderPath.$imageValue.'" title="" class="open"></a>
'.$AG->writeImage($imageValue,'full').'
'.$AG->writeThumb($imageValue,'thumbnail').'
  </div>';
}
$html .='</div>
<script type="text/javascript">
			function startGallery() {
				var myGallery = new gallery(Moo.$(\'AG_'.$AG->getGalleryID().'\'), {
					timed: false
				});
			}
			window.onDomReady(startGallery);
		</script>
<style type="text/css">
#AG_'.$AG->getGalleryID().'
{
	width: 460px;
	height: 345px;
	z-index:5;
	display: none;
	border: 1px solid #000;
}
</style>';
?>
