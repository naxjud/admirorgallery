<?php
 /*------------------------------------------------------------------------
# admirorgallery - Admiror Gallery Plugin
# ------------------------------------------------------------------------
# author   Igor Kekeljevic & Nikola Vasiljevski
# copyright Copyright (C) 2011 admiror-design-studio.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.admiror-design-studio.com/joomla-extensions
# Technical Support:  Forum - http://www.vasiljevski.com/forum/index.php
# Version: 4.5.0
-------------------------------------------------------------------------*/
// Joomla security code
defined('_JEXEC') or die('Restricted access');

// Load CSS from current template folder
$AG->loadCSS($AG->currTemplateRoot . 'template.css');
$AG->loadCSS($AG->currTemplateRoot . 'albums/albums.css');
$AG->loadCSS($AG->currTemplateRoot . 'pagination/pagination.css');

// Reset $html variable from previous entery and load it with scripts needed for Popups
$html = $AG->initPopup();

// Form HTML code, with unique ID and Class Name
$html.='
<style type="text/css">
     .AG_classic_centered .ag_imageThumb{border-color:#'.$AG->params['foregroundColor'].'}
     .AG_classic_centered .ag_imageThumb:hover{background-color:#'.$AG->params['highliteColor'].'}
    /* PAGINATION AND ALBUM STYLE DEFINITIONS */
      #AG_' . $AG->getGalleryID() . ' a.AG_album_thumb, #AG_' . $AG->getGalleryID() . ' div.AG_album_wrap, #AG_' . $AG->getGalleryID() . ' a.AG_pagin_link, #AG_' . $AG->getGalleryID() . ' a.AG_pagin_prev, #AG_' . $AG->getGalleryID() . ' a.AG_pagin_next {border-color:#' . $AG->params['foregroundColor'] . '}
      #AG_' . $AG->getGalleryID() . ' a.AG_album_thumb:hover, #AG_' . $AG->getGalleryID() . ' a.AG_pagin_link:hover, #AG_' . $AG->getGalleryID() . ' a.AG_pagin_prev:hover, #AG_' . $AG->getGalleryID() . ' a.AG_pagin_next:hover {background-color:#' . $AG->params['highliteColor'] . '}
      #AG_' . $AG->getGalleryID() . ' div.AG_album_wrap h1, #AG_' . $AG->getGalleryID() . ' a.AG_pagin_link, #AG_' . $AG->getGalleryID() . ' a.AG_pagin_prev, #AG_' . $AG->getGalleryID() . ' a.AG_pagin_next{color:#' . $AG->params['foregroundColor'] . '}

</style>
<div id="AG_' . $AG->getGalleryID() . '" class="ag_reseter AG_' . $AG->params['template'] . '">';

$html.=$AG->albumParentLink;

$html.='
 <table cellspacing="0" cellpadding="0" border="1" width="100%">
    <tbody>
      <tr>
	<td align="center">';

// Loops over the array of images inside target gallery folder, adding wrapper with SPAN tag and write Popup thumbs inside this wrapper
if (!empty($AG->images)) {
    foreach ($AG->images as $imageKey => $imageName) {
        $html.= '<span class="ag_thumb' . $AG->params['template'] . '">';
        $html.= $AG->writePopupThumb($imageName);
        $html.='</span>';
    }
}

$html .='
	</td>
      </tr>
    </tbody>
  </table>';

// Support for Pagination
$html.= $AG->writePagination();

// Support for Albums
if (!empty($AG->folders) && $AG->params['albumUse']) {
    $html.= '<h1>' . JText::_('Albums') . '</h1>' . "\n";
$html.='
 <table cellspacing="0" cellpadding="0" border="1" width="100%">
    <tbody>
      <tr>
	<td align="center">';
            foreach ($AG->folders as $folderKey => $folderName) {
                $thumb_file = "";
                // Get Thumb URL value                
                // Set Possible Description File Apsolute Path // Instant patch for upper and lower case...
                $ag_pathWithStripExt = $AG->imagesFolderPhysicalPath . $folderName;
                $ag_XML_path = $ag_pathWithStripExt . ".XML";
                if (file_exists($ag_pathWithStripExt . ".xml")) {
                    $ag_XML_path = $ag_pathWithStripExt . ".xml";
                }
                if (file_exists($ag_XML_path)) {// Check is descriptions file exists
                    $ag_XML_xml = simplexml_load_file($ag_XML_path);
                    if (isset($ag_XML_xml->thumb)) {
                        $thumb_file = (string) $ag_XML_xml->thumb;
                    }
                }
                if (empty($thumb_file)) {
                    $images = agHelper::ag_imageArrayFromFolder($AG->imagesFolderPhysicalPath . $folderName);
                    if (!empty($images)) {
                        $images = agHelper::array_sorting($images, $AG->imagesFolderPhysicalPath . $folderName . DIRECTORY_SEPARATOR, $AG->params['arrange']);
                        $thumb_file = $images[0]; // Get First image in folder as thumb 
                    }
                }
                if (!empty($thumb_file)) {
                    $AG->Album_generateThumb($folderName, $thumb_file);
                }
                
                $html.='<span class="ag_thumbclassic_centered"> <a href="#" onClick="AG_form_submit_' . $AG->articleID . '(' . $AG->index . ',1,\'' . $AG->imagesFolderName . '/' . $folderName . '\'); return false;" class="AG_album_thumb">';
                $html.='<span class="AG_album_thumb_img">';
                if(!empty($thumb_file))
                {
                    $html.='<img src="' . $AG->sitePath . PLUGIN_BASE_PATH . 'thumbs/' . $AG->imagesFolderName . '/' . $folderName . '/' . basename($thumb_file) . '" />' . "\n";
                }
                else
                {
                    $html.='<img src="' . $AG->sitePath . PLUGIN_BASE_PATH .'defaultAlbum.png" />' . "\n";
                }
                $html.='</span>';
                $html.='<span class="AG_album_thumb_label">';
                $html.=$AG->writeDescription($folderName);
                $html.='</span>';
                $html.='</a></span>';
            }
            $html.= '<br style="clear:both;" />' . "\n";
			$html .='
	</td>
      </tr>
    </tbody>
  </table>';
}

$html.='
</div>
';

// Loads scripts needed for Popups, after gallery is created
$html.=$AG->endPopup();
?>
