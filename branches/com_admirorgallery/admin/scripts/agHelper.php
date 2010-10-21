<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of agHelper
 *
 * @author Nikola Vasiljevski
 */
class agHelper {

function ag_shrinkString($string,$stringLength,$add3dots){
     if(strlen($string)>$stringLength){
	  $string = substr($string,0,$stringLength);
	  if($add3dots){
	       $string.="...";
	  }
     }
     return $string;
}

//Creates thumbnail from original images, return $errorMessage;
function ag_createComponentThumb($original_file,$new_h) {

	//GD check
	if (!function_exists('gd_info')) {
	    // ERROR - Invalid image
	    return JText::_('GD support is not enabled');
	}

	// Create src_img
	if (preg_match("/jpg|jpeg/i", $original_file)) {
	    @$src_img = imagecreatefromjpeg($original_file);
	} else if (preg_match("/png/i", $original_file)) {
	    @$src_img = imagecreatefrompng($original_file);
	} else if (preg_match("/gif/i", $original_file)) {
	    @$src_img = imagecreatefromgif($original_file);
	} else {
	    return JText::sprintf('Unsupported image type for image',$original_file);
	}

	@$src_width = imageSX($src_img);//$src_width
	@$src_height = imageSY($src_img);//$src_height
        $src_w=$src_width;
        $src_h=$src_height;
        $src_x=0;
        $src_y=0;
	$dst_w = $new_w;
	$dst_h = $new_h;
	$src_ratio=$src_w/$src_h;	
	$dst_ratio=$new_w/$new_h;


	  $dst_w = $dst_h*$src_ratio;

$dst_img=ImageCreate($dst_w, $dst_h);
        @$dst_img = imagecreatetruecolor($dst_w, $dst_h);

	// PNG THUMBS WITH ALPHA PATCH
        if (preg_match("/png/i", $original_file)) {
        // Turn off alpha blending and set alpha flag
            imagealphablending($dst_img, false);
            imagesavealpha($dst_img, true);
        }
        
        @imagecopyresampled($dst_img, $src_img, 0, 0, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);

        if (preg_match("/jpg|jpeg/i", $original_file)) {
            return imagejpeg($dst_img);
        } else if (preg_match("/png/i", $original_file)) {
            return imagepng($dst_img);
        } else if (preg_match("/gif/i", $original_file)) {
            return imagegif($dst_img);
        } else {
            return JText::sprintf('Could not create thumbnail file for image',$original_file);
        }
        @imagedestroy($dst_img);
        @imagedestroy($src_img);
	  
}

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

function ag_imageInfo($imageURLPHP){

  list($width, $height, $type, $attr) = getimagesize($imageURLPHP);

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
          "size" => agHelper::ag_fileRoundSize(filesize($imageURLPHP))
      );

  }

}
}
?>
