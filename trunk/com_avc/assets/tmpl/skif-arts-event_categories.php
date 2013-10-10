<?php

defined('_JEXEC') or die('Restricted access');

// echo '<h2>LOGs</h2>';
// echo '<pre>';
// var_dump($LOGs);
// echo '</pre>';


$gen_id_for_catid = $config["custom_values"]["gen_id_for_catid"];

$SECTIONs = array();
if(!empty($ROWs)){
	foreach ($ROWs as $key => $row) {
		if( empty($SECTIONs[$row["section_id"]]) ){
			$SECTIONs[$row["section_id"]] = array();
		}
		if( !array_key_exists( $row["category_id"], $SECTIONs[ $row["section_id"] ] ) ){
			$SECTIONs[ $row["section_id"] ][$row["category_id"]] = array( "category_title"=>$row["category_title"], "list"=>array() );
		}
		$SECTIONs[ $row["section_id"] ][$row["category_id"]]["list"][(int)$row["rank"]][] = array("img_url"=>$row["img_url"], "first_name"=>$row["first_name"], "last_name"=>$row["last_name"], "country_code"=>$row["country_code"]);
	}
}

// echo '<h2>SECTIONs</h2>';
// echo '<pre>';
// var_dump($SECTIONs);
// echo '</pre>';

$INDEX = 0;
$DATAs = array();
if(!empty($SECTIONs)){
foreach ($SECTIONs as $section_id => $categories) {

	if(!empty($categories)){
	foreach ($categories as $category_id => $category) {

		$DATAs[ $INDEX ] = array();
		$DATAs[ $INDEX ]["id"] = $section_id.$category_id;
		$DATAs[ $INDEX ]["title"] = $category["category_title"];
		$DATAs[ $INDEX ]["alias"] = JFile::makeSafe( strtolower( str_replace(" ", "-", $category["category_title"]) ) );

		// SET CAT ID
		if( !empty($LOGs[ $gen_id_for_catid ][ $section_id ]) || ( $section_id != 0 ) ){
			$DATAs[ $INDEX ]["catid"] = $LOGs[ $gen_id_for_catid ][ $section_id ];
		}else{
			$DATAs[ $INDEX ]["catid"] = NULL;
		}

		$DATAs[$INDEX]["introtext"] = '';
		$DATAs[$INDEX]["fulltext"] = '';
		ksort($category["list"]);
		// CONTENT
		foreach ($category["list"] as $rank => $rank_list) {
			foreach ($rank_list as $sub_rank => $value) {

				if( $rank<4 ){

					$DATAs[$INDEX]["introtext"].='<div class="AVC_SECTION_BLOCK">';
					$DATAs[$INDEX]["introtext"].='<table cellpadding="0" cellspacing="0" border="0" width="100%" class="event_results_list"><tbody><tr>';
					$DATAs[$INDEX]["introtext"].='<td width="10px">'.$rank.'</td>';
					$DATAs[$INDEX]["introtext"].='<td width="10px"><img src="images/events/medals/rank'.$rank.'.jpg" width="100px" height="100px" /></td>';
					if(file_exists(JPATH_ROOT.DS.$value["img_url"])){
						$DATAs[$INDEX]["introtext"].='<td width="10px"><img src="'.$value["img_url"].'" width="100px" height="100px" /></td>';
					}
					$DATAs[$INDEX]["introtext"].='<td>'.$value["last_name"].' '.$value["first_name"].'</td>';	
					$DATAs[$INDEX]["introtext"].='<td width="10px" align="right"><img src="images/flags/'.strtolower($value["country_code"]).'.gif" /></td>';			
					$DATAs[$INDEX]["introtext"].='<td width="10px" align="right">'.$value["country_code"].'</td>';
					$DATAs[$INDEX]["introtext"].='</tr></tbody></table>';
					$DATAs[$INDEX]["introtext"].='</div>';

				}else{

					$DATAs[$INDEX]["fulltext"].='<div class="AVC_SECTION_BLOCK">';
					$DATAs[$INDEX]["fulltext"].='<table cellpadding="0" cellspacing="0" border="0" width="100%" class="event_results_list"><tbody><tr>';
					$DATAs[$INDEX]["fulltext"].='<td width="10px">'.$rank.'</td>';
					$DATAs[$INDEX]["fulltext"].='<td>'.$value["last_name"].' '.$value["first_name"].'</td>';	
					$DATAs[$INDEX]["fulltext"].='<td width="10px" align="right"><img src="images/flags/'.strtolower($value["country_code"]).'.gif" /></td>';			
					$DATAs[$INDEX]["fulltext"].='<td width="10px" align="right">'.$value["country_code"].'</td>';
					$DATAs[$INDEX]["fulltext"].='</tr></tbody></table>';
					$DATAs[$INDEX]["fulltext"].='</div>';

				}

			}
		}

		$INDEX++;

	}
	}


}
}

?>



