<?php



$doc = &JFactory::getDocument();
$doc->addStyleSheet(JURI::base().'components/com_admirorgallery/css/template.css');




function quickiconButton( $link, $image, $text )
{
     global $mainframe;
     $lang = & JFactory::getLanguage();
     ($lang->isRTL()) ? $iconFloat="right" : $iconFloat="left" ;
     echo '
          <div style="float:'.$iconFloat.'">
               <div class="ag_guickIcon">
                    <a href="'.$link.'">
                         <img src="'.JURI::base().'components/com_admirorgallery/images/toolbar/'.$image.'" />
                         <span>'.$text.'</span>
                    </a>
               </div>
          </div>
     ';
}





echo '
<div id="ag_controlPanel_wrapper">
';

$link = 'index.php?option=com_admirorgallery&task=themes';
quickiconButton( $link, 'icon-48-galleryThemes.png', JText::_('Gallery Themes') );

$link = 'index.php?option=com_admirorgallery&task=popups';
quickiconButton( $link, 'icon-48-popupsThemes.png', JText::_('Popups Engines') );

$link = 'index.php?option=com_admirorgallery&task=descriptions';
quickiconButton( $link, 'icon-48-descriptions.png', JText::_('Descriptions') );


echo '
<br style="clear:both;">
<br />
<br />
<h1>Admiror Gallery Administration system</h1>
<p>
It\'s extension for Admiror Gallery Plugin. Plugin can work independently from Admiror Gallery Administration system. Admiror Gallery consists of 3 mayor elements: <b>Gallery Theme</b>, <b>Popup Engine</b> and <b>Descritions</b>. This component is build to make management of this elements more easy.
</p>
<p>
For more information about Admiror Gallery visit <a href="http://www.admiror-design-studio.com/admiror/en/design-resources/joomla-admiror-gallery" target="_blank">Admiror Gallery page</a>.
</p>
<p><table cellpadding="0" cellspacing="10" border="0"><tbody><tr><td><img src="'.JURI::base().'components/com_admirorgallery/images/toolbar/icon-48-galleryThemes.png" align="middle" /></td><td><b>Gallery Theme</b> is standard look and feel of gallery. Usually it\'s a set of small thumbnails with descriptions.</td></tr></tbody></table></p>
<p><table cellpadding="0" cellspacing="10" border="0"><tbody><tr><td><img src="'.JURI::base().'components/com_admirorgallery/images/toolbar/icon-48-popupsThemes.png" align="middle" /></td><td><b>Popup Engine</b> is wrapper for showing larger image, which is usually initiated by clicking on thumbnail image.</td></tr></tbody></table></p>
<p><table cellpadding="0" cellspacing="10" border="0"><tbody><tr><td><img src="'.JURI::base().'components/com_admirorgallery/images/toolbar/icon-48-descriptions.png" align="middle" /></td><td><b>Descriptions</b> are descriptions for images. You can have different description for each of installed site languages.</td></tr></tbody></table></p>
<img src="'.JURI::base().'components/com_admirorgallery/images/ag-explanation.jpg" />

';


echo '
</div>
';

?>
