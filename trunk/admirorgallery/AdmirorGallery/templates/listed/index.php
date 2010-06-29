<?php
// Load Functions
if(!function_exists("imageInfo")){
  include(JPATH_BASE .DS.'/plugins/content/AdmirorGallery/imageInfo.php');
}
if(!function_exists("fileRoundSize")){
  include(JPATH_BASE .DS.'/plugins/content/AdmirorGallery/fileRoundSize.php');
}

$doc->addStyleSheet($joomla_site_path.'/plugins/content/AdmirorGallery/templates/listed/listed.css');
$doc->addStyleSheet($joomla_site_path.'/plugins/content/AdmirorGallery/templates/listed/pagination.css');
$doc->addScript($joomla_site_path.'/plugins/content/AdmirorGallery/templates/listed/jquery.pagination.js');

// Vars
$ag_paginationNumOfItems = 5;

$ag_paginationNumOfItems = ag_getParams("numOfItems",$match,$ag_paginationNumOfItems);


// Form HTML code
$html = '<!-- ======================= Admiror Gallery -->
<div id="AdmirorGallery'.$galleryCount.'_'.$_galleryStyle_.'_'.$articleID.'" class="AdmirorListedGallery">

<div class="ag_paginationResults">
	This content will be replaced when pagination inits.
</div>

<div class="ag_pagination"></div><br style="clear:both" />

<div class="ag_hiddenresult" style="display:none;">

';
isset($customTag) ? $tempTag=$customTag : $tempTag='';
if (!empty($images))
{

if(count($images) < $ag_paginationNumOfItems){$ag_paginationNumOfItems = count($images);}

	foreach ($images as $imagesKey => $imagesValue)
	{
	
	// Calculate $listed_imageSize
	$imageInfo_array=imageInfo(JPATH_BASE .DS.$rootFolder.$imagesFolder_name.'/'.$imagesValue);	
	
		$html .= '<div class="ag_paginationItem">

<table border="0" cellspacing="0" cellpadding="0" width="100%" class="ag_item">
<tbody>
<tr>
<td class="ag_thumbTd">
<span class="ag_thumb'.$_galleryStyle_.'">';
$imgWrapS = '<span class="ag_thumbSpan">';
$imgWrapE = '</span>';
include (JPATH_BASE.DS.'plugins/content/AdmirorGallery/imageHTMLout.php');			
$html .='</span>
</td>
<td class="ag_info">
	<table border="0" cellspacing="0" cellpadding="0">
		<tbody>
		<tr>
			<td class="ag_description">
			'.$imagesDescritions[$imagesValue].'
			</td>
		</tr>
		<tr>
			<td class="ag_imageStat">
			<span>W:'.$imageInfo_array["width"].'px</span>
			<span>H:'.$imageInfo_array["height"].'px</span>
			<span>S:'.fileRoundSize($imageInfo_array['size']).'</span>
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
}

$html .='</div></div>';

$html .='
<script type="text/javascript">        
jQuery(document).ready(function() {  

	var ag_num_of_items_'.$galleryCount.'_'.$_galleryStyle_.'_'.$articleID.' = '.$ag_paginationNumOfItems.'; // <================ SET NUMBER OF ITEMS PER PAGE
	var ag_num_entries_'.$galleryCount.'_'.$_galleryStyle_.'_'.$articleID.' = jQuery("#AdmirorGallery'.$galleryCount.'_'.$_galleryStyle_.'_'.$articleID.'").find(".ag_paginationItem").length;

	//alert('.$galleryCount.'+":"+ag_num_entries_'.$galleryCount.'_'.$_galleryStyle_.'_'.$articleID.');

	function pageselectCallback_'.$galleryCount.'_'.$_galleryStyle_.'_'.$articleID.'(page_index, jq){  
	    jQuery("#AdmirorGallery'.$galleryCount.'_'.$_galleryStyle_.'_'.$articleID.'").find(".ag_paginationResults").empty();    
	    for(var i=page_index*ag_num_of_items_'.$galleryCount.'_'.$_galleryStyle_.'_'.$articleID.';i<page_index*ag_num_of_items_'.$galleryCount.'_'.$_galleryStyle_.'_'.$articleID.'+ag_num_of_items_'.$galleryCount.'_'.$_galleryStyle_.'_'.$articleID.';i++)
	    {
	      jQuery("#AdmirorGallery'.$galleryCount.'_'.$_galleryStyle_.'_'.$articleID.'").find(".ag_paginationResults").append(jQuery("#AdmirorGallery'.$galleryCount.'_'.$_galleryStyle_.'_'.$articleID.'").find(".ag_hiddenresult .ag_paginationItem:eq("+i+")").clone());
	    }               
	    return false;
	}

	jQuery("#AdmirorGallery'.$galleryCount.'_'.$_galleryStyle_.'_'.$articleID.'").find(".ag_pagination").pagination(ag_num_entries_'.$galleryCount.'_'.$_galleryStyle_.'_'.$articleID.', {
	num_edge_entries: 2,
	num_display_entries: 8,
	items_per_page:ag_num_of_items_'.$galleryCount.'_'.$_galleryStyle_.'_'.$articleID.',
	callback: pageselectCallback_'.$galleryCount.'_'.$_galleryStyle_.'_'.$articleID.'
	});  

});
</script>

<style type="text/css">

div#AdmirorGallery'.$galleryCount.'_'.$_galleryStyle_.'_'.$articleID.' .ag_paginationResults
{
	height:'.(($ag_paginationNumOfItems*($_height_+30))+20).'px;
	display:block;
}

</style>

';

$html .='<!-- Admiror Gallery -->';

if (isset($jsInclude)) 
$html.=$jsInclude;	

?>

