<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

echo '<a href="#" onclick="
document.id(\'AVC_layout_' . $AVC->moduleID . '\').set(\'value\',\'table\');
document.id(\'AVC_rowID_' . $AVC->moduleID . '\').set(\'value\',\'0\');
document.id(\'AVC_' . $AVC->moduleID . '\').submit();
return false;
">Back</a><hr />';

var_dump($AVC->output);

