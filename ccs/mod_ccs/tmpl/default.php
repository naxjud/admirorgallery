<?php
 
// No direct access
defined('_JEXEC') or die('Restricted access');

echo '
<form action="'.JRoute::_('index.php').'" method="post" name="ccs_'.$CCS->moduleID.'" id="ccs_'.$CCS->moduleID.'">
';

require($CCS->templatePath) ;

echo '
<input type="hidden" name="ccs_layout_'.$CCS->moduleID.'" id="ccs_layout_'.$CCS->moduleID.'" value="'.$CCS->layout.'" />
<input type="hidden" name="ccs_rowID_'.$CCS->moduleID.'" id="ccs_rowID_'.$CCS->moduleID.'" value="'.$CCS->rowID.'" />
</form>
';

?>