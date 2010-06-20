<?php
/*
 // Admiror Gallery, based on Simple Image Gallery
 // Author: Igor Kekeljevic & Nikola Vasiljevski, 2010.
 // Version: 1.95
 */

defined('_JEXEC') or die('Restricted access');

// Import library dependencies
jimport('joomla.event.plugin');
jimport('joomla.plugin.plugin');

// Simple Language Defines for English
// <--------------- English language Defines ------------------------------------->
define('_AD_NOGD', '<div class="error">GD support is not enabled. Cannot create thumbnail images. Contact your System Administrator to enable GD support.</div>');
define('_AD_JV_CHECK', '<b>Error</b>: Admiror Designe Studio "Admiror Gallery (1.6)" Plugin functions only under Joomla! 1.5');
// <--------------- END --------------------------------------------------------->

class plgContentAdmirorGallery extends JPlugin {
    
    //Constructor
    function plgContentadmirorGallery(&$subject) {
        parent::__construct($subject);
        // load plugin parameters
        $this->_plugin = JPluginHelper::getPlugin('content', 'AdmirorGallery');
        $this->_params = new JParameter($this->_plugin->params);
    }
    function onPrepareContent(&$row, &$params, $limitstart) {
        
        // just startup
        global $mainframe;
        // GD2 Library Check      
        if (!function_exists('gd_info')) {
            // ERROR - Invalid image
            $row->text .= _AD_NOGD;
        }
        
        // checking
        if (!preg_match("#{AdmirorGallery[^}]*}(.*?){/AdmirorGallery}#s", $row->text)) {
            return;
        }
        
        $plugin = &JPluginHelper::getPlugin('content', 'AdmirorGallery');
        $pluginParams = new JParameter($plugin->params);
        
        // Load functions
        require_once (JPATH_BASE.DS.'plugins/content/AdmirorGallery/functions.php');

        
        // Default parameters
        $default_height_ = $pluginParams->get('th_height', 200);
        $default_galleryStyle_ = $pluginParams->get('galleryStyle', 'classic');
        $default_newImageTag_ = $pluginParams->get('newImageTag', '1');
        $default_newImageTag_days_ = $pluginParams->get('newImageTag_days', '7');
        $default_sortImages = $pluginParams->get('sortImages', 'false');
        $default_showSignature_ = $pluginParams->get('showSignature', '1');
		$default_overlayEngine_=$pluginParams->get('overlayEngine','slimbox');
        $ignoreError_ = $pluginParams->get('ignoreError', '1');
        $ignoreAllError_ = $pluginParams->get('ignoreAllError', '0');
        $loadjQuery_=$pluginParams->get('loadjQuery', '1');
        $jQueryNoConflict_=$pluginParams->get('jQueryNoConflict', '1');
        $rootFolder=$pluginParams->get('rootFolder', '/images/stories/');
        //if any image is corrupted supresses recoverable error
        ini_set('gd.jpeg_ignore_warning', $ignoreError_);
        if ($ignoreAllError_)
            error_reporting('E_NOTICE');
        
        // Version check
        $version = new JVersion();
        if ($version->PRODUCT == "Joomla!" && $version->RELEASE != "1.5") {
            echo '<div class="message">'._AD_JV_CHECK.'</div>';
        }
        
        // Paths
        $joomla_site_path = JURI::base();
        if (substr($joomla_site_path, -1) == "/")
            $joomla_site_path = substr($joomla_site_path, 0, -1);
        //$rootFolder = '/images/stories/';
        $thumbsFolder = JPATH_SITE.'/plugins/content/AdmirorGallery/thumbs/';
        
        ag_cleanThumbsFolder(JPATH_SITE.$rootFolder, $thumbsFolder);
        //CreateGallerys
        if (preg_match_all("#{AdmirorGallery[^}]*}(.*?){/AdmirorGallery}#s", $row->text, $matches, PREG_PATTERN_ORDER) > 0) {
            $doc = &JFactory::getDocument();
            $doc->addStyleSheet($joomla_site_path.'/plugins/content/AdmirorGallery/AdmirorGallery.css');
            if($loadjQuery_)
                {
                    $doc->addScript($joomla_site_path.'/plugins/content/AdmirorGallery/jquery.js');
                }
            if($jQueryNoConflict_)
                {
                    $doc->addScriptDeclaration('jQuery.noConflict();');
                }
            $galleryCount = -1;
			$articleID = $row->id;
            preg_match_all("#{AdmirorGallery (.*?)}#s", $row->text, $inlineParams, PREG_PATTERN_ORDER);
            foreach ($matches[0] as $match) {
                $galleryCount++;
                include (JPATH_BASE.DS.'plugins/content/AdmirorGallery/getGalleryParams.php');
                //get gallery path
                $imagesFolder_name = preg_replace("/{.+?}/", "", $match);
                $imagesFolder = JPATH_SITE.$rootFolder.$imagesFolder_name.'/';
                
                // ERROR - Cannot find folder with images
                if (!file_exists($imagesFolder)) {
                    $row->text .= '<div class="error">Cannot find folder "'.$imagesFolder_name.'" inside "images/stories/" folder.</div>';
                }
                 // Create Image description array $imagesDescritions. Localization supported
                include (JPATH_BASE.DS.'plugins/content/AdmirorGallery/descriptions.php');   
				
                $thumbsFolder = JPATH_SITE.'/plugins/content/AdmirorGallery/thumbs/'.$imagesFolder_name.'/';
                unset($images);
                
                $images = ag_imageArrayFromFolder($imagesFolder, $_sortImages);
                
                if ($images == null) {
                    ag_clearOldThumbs($thumbsFolder, $imagesFolder);
                    return;
                }
                //Create directory in thumbs for gallery
                JFolder::create($thumbsFolder, 0755);
				//Add's index.html to thumbs folder
				if (!file_exists($thumbsFolder.'/index.html'))
				{ag_indexWrite($thumbsFolder.'/index.html');}
                // Check for Changes
	            foreach ($images as $imagesKey=>$imagesValue) {
	                $original_file = $imagesFolder.$imagesValue;
	                $thumb_file = $thumbsFolder.$imagesValue;
	              	list($imagewidth, $imageheight) = getimagesize($original_file);
	                if ((!file_exists($thumb_file)) OR ($imageheight != $_height_)) {
	                    $row->text .= ag_createThumb($imagesFolder.$imagesValue, $thumb_file, $_height_);
	                }
	                
	                // ERROR - Invalid image
	                if (!file_exists($thumb_file)) {
	                    $row->text .= '<div class="error">Cannot read thumbnail for image "'.$imagesValue.'".</div>';
	                }
	            
	            	}
				include (JPATH_BASE.DS.'plugins/content/AdmirorGallery/overlay_engine/'.$_overlayEngine_.'/index.php');
                include (JPATH_BASE.DS.'plugins/content/AdmirorGallery/templates/'.$_galleryStyle_.'/index.php');
                ag_clearOldThumbs($thumbsFolder, $imagesFolder);
                $row->text = preg_replace("#{AdmirorGallery[^}]*}".$imagesFolder_name."{/AdmirorGallery}#s", "<div style='clear:both'></div>".$html, $row->text, 1);
            }// foreach($matches[0] as $match)
        }//if (preg_match_all("#{AdmirorGallery}(.*?){/AdmirorGallery}#s", $row->text, $matches, PREG_PATTERN_ORDER)>0)
    }//onPrepareContent(&$row, &$params, $limitstart)
}//class plgContentadmirorGallery extends JPlugin
?>
