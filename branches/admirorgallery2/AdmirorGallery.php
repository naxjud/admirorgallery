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
define('_AD_JV_CHECK', '<b>Error</b>: Admiror Designe Studio "Admiror Gallery" Plugin functions only under Joomla! 1.5');
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
            $AG = new agGallery(new JParameter($plugin->params));
            $AG->setSitePath(JURI::base());
            $AG->setThumbPath(JPATH_SITE.'/plugins/content/AdmirorGallery/thumbs/');

            agHelper::ag_cleanThumbsFolder(JPATH_SITE.$AG->params['rootFolder'], $AG->thumbsPath);

            //if any image is corrupted supresses recoverable error
            ini_set('gd.jpeg_ignore_warning', $AG->params['ignoreError']);
            if ($AG->params['ignoreAllError'])
                error_reporting('E_NOTICE');

            $AG->doc = &JFactory::getDocument();
            $AG->loadCSS('AdmirorGallery.css');
            if($AG->params['loadjQuery'])
                {
                    $AG->loadJS('jquery.js');
                }
            if($AG->params['jQueryNoConflict'])
                {
                    $AG->insertJSCode('jQuery.noConflict();');
                }
            $AG->index  = -1;
            $AG->articleID = $row->id;
            //generate gallery html
            foreach ($matches[0] as $match) {
                $AG->index++;
                $AG->readInlineParams($match);
                //set gallery path
                $AG->setImagesFolderName(preg_replace("/{.+?}/", "", $match));
                $AG->setImagesFolderPath(JPATH_SITE.$AG->params['rootFolder'].$AG->imagesFolderName.'/');
                
                // ERROR - Cannot find folder with images
                if (!file_exists($AG->imagesFolderPath)) {
                    $row->text .= '<div class="error">Cannot find folder "'.$AG->imagesFolderName.'" inside "'.$AG->imagesFolderPath.'" folder.</div>';
                }
                 // Create Image description array $imagesDescritions. Localization supported
                $AG->readDescriptionFiles();//include (JPATH_BASE.DS.'plugins/content/AdmirorGallery/descriptions.php');
                $AG->setThumbPath(JPATH_SITE.'/plugins/content/AdmirorGallery/thumbs/'.$AG->imagesFolderName.'/');
                $AG->loadImageFiles();
				
                if ($gd_exists){
//                      //Create directory in thumbs for gallery
                        JFolder::create($AG->thumbsPath, 0755);
                        $row->text.=$AG->generateThumbs();
                }
		include (JPATH_BASE.DS.'plugins/content/AdmirorGallery/popup_engine/'.$AG->params['popupEngine'].'/index.php');
                include (JPATH_BASE.DS.'plugins/content/AdmirorGallery/templates/'.$AG->params['galleryStyle'].'/index.php');
                agHelper::ag_clearOldThumbs($AG->thumbsPath, $AG->imagesFolderPath);
                $row->text = preg_replace("#{AdmirorGallery[^}]*}".$AG->imagesFolderName."{/AdmirorGallery}#s", "<div style='clear:both'></div>".$html, $row->text, 1);
            }// foreach($matches[0] as $match)
			
            /* ========================= SIGNATURE ====================== */
            //
            if($AG->params['showSignature']){
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