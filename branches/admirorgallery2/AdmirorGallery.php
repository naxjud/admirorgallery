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
// Language Defines for English
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
        // Load gallery class php script
        require_once (dirname(__FILE__).'/AdmirorGallery/classes/agGallery.php');
        //CreateGallerys
        if (preg_match_all("#{AdmirorGallery[^}]*}(.*?){/AdmirorGallery}#s", $row->text, $matches, PREG_PATTERN_ORDER) > 0) {
            $plugin = &JPluginHelper::getPlugin('content', 'AdmirorGallery');
            $AG = new agGallery(new JParameter($plugin->params));
            $AG->setSitePaths(JURI::base(),JPATH_SITE);
            $AG->cleanThumbsFolder();
            //if any image is corrupted supresses recoverable error
            ini_set('gd.jpeg_ignore_warning', $AG->params['ignoreError']);
            if ($AG->params['ignoreAllError'])
                error_reporting('E_NOTICE');
            //Joomla specific variables is passed as parametars for agGallery independce from specific CMS
            $doc = &JFactory::getDocument();
            $AG->setDocument($doc);
            $AG->loadCSS('AdmirorGallery.css');
            if($AG->params['loadjQuery']){$AG->loadJS('jquery.js');}
            if($AG->params['jQueryNoConflict']){$AG->insertJSCode('jQuery.noConflict();');}
            $AG->index  = -1;
            $AG->articleID = $row->id;
            //generate gallery html
            foreach ($matches[0] as $match) {
                $AG->index++;
                $AG->initGallery($match);// match = ;
                // ERROR - Cannot find folder with images
                if (!file_exists($AG->imagesFolderPhysicalPath)) {
                    $row->text .= '<div class="error">Cannot find folder "'.$AG->imagesFolderName.'" inside "'.$AG->imagesFolderPhysicalPath.'" folder.</div>';
                }			
                if ($gd_exists){
//                      //Create directory in thumbs for gallery
                        JFolder::create($AG->thumbsFolderPhysicalPath, 0755);
                        $row->text.=$AG->generateThumbs();
                }
                include (dirname(__FILE__).'/AdmirorGallery/templates/'.$AG->params['template'].'/index.php');
                $AG->clearOldThumbs();
                $row->text = preg_replace("#{AdmirorGallery[^}]*}".$AG->imagesFolderName."{/AdmirorGallery}#s", "<div style='clear:both'></div>".$html, $row->text, 1);
            }// foreach($matches[0] as $match)		
            /* ========================= SIGNATURE ====================== */
            if($AG->params['showSignature']){
                    $row->text .= '<div style="display:block; font-size:10px;">';
            }else{
                    $row->text .= '<div style="display:block; font-size:10px; overflow:hidden; height:1px; padding-top:1px;">';
            }
            $row->text .= '<br /><a href="http://www.admiror-design-studio.com/admiror/en/design-resources/joomla-admiror-gallery" target="_blank">AdmirorGallery</a>, created by <a href="http://www.admiror-design-studio.com" target="_blank">Kekeljevic</a> & <a href="http://www.vasiljevski.com/" target="_blank">Vasiljevski</a>.<br />';
            $row->text .= '</div>';
            /* ------------------------ SIGNATURE ---------------------- */
        }//if (preg_match_all("#{AdmirorGallery}(.*?){/AdmirorGallery}#s", $row->text, $matches, PREG_PATTERN_ORDER)>0)
    }//onPrepareContent(&$row, &$params, $limitstart)
}//class plgContentadmirorGallery extends JPlugin
?>
