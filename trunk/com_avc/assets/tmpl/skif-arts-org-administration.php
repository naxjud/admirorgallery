<?php

defined('_JEXEC') or die('Restricted access');

// echo '<pre>';
// var_dump($ROWs);
// echo '</pre>'; 

$gen_id_for_catid = $config["custom_values"]["gen_id_for_catid"];

$committees = array();
if(!empty($ROWs)){
foreach ($ROWs as $key => $row) {
	// Organisation
	$orguniquename = JFile::makeSafe( strtolower( str_replace(" ", "-", $row["orgtitle"].", ".$row["nastown"].", ".$row["zemcountry"]) ) );
	if( empty($committees[$orguniquename]) ){
		$committees[$orguniquename] = array();
		$committees[$orguniquename]["row"] = $row;		
		$committees[$orguniquename]["commettee"] = array();
	}
	// Committees
	if( empty($committees[$orguniquename]["commettee"][$row["committee_name"]]) ){
		$committees[$orguniquename]["commettee"][$row["committee_name"]] = array();
	}
	// Functions
	if( empty($committees[$orguniquename]["commettee"][$row["committee_name"]][$row["function_name"]]) ){
		$committees[$orguniquename]["commettee"][$row["committee_name"]][$row["function_name"]] = array();
	}	
	// People
	$img="";
	if(!empty($row["img_url"])){
		$img='<td><img src="'.$row["img_url"].'" class="AVC_SECTION_THUMB" /></td>';
	}
	$committees[$orguniquename]["commettee"][$row["committee_name"]][$row["function_name"]][] = "<table><tbody><tr>".$img."<td style='vertical-align:middle'>".$row["last_name"].", ".$row["first_name"]."</td></tbody></table>";
}
}

$DATAs = array();
if(!empty($committees)){
foreach ($committees as $key => $committee) {

	$DATAs[$key] = array();
	$DATAs[$key]["id"] = $committee["row"]["orgid"];
	$DATAs[$key]["ordering"] = 2;
	$DATAs[$key]["title"] = "Administration";
	$DATAs[$key]["alias"] = "administration-".JFile::makeSafe( strtolower( str_replace(" ", "-", $committee["row"]["orgtitle"]." ".$committee["row"]["nastown"].", ".$committee["row"]["zemcountry"]) ) );

	// SET CAT ID
	if( !empty($LOGs[ $gen_id_for_catid ][ $committee["row"]["catid"] ]) || ( $committee["row"]["catid"] != 0 ) ){
		$DATAs[ $key ]["catid"] = $LOGs[ $gen_id_for_catid ][ $committee["row"]["catid"] ];
	}else{
		$DATAs[ $key ]["catid"] = NULL;
	}	


	// CONTENT
	$DATAs[$key]["introtext"] = '';
	$DATAs[$key]["introtext"].= '<div class="AVC_LAYOUT_CONTENT">';

	if(!empty($committee["commettee"])){
	foreach ($committee["commettee"] as $committeeName => $committeeData) {

		$DATAs[$key]["introtext"].= '<h1>'.$committeeName.'</h1>';

		if(!empty($committeeData)){
		foreach ($committeeData as $function_name => $persons) {
			$DATAs[$key]["introtext"].= '<div class="AVC_SECTION_BLOCK"><h2>'.$function_name.'</h2>';
			if(!empty($persons)){
			foreach ($persons as $person) {
				$DATAs[$key]["introtext"].= '<p>'.$person.'</p>';
			}
			}
			$DATAs[$key]["introtext"].= '</div>';
		}
		}

		$DATAs[$key]["introtext"].= '<p style="clear:both">&nbsp;</p>';
		$DATAs[$key]["introtext"].= '<hr />';

	}	
	}

	$DATAs[$key]["introtext"].= '<p style="clear:both">&nbsp;</p>';

	$DATAs[$key]["introtext"].= '</div>';


}
}



?>



