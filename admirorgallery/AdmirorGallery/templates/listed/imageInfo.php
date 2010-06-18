<?php

////  IMAGEINFO  /////////////////////////////////////////////////////////////
//                                                                          //
// Last Update: 06.12.2008.                                                 //
//                                                                          //
// FUNCTION INPUT:                                                          //
//    1. - $imageURL                                                        //
//                                                                          //
// FUNCTION OUTPUT:                                                         //
//    1. - $imageInfo array:"width","height","type","size"                  //
//                                                                          //
//                                                                          //
// Copyright: Igor Kekeljevic, 2008.                                        //
//                                                                          //
//////////////////////////////////////////////////////////////////////////////

function imageInfo($imageURL){

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
          "size" => filesize($imageURL)
      );
  
  }

}

?>