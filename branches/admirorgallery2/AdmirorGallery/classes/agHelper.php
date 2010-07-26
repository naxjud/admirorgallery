<?php
/* 
 */

/**
 * Description of agHelper
 *
 * @author Nikola Vasiljevski
 * 11.07.2010
 */
class agHelper{
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

    protected function ag_imageInfo($imageURL){

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
    protected function ag_fileRoundSize($size) {
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
    //Read's all floders in folder.
    protected function ag_foldersArrayFromFolder($targetFolder){

            unset($folders);

            if (!file_exists($targetFolder))
            {
                    return null;
            }
            $folders = array();

            if ($dh = opendir($targetFolder)) {
              while (($f = readdir($dh)) !== false) {
                      if(is_dir($targetFolder.$f) && $f!="." && $f!="..") {
                              $folders[] = $f;
                      }
              }
              return $folders;
             }else{
                    return null;
             }

             closedir($dh);
    }
    protected function ag_cleanThumbsFolder($originalFolder,$thumbFolder){
            $origin= agHelper::ag_foldersArrayFromFolder($originalFolder);
            $thumbs= agHelper::ag_foldersArrayFromFolder($thumbFolder);
            $diffArray= array_diff($thumbs,$origin);
            if ($diffArray!=null)
            {
                    foreach ($diffArray as $diffFolder) {
                             agHelper::ag_sureRemoveDir($thumbFolder.$diffFolder,true);
                    }
            }
    }
    protected function ag_clearOldThumbs($imagesFolder,$thumbsFolder){

      // Generate array of thumbs
      $targetFolder=$thumbsFolder;
      $thumbs=agHelper::ag_imageArrayFromFolder($targetFolder,0);

      // Generate array of images
      $targetFolder=$imagesFolder;
      $images=agHelper::ag_imageArrayFromFolder($targetFolder,0);

      if (empty($images)){
      agHelper::ag_sureRemoveDir($thumbsFolder, 1);
      return;
      }

      // Locate and delete old thumbs
      if(!empty($thumbs)){
        foreach ($thumbs as $thumbsKey => $thumbsValue){
          if ((!in_array($thumbsValue, $images)) && (!empty($thumbsValue)) && file_exists($thumbsFolder.$thumbsValue)) {
             unlink($thumbsFolder.$thumbsValue);
          }
        }
      }
    }
        /**
     * Makes directory, returns TRUE if exists or made
     *
     * @param string $pathname The directory path.
     * @return boolean returns TRUE if exists or made or FALSE on failure.
     */
    protected function ag_mkdir_recursive($pathname, $mode){
        is_dir(dirname($pathname)) || agHelper::ag_mkdir_recursive(dirname($pathname), $mode);
        return is_dir($pathname) || @mkdir($pathname, $mode);
    }

    protected function ag_sureRemoveDir($dir, $DeleteMe) {
        if(!$dh = @opendir($dir)) return;
        while (false !== ($obj = readdir($dh))) {
            if($obj=='.' || $obj=='..') continue;
            if (!@unlink($dir.'/'.$obj)) agHelper::ag_sureRemoveDir($dir.'/'.$obj, true);
        }

        closedir($dh);
        if ($DeleteMe){
            @rmdir($dir);
        }
    }
    //Read's all images from folder.
    protected function ag_imageArrayFromFolder($targetFolder,$sort){
        if (!file_exists($targetFolder))
        {
                return null;
        }
        if ($dh = opendir($targetFolder)) {
          while (($f = readdir($dh)) !== false) {
                  if((substr(strtolower($f),-3) == 'jpg') || (substr(strtolower($f),-4) == 'jpeg') || (substr(strtolower($f),-3) == 'gif') || (substr(strtolower($f),-3) == 'png')) {
                          $images[] = $f;
                  }
          }
        if (isset($images))
        {
              if ($sort)
              {
                      for($i=0;$i<count($images);$i++)
                      {
                            $imagesPath[$i]=$targetFolder.$images[$i];
                      }
                      array_multisort(
                                    array_map( 'filectime', $imagesPath ),
                                    SORT_NUMERIC,
                                    SORT_DESC,
                                    $images
                            );
                      unset($imagesPath);
              }
              else
                            array_multisort($images, SORT_ASC, SORT_REGULAR);
        return $images;
        }
        else
        {
            return null;
        }
         closedir($dh);
        }
    }
        //Gets the atributes value by name, else returns false
    protected function ag_getParams($attrib, $tag, $default){
            //get attribute from html tag
            $re = '/' . preg_quote($attrib) . '=([\'"])?((?(1).+?|[^\s>]+))(?(1)\1)/is';
            if (preg_match($re, $tag, $match)) {
            return urldecode($match[2]);
            }
            return $default;
    }

    //Creates thumbnail from original images, return $errorMessage;
    protected function ag_createThumb($original_file, $thumb_file, $new_h) {
        //GD check
        if (!function_exists('gd_info')) {
            // ERROR - Invalid image
            return JText::_('GD support is not enabled');
        }
        if (preg_match("/jpg|jpeg/i", $original_file)) {
            @$src_img = imagecreatefromjpeg($original_file);
        } else if (preg_match("/png/i", $original_file)) {
            @$src_img = imagecreatefrompng($original_file);
        } else if (preg_match("/gif/i", $original_file)) {
            @$src_img = imagecreatefromgif($original_file);
        } else {
            return JText::sprintf('Unsupported image type for image',$original_file);
        }
        @$old_x = imageSX($src_img);
        @$old_y = imageSY($src_img);

        @$thumb_w = $old_x * ($new_h / $old_y);
        @$thumb_h = $new_h;

        if($thumb_w==0 || $thumb_h==0){
            return JText::sprintf('Image is missing or not valid. Cannot read this image',$original_file);
        }

        @$dst_img = imagecreatetruecolor($thumb_w, $thumb_h);

        @imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $thumb_w, $thumb_h, $old_x, $old_y);

        if (preg_match("/jpg|jpeg/i", $original_file)) {
            @imagejpeg($dst_img, $thumb_file);
        } else if (preg_match("/png/i", $original_file)) {
            @imagepng($dst_img, $thumb_file);
        } else if (preg_match("/gif/i", $original_file)) {
            @imagegif($dst_img, $thumb_file);
        } else {
            return JText::sprintf('Could not create thumbnail file for image',$original_file);
        }
        @imagedestroy($dst_img);
        @imagedestroy($src_img);
    }
    /**
     *
     * @param <string> $filename
     */
    protected function ag_indexWrite($filename){
      $handle = fopen($filename,"w") or die("");
      $contents = fwrite($handle,'<html><body bgcolor="#FFFFFF"></body></html>');
      fclose($handle);
    }
}
?>
