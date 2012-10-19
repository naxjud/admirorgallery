<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

echo '<a href="#" onclick="
document.id(\'ccs_layout_' . $CCS->moduleID . '\').set(\'value\',\'table\');
document.id(\'ccs_rowID_' . $CCS->moduleID . '\').set(\'value\',\'0\');
document.id(\'ccs_' . $CCS->moduleID . '\').submit();
return false;
">Back</a><hr />';

var_dump($CCS->output);

