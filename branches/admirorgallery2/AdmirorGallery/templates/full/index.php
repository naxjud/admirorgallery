<?php
$ag->addCSS('/plugins/content/AdmirorGallery/templates/'.$ag->params['galleryStyle'].'/template.css');

// Form HTML code
$html = '
<!-- ======================= Admiror Gallery -->
<table border="0" cellpadding="0" cellspacing="0" id="fullGallery">
<tbody>
<tr>
<td style="width:'.$ag->params['frame_width'].'px; height:'.$ag->params['frame_height'].'px; background-color:black;">
<div id="slide-holder" style="width:'.$ag->params['frame_width'].'px; height:'.$ag->params['frame_height'].'px">
      <div id="slide-runner" style="width:'.$ag->params['frame_width'].'px; height:'.$ag->params['frame_height'].'px">
';

     foreach ($ag->images as $imagesKey => $imageValue)
	{
	$targetID=($imagesKey+1);
	if($targetID == count($ag->images)){$targetID=0;}
	$html .= '<a onfocus="this.blur();" onclick="slider.slide('.$targetID.');return false;" href="#">'."\n";
	 $html .= '<img id="slide-img-'.($imagesKey+1).'" src="'.$ag->sitePath.$ag->params['rootFolder'].$ag->imagesFolderName.'/'.$imageValue.'" class="slide" alt=""  style="width:'.$ag->params['frame_width'].'px; height:'.$ag->params['frame_height'].'px;"/>'."\n";
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

    <table border="0" cellspacing="0" cellpadding="0"  style="width:'.$ag->params['frame_width'].'px;">
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

foreach ($ag->images as $imagesKey => $imageValue)
{
  $html.='{"id":"slide-img-'.($imagesKey+1).'","client":"nature beauty","desc":"'. $ag->getDescription($imageValue).'"},';
}   

$html = substr_replace($html ,"",-1);
$html.='];</script>';


$ag->addJavaScript('/plugins/content/AdmirorGallery/templates/full/galleryScript.js');

?>
