<?php

$galleryID=str_replace(array('-','/'),"",$imagesFolder_name);
//$galleryID=str_replace("/","",$imagesFolder_name);
$doc->addStyleSheet($joomla_site_path.'/plugins/content/AdmirorGallery/templates/full/template.css');

//get parametars
$default_frame_width_ = $pluginParams->get('frame_width','500');
$default_frame_height_ = $pluginParams->get('frame_height','300');

$inline_frame_width_ = ag_getAttribute("frameWidth",$match);
$inline_frame_height_ = ag_getAttribute("frameHeight",$match);

if ($inline_frame_width_)
	$_frame_width_=$inline_frame_width_;
else
	$_frame_width_=$default_frame_width_;
	
if ($inline_frame_height_)
	$_frame_height_=$inline_frame_height_;
else
	$_frame_height_=$default_frame_height_;
	
// Form HTML code
$html = '
<!-- ======================= Admiror Gallery -->
<table border="0" cellpadding="0" cellspacing="0" id="fullGallery">
<tbody>
<tr>
<td style="width:'.$_frame_width_.'px; height:'.$_frame_height_.'px; background-color:black;">
<div id="slide-holder" style="width:'.$_frame_width_.'px; height:'.$_frame_height_.'px">
      <div id="slide-runner" style="width:'.$_frame_width_.'px; height:'.$_frame_height_.'px">
';

     foreach ($images as $imagesKey => $imagesValue)
	{
	$targetID=($imagesKey+1);
	if($targetID == count($images)){$targetID=0;} 
	$html .= '<a onfocus="this.blur();" onclick="slider.slide('.$targetID.');return false;" href="#">'."\n";
	 $html .= '<img id="slide-img-'.($imagesKey+1).'" src="'.$joomla_site_path.$rootFolder.$imagesFolder_name.'/'.$imagesValue.'" class="slide" alt=""  style="width:'.$_frame_width_.'px; height:'.$_frame_height_.'px;"/>'."\n";
	 $html .= '</a>'."\n";
	}	
	
$html.='
    </div>
  </div>
</td>
</tr>
';	
		
$html.='
  <tr>
    <td id="slide-controls">
  
  <!-- Controls -->

    <table border="0" cellspacing="0" cellpadding="0"  style="width:'.$_frame_width_.'px;">
    <tbody>
      <tr>
        <td width="70%" align="left"><p id="slide-desc" class="text"></p></td>
        <td align="right"><p id="slide-nav"></p></td>
      </tr>
    </tbody>
    </table>
    
    </td>
    </tr>
  </tbody>
</table>

';

$html.='<script type="text/javascript">

if(!window.slider) var slider={};slider.data=[';

foreach ($images as $imagesKey => $imagesValue)
{
  $html.='{"id":"slide-img-'.($imagesKey+1).'","client":"nature beauty","desc":"'. $imagesDescritions[$imagesValue].'"},';
}   

$html = substr_replace($html ,"",-1);
$html.='];</script>';


$doc->addScript($joomla_site_path.'/plugins/content/AdmirorGallery/templates/full/galleryScript.js');

?>
