<?php

$ag->addCSS('/plugins/content/AdmirorGallery/templates/'.$ag->params['galleryStyle'].'/listed.css');
$ag->addCSS('/plugins/content/AdmirorGallery/templates/'.$ag->params['galleryStyle'].'/pagination.css');
$ag->addJavaScript('/plugins/content/AdmirorGallery/templates/'.$ag->params['galleryStyle'].'/jquery.pagination.js');

// Vars
$ag_paginationNumOfItems = agGallery::ag_readInlineParam("numOfItems",$match,5);


// Form HTML code
$html = '<!-- ======================= Admiror Gallery -->
<div id="AdmirorGallery'.$galleryCount.'_'.$ag->params['galleryStyle'].'_'.$articleID.'" class="AdmirorListedGallery">

<div class="ag_paginationResults">
	This content will be replaced when pagination inits.
</div>

<div class="ag_pagination"></div><br style="clear:both" />

<div class="ag_hiddenresult" style="display:none;">

';

if(count($ag->images) < $ag_paginationNumOfItems){$ag_paginationNumOfItems = count($ag->images);}

	foreach ($ag->images as $imagesKey => $imageValue)
	{
	
	// Calculate $listed_imageSize
	$imageInfo_array=agHelper::ag_imageInfo(JPATH_BASE .DS.$ag->params['rootFolder'].$ag->imagesFolderName.'/'.$imageValue);
	
		$html .= '<div class="ag_paginationItem">

<table border="0" cellspacing="0" cellpadding="0" width="100%" class="ag_item">
<tbody>
<tr>
<td class="ag_thumbTd">
<span class="ag_thumb'.$ag->params['galleryStyle'].'">';
$popup->imgWrapS = '<span class="ag_thumbSpan">';
$popup->imgWrapE = '</span>';
$html.= $ag->generatePopupHTML($popup,$imageValue);
$html .='</span>
</td>
<td class="ag_info">
	<table border="0" cellspacing="0" cellpadding="0">
		<tbody>
		<tr>
			<td class="ag_description">
			'.$ag->getDescription($imageValue).'
			</td>
		</tr>
		<tr>
			<td class="ag_imageStat">
			<span>W:'.$imageInfo_array["width"].'px</span>
			<span>H:'.$imageInfo_array["height"].'px</span>
			<span>S:'.agHelper::ag_fileRoundSize($imageInfo_array['size']).'</span>
			</td>
		</tr>
		</tbody>
	</table>
</td>
</tr>
</tbody>
</table>

</div>';
}

$html .='</div></div>';

$html .='
<script type="text/javascript">        
jQuery(document).ready(function() {  

	var ag_num_of_items_'.$galleryCount.'_'.$ag->params['galleryStyle'].'_'.$articleID.' = '.$ag_paginationNumOfItems.'; // <================ SET NUMBER OF ITEMS PER PAGE
	var ag_num_entries_'.$galleryCount.'_'.$ag->params['galleryStyle'].'_'.$articleID.' = jQuery("#AdmirorGallery'.$galleryCount.'_'.$ag->params['galleryStyle'].'_'.$articleID.'").find(".ag_paginationItem").length;

	//alert('.$galleryCount.'+":"+ag_num_entries_'.$galleryCount.'_'.$ag->params['galleryStyle'].'_'.$articleID.');

	function pageselectCallback_'.$galleryCount.'_'.$ag->params['galleryStyle'].'_'.$articleID.'(page_index, jq){
	    jQuery("#AdmirorGallery'.$galleryCount.'_'.$ag->params['galleryStyle'].'_'.$articleID.'").find(".ag_paginationResults").empty();
	    for(var i=page_index*ag_num_of_items_'.$galleryCount.'_'.$ag->params['galleryStyle'].'_'.$articleID.';i<page_index*ag_num_of_items_'.$galleryCount.'_'.$ag->params['galleryStyle'].'_'.$articleID.'+ag_num_of_items_'.$galleryCount.'_'.$ag->params['galleryStyle'].'_'.$articleID.';i++)
	    {
	      jQuery("#AdmirorGallery'.$galleryCount.'_'.$ag->params['galleryStyle'].'_'.$articleID.'").find(".ag_paginationResults").append(jQuery("#AdmirorGallery'.$galleryCount.'_'.$ag->params['galleryStyle'].'_'.$articleID.'").find(".ag_hiddenresult .ag_paginationItem:eq("+i+")").clone());
	    }
		'.$popup->initCode.'
	    return false;
	}

	jQuery("#AdmirorGallery'.$galleryCount.'_'.$ag->params['galleryStyle'].'_'.$articleID.'").find(".ag_pagination").pagination(ag_num_entries_'.$galleryCount.'_'.$ag->params['galleryStyle'].'_'.$articleID.', {
	num_edge_entries: 2,
	num_display_entries: 8,
	items_per_page:ag_num_of_items_'.$galleryCount.'_'.$ag->params['galleryStyle'].'_'.$articleID.',
	callback: pageselectCallback_'.$galleryCount.'_'.$ag->params['galleryStyle'].'_'.$articleID.'
	});  
});
</script>

<style type="text/css">

div#AdmirorGallery'.$galleryCount.'_'.$ag->params['galleryStyle'].'_'.$articleID.' .ag_paginationResults
{
	height:'.(($ag_paginationNumOfItems*($ag->params['th_height']+30))+20).'px;
	display:block;
}

</style>

';

$html .='<!-- Admiror Gallery -->';
//if there is any extra script that needs to be put after gallery html.
$html.=$popup->jsInclude;

?>

