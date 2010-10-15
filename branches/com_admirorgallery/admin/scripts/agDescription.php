<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of agDescription
 *
 * @author Nikola Vasiljevski
 */
class agDescription {

function ag_fileRoundSize($size) {
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
}
?>
