<?php

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

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

$link = 'index.php?option=com_admirorgallery&task=templates';
quickiconButton( $link, 'icon-48-templates.png', JText::_('Templates') );

$link = 'index.php?option=com_admirorgallery&task=popups';
quickiconButton( $link, 'icon-48-popupsThemes.png', JText::_('Popups') );

$link = 'index.php?option=com_admirorgallery&task=image-manager';
quickiconButton( $link, 'icon-48-image-manager.png', JText::_('Image manager') );


echo JText::_('ADMIRORGALLERYADMINISTRATIONSYSTEM');


echo '
</div>
';

?>
