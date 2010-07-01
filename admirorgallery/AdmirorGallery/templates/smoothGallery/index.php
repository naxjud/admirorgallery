<?php
 
$doc->addStyleSheet($joomla_site_path.'/plugins/content/AdmirorGallery/templates/smoothGallery/css/jd.gallery.css');
$doc->addScript($joomla_site_path.'/plugins/content/AdmirorGallery/templates/smoothGallery/scripts/mootools.namespaced.js');
$doc->addScript($joomla_site_path.'/plugins/content/AdmirorGallery/templates/smoothGallery/scripts/jd.gallery.namespaced.js');

// Form HTML code
$html = '<!-- ======================= Admiror Gallery -->
<div id="AdmirorGallery'.$galleryCount.'_'.$_galleryStyle_.'_'.$articleID.'">
';


isset($customTag) ? $tempTag=$customTag : $tempTag='';
if (!empty($images))
{
	foreach ($images as $imagesKey => $imagesValue)
	{
	
     $html.='<div class="imageElement"> <h3>'.$imagesDescritions[$imagesValue].'</h3>
      <a href="'.$joomla_site_path.$rootFolder.$imagesFolder_name.'/'.$imagesValue.'" title="" class="open"></a>
      <img src="'.$joomla_site_path.$rootFolder.$imagesFolder_name.'/'.$imagesValue.'" class="full" />
      <img src="'.$joomla_site_path.'/plugins/content/AdmirorGallery/thumbs/'.$imagesFolder_name.'/'.$imagesValue.'" class="thumbnail" /></div>';	
	}			

}

$html .='';
$html .='</div>
<script type="text/javascript">
			function startGallery() {
				var myGallery = new gallery(Moo.$(\'AdmirorGallery'.$galleryCount.'_'.$_galleryStyle_.'_'.$articleID.'\'), {
					timed: false
				});
			}
			window.onDomReady(startGallery);
		</script>
<style type="text/css">
#AdmirorGallery'.$galleryCount.'_'.$_galleryStyle_.'_'.$articleID.'
{
	width: 460px;
	height: 345px;
	z-index:5;
	display: none;
	border: 1px solid #000;
}
</style>';
//if there is any extra script that needs to be put after gallery html.
if (isset($jsInclude)) 
$html.=$jsInclude;
?>
