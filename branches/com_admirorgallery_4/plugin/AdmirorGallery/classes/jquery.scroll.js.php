<?php
/*------------------------------------------------------------------------
# plg_admirorgallery - Admiror Gallery Plugin
# ------------------------------------------------------------------------
# author    Vasiljevski & Kekeljevic
# copyright Copyright (C) 2011 admiror-design-studio.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.admiror-design-studio.com/joomla-extensions
# Technical Support:  Forum - http://www.vasiljevski.com/forum/index.php
# Version: 4.0
-------------------------------------------------------------------------*/
if(!empty($_GET['AG_form_scrollTop'])){

$doc->addScriptDeclaration('
    AG_jQuery(document).ready(function() {
        AG_jQuery(window).scrollTop('.$_GET['AG_form_scrollTop'].');
        AG_jQuery(window).scrollLeft('.$_GET['AG_form_scrollLeft'].');
    });
');

}
?>
