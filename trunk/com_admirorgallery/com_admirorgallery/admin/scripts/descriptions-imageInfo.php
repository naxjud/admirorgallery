<?php

$ag_url_img = $_POST["ag_url_img"];
$ag_url_html = urldecode($_POST["ag_url_html"]);
$ag_url_php = urldecode($_POST["ag_url_php"]);

$ag_url_img_php = $ag_url_php.'/'.substr($ag_url_img,strlen($ag_url_html));

function fileRoundSize($size) {
$bytes = array('B','KB','MB','GB','TB');
  foreach($bytes as $val) {
   if($size > 1024){
    $size = $size / 1024;
   }else{
    break;
   }
  }
  return round($size, 2)." ".$val;
}

function ag_imageInfo($imageURL, $imageURLPHP){

  list($width, $height, $type, $attr) = getimagesize($imageURL);

  $types = Array(
          1 => 'GIF',
          2 => 'JPG',
          3 => 'PNG',
          4 => 'SWF',
          5 => 'PSD',
          6 => 'BMP',
          7 => 'TIFF(intel byte order)',
          8 => 'TIFF(motorola byte order)',
          9 => 'JPC',
          10 => 'JP2',
          11 => 'JPX',
          12 => 'JB2',
          13 => 'SWC',
          14 => 'IFF',
          15 => 'WBMP',
          16 => 'XBM'
      );

  if($type){
  
    return $imageInfo = array(
          "width" => $width,
          "height" => $height,
          "type" => $types[$type],
          "size" => fileRoundSize(filesize($imageURLPHP))
      );
  
  }

}



$ag_imageInfoArray = ag_imageInfo($ag_url_img, $ag_url_img_php);

foreach($ag_imageInfoArray as $ag_imageInfoArrayKey => $ag_imageInfoArrayValue){
	echo '<span class="ag_label_wrap">'.$ag_imageInfoArrayKey.':<span class="ag_label">'.$ag_imageInfoArrayValue.'</span></span>';
}

?>
