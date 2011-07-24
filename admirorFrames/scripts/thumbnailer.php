<?php

if($_GET['src_file'] == "")exit;

$src_file = urldecode($_GET['src_file']);
$bgcolor = $_GET['bgcolor'];
$colorize = $_GET['colorize'];
$ratio = $_GET['ratio'];

if(!function_exists('imagefilter')){
    function imagefilter($source, $var, $arg1 = null, $arg2 = null, $arg3 = null){

        $max_y = imagesy($source);
        $max_x = imagesx($source); 

        $x = 0;
        while($x<$max_x){
           $y = 0;
           while($y<$max_y){
               $rgb = imagecolorat($source, $x, $y);
               $r = (($rgb >> 16) & 0xFF) + $arg1;
               $g = (($rgb >> 8) & 0xFF) + $arg2;
               $b = ($rgb & 0xFF) + $arg3;
               $a = $rgb >> 24;
               $r = ($r > 255)? 255 : (($r < 0)? 0:$r);
               $g = ($g > 255)? 255 : (($g < 0)? 0:$g);
               $b = ($b > 255)? 255 : (($b < 0)? 0:$b);
               $new_pxl = imagecolorallocatealpha($source, $r, $g, $b, $a);
               if ($new_pxl == false){
                   $new_pxl = imagecolorclosestalpha($source, $r, $g, $b, $a);
               }
               imagesetpixel($source,$x,$y,$new_pxl);
               ++$y;
               }
           ++$x;
        }
        return true;

    }
}

//GD check
if (!function_exists('gd_info')) {
    // ERROR - Invalid image
    return JText::_('GD support is not enabled');
}

// Create src_img
if (preg_match("/png/i", $src_file)) {
    @$src_img = imagecreatefrompng($src_file);
    if(!empty($colorize)){   
        $AF_colorize_RGB = array(
                base_convert(substr($colorize, 0, 2), 16, 10),
                base_convert(substr($colorize, 2, 2), 16, 10),
                base_convert(substr($colorize, 4, 2), 16, 10)
                ); 
        imagefilter($src_img, IMG_FILTER_COLORIZE, $AF_colorize_RGB[0], $AF_colorize_RGB[1], $AF_colorize_RGB[2]); // Need Bundled GD
    }
} else {
    return JText::sprintf('Unsupported image type for image', $src_file);
}

$src_w = imageSX($src_img); //$src_width
$src_h = imageSY($src_img); //$src_height
$src_x = 0;
$src_y = 0;
$dst_w = round($src_w * $ratio);
$dst_h = round($src_h * $ratio);
if($dst_w<4)$dst_w=4;
if($dst_h<4)$dst_h=4;


@$dst_img = imagecreatetruecolor($dst_w, $dst_h);

$AF_bgcolor_RGB = array(
            base_convert(substr($bgcolor, 0, 2), 16, 10),
            base_convert(substr($bgcolor, 2, 2), 16, 10),
            base_convert(substr($bgcolor, 4, 2), 16, 10)
            );
                      
$AF_BGCOLOR = imagecolorallocate($dst_img, $AF_bgcolor_RGB[0], $AF_bgcolor_RGB[1], $AF_bgcolor_RGB[2]);

imagefill($dst_img, 0, 0, $AF_BGCOLOR);

@imagecopyresampled($dst_img, $src_img, 0, 0, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);

@imagejpeg($dst_img, NULL, 96);

@imagedestroy($dst_img);
@imagedestroy($src_img);

?>
