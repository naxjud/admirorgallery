<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

echo '
<form action="' . JRoute::_('index.php') . '" method="post" name="AVC_' . $AVC->moduleID . '" id="AVC_' . $AVC->moduleID . '">
';

require  JPATH_ROOT . DS . 'modules' . DS . 'mod_avc' . DS . 'templates' . DS . $AVC->template;

echo '
<input type="hidden" name="AVC_layout_' . $AVC->moduleID . '" id="AVC_layout_' . $AVC->moduleID . '" value="' . $AVC->layout . '" />
<input type="hidden" name="AVC_rowID_' . $AVC->moduleID . '" id="AVC_rowID_' . $AVC->moduleID . '" value="' . $AVC->rowID . '" />
</form>
';
?>