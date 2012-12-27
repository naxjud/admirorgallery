<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

var_dump($AVC->output);

echo '<hr />';

foreach ($AVC->output as $row) {
    echo '<a href="#" onclick="
	document.id(\'AVC_layout_' . $AVC->moduleID . '\').set(\'value\',\'row\');
	document.id(\'AVC_rowID_' . $AVC->moduleID . '\').set(\'value\',\'' . $row['id'] . '\');
	document.id(\'AVC_' . $AVC->moduleID . '\').submit();
	return false;
	">' . $row['id'] . '</a><br />';
}
