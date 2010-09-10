<?php
/**
 * Description of agGallery
 *
 * @author Nikola Vasiljevski
 * 11.07.2010
 */
define('PLUGIN_BASE_PATH', '/plugins/content/AdmirorGallery/');

require_once (dirname(__FILE__).'/agHelper.php');
require_once (dirname(__FILE__).'/agPopup.php');

class agGallery extends agHelper {
    var $sitePath='';
    var $sitePhysicalPath = '';
    // Virtual path. Example: "http://www.mysite.com/plugin/content/AdmirorGallery/thumbs/"
    var $thumbsFolderPath='';
    // Physical path on the server. Example: "E:\php\www\joomla/plugin/content/AdmirorGallery/thumbs/"
    var $thumbsFolderPhysicalPath='';
    // Gallery name. Example: food
    var $imagesFolderName='';
    // Physical path on the server. Example: "E:\php\www\joomla/plugin/content/"
    var $imagesFolderPhysicalPath = '';
    // Virtual path. Example: "http://www.mysite.com/images/stories/food/"
    var $imagesFolderPath='';
    var $images = array();
    var $imageInfo = array();
    var $params =  array();
    var $staticParams = array();
    var $index = -1;
    var $articleID = 0;
    var $popupEngine;
    var $currPopupRoot='';
    var $currTemplateRoot='';
    // Virtual path. Example: "http://www.mysite.com/plugins/content/AdmirorGallery/"
    var $pluginPath = '';
    private $errors = array();
    private $doc = null;
    private $descArray = array ();
    private $match = '';

    // relativ plugin path
   // const PLUGIN_BASE_PATH = '/plugins/content/AdmirorGallery/';

    //**************************************************************************
    //Template API functions                                                  //
    //**************************************************************************
    /**
     * Gets image info data, and loads it in imageInfo array. It also rounds image size.
     * @param <string> $imageName
     */
    function getImageInfo($imageName){
        $this->imageInfo = agHelper::ag_imageInfo($this->imagesFolderPhysicalPath.'/'.$imageName);
        $this->imageInfo["size"] = agHelper::ag_fileRoundSize($this->imageInfo["size"]);
    }
    /**
     * Returns gallery id formed from gallery index and article ID
     * @return <string>
     */
    function getGalleryID(){
        return $this->index.$this->articleID;
    }
    /**
     * Loads CSS file from the given path.
     * @param <string> $path
     */
    function loadCSS($path){
        $this->doc->addStyleSheet($this->sitePath.PLUGIN_BASE_PATH.$path);
    }
     /**
     * Loads JavaScript file from the given path.
     * @param <string> $path
     */
    function loadJS($path){
        $this->doc->addScript($this->sitePath.PLUGIN_BASE_PATH.$path);
    }
     /**
     * Loads JavaScript code block into document head.
     * @param <string> $script
     */
    function insertJSCode($script){
        $this->doc->addScriptDeclaration($script);
    }
    /**
     * Returns specific inline parametar if entered or returns default value
     * @param <string> $atrib
     * @param <string> $default
     * @return <value> 
     */
    function getParameter($atrib,$default){
        return $this->ag_getParams($atrib,$this->match,$default);
    }
    /**
     * Returns full image html
     * @param <string> $imageName
     * @param <string> $cssClass
     * @return <html> 
     */
    function writeImage($imageName,$cssClass=''){
        return '<img src="'.$this->imagesFolderPath.$imageName.'"
                alt="'.htmlspecialchars(strip_tags($this->descArray[$imageName])).'"
                class="'.$cssClass.'">';
    }
    /**
     * Returns thumb html
     * @param <string> $imageName
     * @param <string> $cssClass
     * @return <html> 
     */
    function writeThumb($imageName,$cssClass=''){
        return '<img src="'.$this->sitePath.PLUGIN_BASE_PATH.'thumbs/'.$this->imagesFolderName.'/'.$imageName.'"
                alt="'.htmlspecialchars(strip_tags($this->descArray[$imageName])).'"
                class="'.$cssClass.'">';
    }
    /**
     * Generates HTML with new image tag
     * @param <string> $image
     * @return <html>
     */
    function writeNewImageTag($image){
	$fileStat=stat($this->imagesFolderPhysicalPath.$image);
	$fileAge=time()-$fileStat['ctime'];
	if((int)$fileAge < (int)($this->params['newImageTag_days']*24*60*60) && $this->params['newImageTag']==1){
	  return '<span class="ag_newTag"><img src="'.$this->sitePath.PLUGIN_BASE_PATH.'newTag.gif" class="ag_newImageTag" /></span>';
	}
    }
    /**
     * Generates HTML with Popup engine integration
     * @param <string> $image
     * @return <html>
     */
    function writePopupThumb($image){
        $html='';
	if($this->popupEngine->customPopupThumb){
	    $html=$this->popupEngine->customPopupThumb;
	    $html=str_replace("{imagePath}",$this->imagesFolderPath.$image,$html);
	    $html=str_replace("{imageDescription}",htmlspecialchars(strip_tags($this->descArray[$image])),$html);
	    $html=str_replace("{className}",$this->popupEngine->className,$html);
	    $html=str_replace("{rel}",$this->popupEngine->rel,$html);
	    $html=str_replace("{customAttr}",$this->popupEngine->customTag,$html);
	    $html=str_replace("{newImageTag}",$this->writeNewImageTag($image),$html);
	    $html=str_replace("{thumbImagePath}",$this->sitePath.PLUGIN_BASE_PATH.'thumbs/'.$this->imagesFolderName.'/'.$image,$html);
	}else{
	    $html.='<a href="'.$this->imagesFolderPath.$image.'" title="'.htmlspecialchars(strip_tags($this->descArray[$image])).'" class="'.$this->popupEngine->className.'" rel="'.$this->popupEngine->rel.'" '.$this->popupEngine->customAttr.' target="_blank">';
	    $html.=$this->writeNewImageTag($image);
	    $html.='<img src="'.$this->sitePath.PLUGIN_BASE_PATH.'thumbs/'.$this->imagesFolderName.'/'.$image.'
		" alt="'.htmlspecialchars(strip_tags($this->descArray[$image])).'" class="ag_imageThumb"></a>';
	}
        return $html;
    }
    /**
     * Generates html with popup support for all the images in the gallery.
     * @return <html>
     */
    function writeAllPopupThumbs(){
        $html='';
        if (!empty($this->images)){
            foreach ($this->images as $imagesKey => $imagesValue){
                    $html.='<a href="'.$this->imagesFolderPath.$imagesValue.'" title="'.htmlspecialchars(strip_tags($this->descArray[$imagesValue])).'" class="'.$this->popupEngine->cssClass.'" rel="'.$this->popupEngine->rel.'" '.$this->popupEngine->customTag.' target="_blank">';
                    $html.=$this->writeNewImageTag($imagesValue);
                    $html.='<img src="'.$this->sitePath.PLUGIN_BASE_PATH.'thumbs/'.$this->imagesFolderName.'/'.$imagesValue.'
                        " alt="'.htmlspecialchars(strip_tags($this->descArray[$imagesValue])).'" class="ag_imageThumb"></a>';
            }
        }
        return $html;
    }
    /**
     * Returns image description. The current localization is taken into account.
     * @param <string> $imageName
     * @return <string> 
     */
    function writeDescription($imageName){
        return htmlspecialchars(strip_tags($this->descArray[$imageName]));
    }
    /*
     * Initialises Popup engine. Loads popupEngine settings and scripts
     */
    function initPopup(){
        require ('plugins/content/AdmirorGallery/popups/'.$this->params['popupEngine'].'/index.php');	
        return  $this->popupEngine->initCode;
    }
    /*
     * Includes JavaScript code ad the end of the gallery html
     */
    function endPopup(){
        return  $this->popupEngine->endCode;
    }
    /*
     * adds new error value to the error array
     */
    function addError($value){
        if($value!=''){$this->errors[]=$value;}
    }
    //**************************************************************************
    // END Template API functions                                             //
    //**************************************************************************

    //**************************************************************************
    // Gallery Functions                                                      //
    //**************************************************************************
    /**
     * Gallery initialization
     * @param <string> $match
     */
    function initGallery($match){
        $this->match = $match;
        $this->readInlineParams();
        $this->imagesFolderNameOriginal = preg_replace("/{.+?}/", "", $match);
        $this->imagesFolderName = strip_tags($this->imagesFolderNameOriginal);
        $this->imagesFolderPhysicalPath = $this->sitePhysicalPath.$this->params['rootFolder'].$this->imagesFolderName.'/';
        $this->thumbsFolderPhysicalPath = $this->sitePhysicalPath.PLUGIN_BASE_PATH.'thumbs/'.$this->imagesFolderName.'/';
        $this->imagesFolderPath = $this->sitePath.$this->params["rootFolder"].$this->imagesFolderName.'/';
        $this->readDescriptionFiles();
        $this->loadImageFiles();
        $this->currPopupRoot = 'popups/'.$this->params['popupEngine'].'/';
        $this->currTemplateRoot = 'templates/'.$this->params['template'].'/';
        $this->pluginPath = $this->sitePath.PLUGIN_BASE_PATH;
    }
    // Clears obsolete thumbnail folders
    function cleanThumbsFolder(){
        $this->ag_cleanThumbsFolder($this->imagesFolderPhysicalPath, $this->thumbsFolderPhysicalPath);
    }
    // Clears obsolete thumbnails
    function clearOldThumbs(){
        $this->ag_clearOldThumbs($this->imagesFolderPhysicalPath, $this->thumbsFolderPhysicalPath);
    }
    // Reads description files
    private function readDescriptionFiles(){
        // Create Images Array
        unset($this->descArray);

        if (file_exists($this->imagesFolderPhysicalPath))
        {
            if ($dh = opendir($this->imagesFolderPhysicalPath)) {
              while (($f = readdir($dh)) !== false) {
                      if((substr(strtolower($f),-3) == 'jpg') || (substr(strtolower($f),-4) == 'jpeg') || (substr(strtolower($f),-3) == 'gif') || (substr(strtolower($f),-3) == 'png')) {

                              // Set image name as imageDescription value, as predifined value
                              $this->descArray[$f] = $f;

                              // Set Possible Description File Apsolute Path // Instant patch for upper and lower case...
                              if(file_exists($this->imagesFolderPhysicalPath.(substr($f, 0, -3))."desc")){
                                $descriptionFileApsolutePath=$this->imagesFolderPhysicalPath.(substr($f, 0, -3))."desc";
                        }else{
                          $descriptionFileApsolutePath=$this->imagesFolderPhysicalPath.(substr($f, 0, -3))."DESC";
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
    // Loads images array, sorted as defined bu parametar.
    private function loadImageFiles(){
         $this->images = agHelper::ag_imageArrayFromFolder($this->imagesFolderPhysicalPath, $this->params['sortImages']);
    }
    //Generates thumbs, check for settings change and recreates thumbs if it needs to
    function generateThumbs(){
        //Add's index.html to thumbs folder
        if (!file_exists($this->thumbsFolderPhysicalPath.'/index.html'))
        {$this->ag_indexWrite($this->thumbsFolderPhysicalPath.'/index.html');}
        // Check for Changes
        foreach ($this->images as $imagesKey=>$imagesValue) {
                $original_file = $this->imagesFolderPhysicalPath.$imagesValue;
                $thumb_file = $this->thumbsFolderPhysicalPath.$imagesValue;
                if (!file_exists($thumb_file)) {
                        $this->addError(agHelper::ag_createThumb($this->imagesFolderPhysicalPath.$imagesValue, $thumb_file, $this->params['th_height']));
                }else{
                list($imagewidth, $imageheight) = getimagesize($thumb_file);
                if ($imageheight != $this->params['th_height']) {
                        $this->addError(agHelper::ag_createThumb($this->imagesFolderPhysicalPath.$imagesValue, $thumb_file, $this->params['th_height']));
                    }
                }
                                // ERROR - Invalid image
                if (!file_exists($thumb_file)) {
                        //$this->addError("Cannot read thumbnail");
                            $this->addError(JText::_("Cannot read thumbnail"));
                }
        }
    }
    /*
     * Returns error html
     */
    function writeErrors(){
	  $errors="";
          if (isset($this->errors)){
	  foreach($this->errors as $key => $value){
               $errors.='<div class="error">'.$value.'</div>'."\n";
	  }
          unset($this->errors);
          }
	  return $errors;
    }
     /**
     * Sets path values
     * @param <string> $path
     * @param <string> $sitePhysicalPath
     */
    function setSitePaths($path, $sitePhysicalPath){
        if (substr($path, -1) == "/")
            $path = substr($path, 0, -1);
        $this->sitePath = $path;
        $this->sitePhysicalPath = $sitePhysicalPath;
        $this->thumbsFolderPhysicalPath = $sitePhysicalPath.PLUGIN_BASE_PATH.'thumbs/';
        $this->imagesFolderPhysicalPath = $sitePhysicalPath.$this->params["rootFolder"];
    }
     /**
     * Sets document reference
     * @param <pointer> $document
     */
    function setDocument($document){
        $this->doc = $document;
    }
     // Reads inline parametar if any or sets default values
    function readInlineParams(){
        ////setting parametars for current gallery, if there is no inline params default params are set
        $this->params['template']= $this->ag_getParams("template",$this->match,$this->staticParams['template']);
        $this->params['th_height'] = $this->ag_getParams("height",$this->match,$this->staticParams['th_height']);
        $this->params['newImageTag']=$this->ag_getParams("newImageTag",$this->match,$this->staticParams['newImageTag']);
        $this->params['newImageTag_days']= $this->ag_getParams("newImageDays",$this->match,$this->staticParams['newImageTag_days']);
        $this->params['sortImages']=$this->ag_getParams("sortByDate",$this->match,$this->staticParams['sortImages']);
        $this->params['frameWidth']=$this->ag_getParams("frameWidth",$this->match,$this->staticParams['frame_width']);
        $this->params['frameHeight']=$this->ag_getParams("frameHeight",$this->match,$this->staticParams['frame_height']);
        $this->params['showSignature']=$this->ag_getParams("showSignature",$this->match,$this->staticParams['showSignature']);
        $this->params['popupEngine']=$this->ag_getParams("popupEngine",$this->match,$this->staticParams['popupEngine']);
	$this->params['foregroundColor'] = $this->ag_getParams("foregroundColor",$this->match,$this->staticParams['foregroundColor']);
	$this->params['highliteColor'] = $this->ag_getParams("highliteColor",$this->match,$this->staticParams['highliteColor']);
    }
     /**
     * Gallery constructor
     * @param <JParameter> $globalParams
     */
    function  __construct($globalParams) {
        $this->staticParams['th_height']= $globalParams->get('th_height', 200);
        $this->staticParams['template']= $globalParams->get('template', 'classic');
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
	$this->staticParams['foregroundColor']= $globalParams->get('foregroundColor','808080');
	$this->staticParams['highliteColor']= $globalParams->get('highliteColor','fea804');
        $this->popupEngine = new agPopup();
        $this->params = $this->staticParams;
        //$this->errors = new agErrors();
    }

    //**************************************************************************
    // END Gallery Functions                                                  //
    //**************************************************************************
}

?>
