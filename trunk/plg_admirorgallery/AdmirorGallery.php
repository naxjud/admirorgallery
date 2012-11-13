<?php
/*
 // Admiror Gallery, based on Simple Image Gallery
 // Author: Igor Kekeljevic & Nikola Vasiljevski, 2010.
 // Version: 2.0 beta
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
        $gd_exists=true;
        // just startup
        global $mainframe;
        
        // checking
        if (!preg_match("#{AdmirorGallery[^}]*}(.*?){/AdmirorGallery}#s", $row->text)) {
            return;
        }
		        // GD2 Library Check      
        if (!function_exists('gd_info')) {
            // ERROR - Invalid image
            $row->text .= _AD_NOGD;
            $gd_exists=false;
        }
        
        // Version check
        $version = new JVersion();
        if ($version->PRODUCT == "Joomla!" && $version->RELEASE != "1.5") {
            echo '<div class="message">'._AD_JV_CHECK.'</div>';
        }
        
        // Load functions
        require_once (JPATH_BASE.DS.'plugins/content/AdmirorGallery/classes/agGallery.php');
        require_once (JPATH_BASE.DS.'plugins/content/AdmirorGallery/classes/agHelper.php');

        //CreateGallerys
        if (preg_match_all("#{AdmirorGallery[^}]*}(.*?){/AdmirorGallery}#s", $row->text, $matches, PREG_PATTERN_ORDER) > 0) {
            $plugin = &JPluginHelper::getPlugin('content', 'AdmirorGallery');
            $ag = new agGallery(new JParameter($plugin->params));
            $ag->setSitePath(JURI::base());
            $ag->setThumbPath(JPATH_SITE.'/plugins/content/AdmirorGallery/thumbs/');

            agHelper::ag_cleanThumbsFolder(JPATH_SITE.$ag->params['rootFolder'], $ag->thumbsPath);

            //if any image is corrupted supresses recoverable error
            ini_set('gd.jpeg_ignore_warning', $ag->params['ignoreError']);
            if ($ag->params['ignoreAllError'])
                error_reporting('E_NOTICE');

            $ag->doc = &JFactory::getDocument();
            $ag->addCSS('/plugins/content/AdmirorGallery/AdmirorGallery.css');
            if($ag->params['loadjQuery'])
                {
                    $ag->addJavaScript('/plugins/content/AdmirorGallery/jquery.js');
                }
            if($ag->params['jQueryNoConflict'])
                {
                    $ag->addJavaScriptCode('jQuery.noConflict();');
                }
            $galleryCount = -1;
            $articleID = $row->id;

            //generate gallery html
            foreach ($matches[0] as $match) {
                $galleryCount++;
                $ag->readInlineParams($match);
                //set gallery path
                $ag->setImagesFolderName(preg_replace("/{.+?}/", "", $match));
                $ag->setImagesFolderPath(JPATH_SITE.$ag->params['rootFolder'].$ag->imagesFolderName.'/');
                
                // ERROR - Cannot find folder with images
                if (!file_exists($ag->imagesFolderPath)) {
                    $row->text .= '<div class="error">Cannot find folder "'.$ag->imagesFolderName.'" inside "'.$ag->imagesFolderPath.'" folder.</div>';
                }
                 // Create Image description array $imagesDescritions. Localization supported
                $ag->readDescriptionFiles();//include (JPATH_BASE.DS.'plugins/content/AdmirorGallery/descriptions.php');		
                $ag->setThumbPath(JPATH_SITE.'/plugins/content/AdmirorGallery/thumbs/'.$ag->imagesFolderName.'/');
                $ag->loadImageFiles();
				
                if ($gd_exists){
//                      //Create directory in thumbs for gallery
                        JFolder::create($ag->thumbsPath, 0755);
                        $row->text.=$ag->generateThumbs();
                }
                $popup = new POPUP();
		include (JPATH_BASE.DS.'plugins/content/AdmirorGallery/popup_engine/'.$ag->params['popupEngine'].'/index.php');
                include (JPATH_BASE.DS.'plugins/content/AdmirorGallery/templates/'.$ag->params['galleryStyle'].'/index.php');
                agHelper::ag_clearOldThumbs($ag->thumbsPath, $ag->imagesFolderPath);
                $row->text = preg_replace("#{AdmirorGallery[^}]*}".$ag->imagesFolderName."{/AdmirorGallery}#s", "<div style='clear:both'></div>".$html, $row->text, 1);
            }// foreach($matches[0] as $match)
			
		/* ========================= SIGNATURE ====================== */
		//
			if($ag->params['showSignature']=="1"){
				$row->text .= '<div style="display:block; font-size:10px;">';
			}else{
				$row->text .= '<div style="display:block; font-size:10px; overflow:hidden; height:1px; padding-top:1px;">';
			}
		$row->text .= '<br /><a href="http://www.admiror-design-studio.com/admiror/en/design-resources/joomla-admiror-gallery" target="_blank">AdmirorGallery</a>, created by <a href="http://www.admiror-design-studio.com" target="_blank">Kekeljevic</a> & <a href="http://www.vasiljevski.com/" target="_blank">Vasiljevski</a>.<br />';
		$row->text .= '</div>';
		//
		/* ------------------------ SIGNATURE ---------------------- */
        }//if (preg_match_all("#{AdmirorGallery}(.*?){/AdmirorGallery}#s", $row->text, $matches, PREG_PATTERN_ORDER)>0)
    }//onPrepareContent(&$row, &$params, $limitstart)
}//class plgContentadmirorGallery extends JPlugin
?>
