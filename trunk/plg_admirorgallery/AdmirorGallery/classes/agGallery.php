<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of agGallery
 *
 * @author Nikola Vasiljevski
 * 11.07.2010
 */
class agGallery {
    var $sitePath='';
    var $thumbsPath='';
    var $imagesFolderName='';
    var $imagesFolderPath = '';
    var $descArray = array ();
    var $images = array();
    var $params =  array();
    var $staticParams = array();
    var $doc = null;

    /**
     *
     * @param <string> $path
     */
    function setSitePath($path){
        if (substr($path, -1) == "/")
            $path = substr($path, 0, -1);
        $this->sitePath = $path;
    }
    /**
     *
     * @param <string> $path
     */
    function setThumbPath($path){
        $this->thumbsPath = $path;
    }
    /**
     *
     * @param <string> $name 
     */
    function setImagesFolderName($name){
        $this->imagesFolderName = $name;
    }
    /**
     *
     * @param <string> $path
     */
    function setImagesFolderPath($path){
        $this->imagesFolderPath  = $path;
    }
    /**
     *
     * @param <JParameter> $globalParams */
    function  __construct($globalParams) {
        $this->staticParams['th_height']= $globalParams->get('th_height', 200);
        $this->staticParams['galleryStyle']= $globalParams->get('galleryStyle', 'classic');
        $this->staticParams['newImageTag']= $globalParams->get('newImageTag', true);
        $this->staticParams['newImageTag_days']= $globalParams->get('newImageTag_days', '7');
        $this->staticParams['sortImages']= $globalParams->get('sortImages', false);
        $this->staticParams['frame_width']= $globalParams->get('frame_width', false);
        $this->staticParams['frame_height']= $globalParams->get('frame_height', false);
        $this->staticParams['showSignature']= $globalParams->get('showSignature', '1');
        $this->staticParams['popupEngine']= $globalParams->get('popupEngine', 'slimbox');
        $this->staticParams['usePopuEngine']= $globalParams->get('usePopuEngine', true);
        $this->staticParams['ignoreError']= $globalParams->get('ignoreError', true);
        $this->staticParams['ignoreAllError']= $globalParams->get('ignoreAllError', false);
        $this->staticParams['loadjQuery']= $globalParams->get('loadjQuery', true);
        $this->staticParams['jQueryNoConflict']= $globalParams->get('jQueryNoConflict', true);
        $this->staticParams['rootFolder']= $globalParams->get('rootFolder','/images/stories/');
        $this->params = $this->staticParams;
    }
    /**
     *
     * @param <array> $match 
     */
    function readInlineParams($match){
        ////setting parametars for current gallery, if there is no inline params default params are set
        $this->params['galleryStyle']= $this->ag_getParams("template",$match,$this->staticParams['galleryStyle']);
        $this->params['th_height'] = $this->ag_getParams("height",$match,$this->staticParams['th_height']);
        $this->params['newImageTag']=$this->ag_getParams("newImageTag",$match,$this->staticParams['newImageTag']);
        $this->params['newImageTag_days']= $this->ag_getParams("newImageDays",$match,$this->staticParams['newImageTag_days']);
        $this->params['sortImages']=$this->ag_getParams("sortByDate",$match,$this->staticParams['sortImages']);
        $this->params['frame_width']=$this->ag_getParams("frameWidth",$match,$this->staticParams['frame_width']);
        $this->params['frame_height']=$this->ag_getParams("frameHeight",$match,$this->staticParams['frame_height']);
        $this->params['showSignature']=$this->ag_getParams("showSignature",$match,$this->staticParams['showSignature']);
        $this->params['popupEngine']=$this->ag_getParams("popupEngine",$match,$this->staticParams['popupEngine']);
    }
    //Gets the atributes value by name, else returns false
    private function ag_getParams($attrib, $tag, $default){
            //get attribute from html tag
            $re = '/' . preg_quote($attrib) . '=([\'"])?((?(1).+?|[^\s>]+))(?(1)\1)/is';
            if (preg_match($re, $tag, $match)) {
            return urldecode($match[2]);
            }
            return $default;
    }
    /**
     *
     * @param <string> $atrib
     * @param <string> $match
     * @param <string> $default
     * @return <value> 
     */
    function readInlineParam($atrib,$match,$default){
        return agGallery::ag_getParams($atrib,$match,$default);
    }
    /**
     * Reads description files
     */
    function readDescriptionFiles(){
        // Create Images Array
        unset($this->descArray);

        if (file_exists($this->imagesFolderPath))
        {
            if ($dh = opendir($this->imagesFolderPath)) {
              while (($f = readdir($dh)) !== false) {
                      if((substr(strtolower($f),-3) == 'jpg') || (substr(strtolower($f),-4) == 'jpeg') || (substr(strtolower($f),-3) == 'gif') || (substr(strtolower($f),-3) == 'png')) {

                              // Set image name as imageDescription value, as predifined value
                              $this->descArray[$f] = $f;

                              // Set Possible Description File Apsolute Path // Instant patch for upper and lower case...
                              if(file_exists($this->imagesFolderPath.(substr($f, 0, -3))."desc")){
                                $descriptionFileApsolutePath=$this->imagesFolderPath.(substr($f, 0, -3))."desc";
                        }else{
                          $descriptionFileApsolutePath=$this->imagesFolderPath.(substr($f, 0, -3))."DESC";
                        }

                      if(file_exists($descriptionFileApsolutePath)){// Check is descriptions file exists

                            // Read Description File Content
                            $descriptionFileContent='';
                            $file=fopen($descriptionFileApsolutePath,"r");
                            while (!feof($file))
                              {
                              $descriptionFileContent.=fgetc($file);
                              }
                            fclose($file);

                            $descriptionFileContent=str_replace("\n","<br>",$descriptionFileContent);


                            $langTag="default";
                              if(stripos($descriptionFileContent, "{".$langTag."}") !== false && stripos($descriptionFileContent, "{/".$langTag."}") !== false){// If none lang encoding match found extract default tag and place it as imageDescription value
                                    $from="{".$langTag."}";
                                    $to="{/".$langTag."}";
                              $content=$descriptionFileContent;
                              $content=stristr($content, $from);
                              $content=substr($content,strlen($from),strpos($content, $to)-(strlen($to)-1));
                              $this->descArray[$f]=$content;
                            }

                                    $lang =& JFactory::getLanguage();
                                    $langTag=strtolower($lang->getTag());

                                    if(stripos($descriptionFileContent, "{".$langTag."}") !== false && stripos($descriptionFileContent, "{/".$langTag."}") !== false){// Extract part of text which suits to tag for language and place it as imageDescription value
                                      $from="{".$langTag."}";
                                      $to="{/".$langTag."}";
                              $content=$descriptionFileContent;
                              $content=stristr($content, $from);
                              $content=substr($content,strlen($from),strpos($content, $to)-(strlen($to)-1));
                              $this->descArray[$f]=$content;
                            }

                      }// if(file_exists($descriptionFileApsolutePath))


                      }// if((substr(strtolower($f),-3) == 'jpg') || (substr(strtolower($f),-3) == 'gif') || (substr(strtolower($f),-3) == 'png'))
              }// while (($f = readdir($dh)) !== false)

              closedir($dh);

            }// if ($dh = opendir($galleryApsolutePath))
        }
        else
        $this->descArray=null;

    }

    function loadImageFiles(){
         $this->images = agHelper::ag_imageArrayFromFolder($this->imagesFolderPath, $this->params['sortImages']);
    }
    /**
     * Generates thumbs, check for settings change and recreates thumbs if it needs to
     */
    function generateThumbs(){
        //Add's index.html to thumbs folder
        if (!file_exists($this->thumbsPath.'/index.html'))
        {$this->ag_indexWrite($this->thumbsPath.'/index.html');}
        // Check for Changes
        $ERROR = '';
        foreach ($this->images as $imagesKey=>$imagesValue) {
                $original_file = $this->imagesFolderPath.$imagesValue;
                $thumb_file = $this->thumbsPath.$imagesValue;
                list($imagewidth, $imageheight) = getimagesize($thumb_file);
                if ((!file_exists($thumb_file)) || ($imageheight != $this->params['th_height'])) {
                        $ERROR= $this->ag_createThumb($this->imagesFolderPath.$imagesValue, $thumb_file, $this->params['th_height']);
                }
                // ERROR - Invalid image
                if (!file_exists($thumb_file)) {
                        $ERROR.= '<div class="error">Cannot read thumbnail for image "'.$imagesValue.'".</div>';
                }

        }
        return $ERROR;
    }
    //Creates thumbnail from original images, return $errorMessage;
    function ag_createThumb($original_file, $thumb_file, $new_h) {

        $errorMessage = '';

        if (preg_match("/jpg|jpeg/i", $original_file)) {
            @$src_img = imagecreatefromjpeg($original_file);
        } else if (preg_match("/png/i", $original_file)) {
            @$src_img = imagecreatefrompng($original_file);
        } else if (preg_match("/gif/i", $original_file)) {
            @$src_img = imagecreatefromgif($original_file);
        } else {
            return '<div class="error">Unsupported image type for image "'.$original_file.'". </div>';
        }
        @$old_x = imageSX($src_img);
        @$old_y = imageSY($src_img);

        @$thumb_w = $old_x * ($new_h / $old_y);
        @$thumb_h = $new_h;

        if($thumb_w==0 || $thumb_h==0){
            return '<div class="error">Image "'.$original_file.'" is missing or not valid. Cannot read this image.</div>';
        }

        @$dst_img = imagecreatetruecolor($thumb_w, $thumb_h);
		
        // PNG THUMBS WITH ALPHA PATCH
        if (preg_match("/png/i", $original_file)) {
        // Turn off alpha blending and set alpha flag
            imagealphablending($dst_img, false);
            imagesavealpha($dst_img, true);
        }

        @imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $thumb_w, $thumb_h, $old_x, $old_y);

        if (preg_match("/jpg|jpeg/i", $original_file)) {
            @imagejpeg($dst_img, $thumb_file);
        } else if (preg_match("/png/i", $original_file)) {
            @imagepng($dst_img, $thumb_file);
        } else if (preg_match("/gif/i", $original_file)) {
            @imagegif($dst_img, $thumb_file);
        } else {
            return '<div class="error">Could not create thumbnail file for image "'.$original_file.'"! </div>';
        }
        @imagedestroy($dst_img);
        @imagedestroy($src_img);
        return $errorMessage;
    }
    /**
     *
     * @param <string> $filename 
     */
    function ag_indexWrite($filename){
      $handle = fopen($filename,"w") or die("");
      $contents = fwrite($handle,'<html><body bgcolor="#FFFFFF"></body></html>');
      fclose($handle);
    }
    /**
     *
     * @param <popup.base> $popupSettings
     */
    function generatePopupHTML($popupSettings,$image){
        $html='';
        $html.='<a href="'.$this->sitePath.$this->params['rootFolder'].$this->imagesFolderName.'/'.$image.'" title="'.htmlspecialchars(strip_tags($this->descArray[$image])).'" class="'.$popupSettings->cssClass.'" rel="'.$popupSettings->rel.'" '.$popupSettings->customTag.' target="_blank">'.$popupSettings->imgWrapS;
        $fileStat=stat($this->imagesFolderPath.$image);
        $fileAge=time()-$fileStat['ctime'];
        if((int)$fileAge < (int)($this->params['newImageTag_days']*24*60*60) && $this->params['newImageTag']==1){
        $html .= '<span class="ag_newTag"><img src="'.$this->sitePath.'/plugins/content/AdmirorGallery/newTag.gif" class="ag_newImageTag" /></span>';
        }
        $html.='<img src="'.$this->sitePath.'/plugins/content/AdmirorGallery/thumbs/'.$this->imagesFolderName.'/'.$image.'
            " alt="'.htmlspecialchars(strip_tags($this->descArray[$image])).'" class="ag_imageThumb">'.$popupSettings->imgWrapE.'</a>';
        return $html;
    }
    function generateImagesHTML($popupSettings){
        $html='';
        if (!empty($this->images)){
            foreach ($this->images as $imagesKey => $imagesValue){
                    $html.='<a href="'.$this->sitePath.$this->params['rootFolder'].$this->imagesFolderName.'/'.$imagesValue.'" title="'.htmlspecialchars(strip_tags($this->descArray[$imagesValue])).'" class="'.$popupSettings->cssClass.'" rel="'.$popupSettings->rel.'" '.$popupSettings->customTag.' target="_blank">'.$popupSettings->imgWrapS;
                    $fileStat=stat($this->imagesFolderPath.$imagesValue);
                    $fileAge=time()-$fileStat['ctime'];
                    if((int)$fileAge < (int)($this->params['newImageTag_days']*24*60*60) && $this->params['newImageTag']==1){
                    $html .= '<span class="ag_newTag"><img src="'.$this->sitePath.'/plugins/content/AdmirorGallery/newTag.gif" class="ag_newImageTag" /></span>';
                    }
                    $html.='<img src="'.$this->sitePath.'/plugins/content/AdmirorGallery/thumbs/'.$this->imagesFolderName.'/'.$imagesValue.'
                        " alt="'.htmlspecialchars(strip_tags($this->descArray[$imagesValue])).'" class="ag_imageThumb">'.$popupSettings->imgWrapE.'</a>';
            }
        }
        return $html;
    }
    function ThumbHTML($image,$cssClass=''){
        return '<img src="'.$this->sitePath.'/plugins/content/AdmirorGallery/thumbs/'.$this->imagesFolderName.'/'.$image.'"
                alt="'.htmlspecialchars(strip_tags($this->descArray[$image])).'"
                class="'.$cssClass.'">';
    }
    function getDescription($image){
        return $this->descArray[$image];
    }
    function addCSS($path){
        $this->doc->addStyleSheet($this->sitePath.$path);
    }
    function addJavaScript($path){
        $this->doc->addScript($this->sitePath.$path);
    }
    function addJavaScriptCode($script){
        $this->doc->addScriptDeclaration($script);
    }

}
/**
 *
 */
class POPUP{
        var $customTag='';
        var $rel='';
        var $cssClass='';
        var $jsInclude = '';
        var $imgWrapS = '';
        var $imgWrapE='';
        var $initCode='';
}

?>
