<?php
 
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

var_dump($CCS->output);

echo '<hr />';

foreach($CCS->output as $row){
	echo '<a href="#" onclick="
	document.id(\'ccs_layout_'.$CCS->moduleID.'\').set(\'value\',\'row\');
	document.id(\'ccs_rowID_'.$CCS->moduleID.'\').set(\'value\',\''.$row['id'].'\');
	document.id(\'ccs_'.$CCS->moduleID.'\').submit();
	return false;
	">'.$row['id'].'</a><br />';
}
