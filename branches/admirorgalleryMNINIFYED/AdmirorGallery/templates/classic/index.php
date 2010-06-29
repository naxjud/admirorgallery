<?php
defined('_JEXEC') or die('Restricted access');
$doc->addStyleSheet($joomla_site_path.'/plugins/content/AdmirorGallery/templates/classic/template.css');
// Form HTML code
$html = '<!-- ======================= Admiror Gallery -->
<div id="AdmirorGallery'.$galleryCount.'('.$_galleryStyle_.''.$articleID.')">
<table class="AdmirorGallery" cellspacing="0" cellpadding="0" border="0">
<tbody>
<tr>
<td>';
if (!empty($images)){
	foreach ($images as $imagesKey => $imagesValue){
		$html .= '<span class="ag_thumb'.$_galleryStyle_.'">';
			include (JPATH_BASE.DS.'plugins/content/AdmirorGallery/imageHTMLout.php');
		$html.='</span>';
	}
}

$html .='
</td>
</tr>
</tbody>
</table>
</div>
';
if (isset($jsInclude)) 
$html.=$jsInclude;					
?>
