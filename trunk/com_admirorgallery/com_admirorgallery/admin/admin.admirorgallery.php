<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

require_once( JApplicationHelper::getPath( 'admin_html' ) ); 
require_once( JApplicationHelper::getPath( 'toolbar' ) );

SCREENS_admirorgallery::_VIEW($task);
TOOLBAR_admirorgallery::_VIEW($task);


?>
