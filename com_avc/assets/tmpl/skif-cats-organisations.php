<?php

defined('_JEXEC') or die('Restricted access');

// echo '<h2>ROWs</h2>';
// echo '<pre>';
// var_dump($ROWs);
// echo '</pre>'; 

$DATAs = array();
if(!empty($ROWs)){
foreach ($ROWs as $key => $row) {

	$DATAs[ $key ] = array();
	$DATAs[ $key ]["id"] = $LOGs[$config["id"]][ $row["childid"] ];	
	$DATAs[ $key ]["title"] = $row["orgtitle"];
	$DATAs[ $key ]["alias"] = JFile::makeSafe( strtolower( str_replace(" ", "-", $row["orgtitle"]) ) );

	// SET PARENT ID
	if( !empty($LOGs[$config["id"]][ $row["parentid"] ]) || ( $row["parentid"] != 0 ) ){
		$DATAs[ $key ]["parent_id"] = $LOGs[$config["id"]][ $row["parentid"] ];
	}else{
		$DATAs[ $key ]["parent_id"] = NULL;
	}

	// CONTENT
	$DATAs[$key]["description"] = '';
	$DATAs[$key]["description"].= '<div class="AVC_LAYOUT_CONTENT">';

	if(!empty($row["orgarticle"])){
		$DATAs[$key]["description"].= '<p>'.$row["orgarticle"].'</p>';
		$DATAs[$key]["description"].= '<p>&nbsp;</p>';	
	}

	// ADDRESS
	$DATAs[$key]["description"].= '
	<div class="AVC_SECTION_ADDRESS">
	<p>'.$row["orgaddress"].'</p>
	<p>'.$row["naszip"].' '.$row["nastown"].'</p>
	<p>'.$row["zemcountry"].'</p>
	</div>
	';

	if(!empty($row["orgphone"])){
		// Parse JSON
		$PHONE = json_decode($row["orgphone"], true);
		if(!empty($PHONE)){
			$DATAs[$key]["description"].= '<div class="AVC_SECTION_PHONE">';
			foreach ($PHONE as $key => $value) {
				$DATAs[$key]["description"].= '<p>'.JText::_(strtoupper($key)).': '.$value.'</p>';
			}
			$DATAs[$key]["description"].= '</div>';
		}
	}

	if(!empty($row["orge_mail"])){
	$DATAs[$key]["description"].= '
	<div class="AVC_SECTION_EMAIL">
	<p><a href="mailto:'.$row["orge_mail"].'">'.$row["orge_mail"].'</a></p>
	</div>
	';
	}

	if(!empty($row["orgweb"])){
	$DATAs[$key]["description"].= '
	<div class="AVC_SECTION_WEB">
	<p><a href="'.$row["orgweb"].'" target="blank">'.$row["orgweb"].'</a></p>
	</div>
	';
	}

	$DATAs[$key]["description"].= '<p style="clear:both">&nbsp;</p>';

	$DATAs[$key]["description"].= '</div>';


}
}

?>



