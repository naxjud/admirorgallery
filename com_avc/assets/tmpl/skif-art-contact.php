<?php

if(!empty($ROWs)){

	$DATAs = array();
	$DATAs[1] = array();
	$DATAs[1]["id"] = $LOGs[$config["id"]][ $row["istid"] ];
	$DATAs[1]["title"] = "Contact";
	$DATAs[1]["alias"] = "contant";

	$DATAs[1]["introtext"] = "";
	$DATAs[1]["introtext"].= '<div class="AVC_LAYOUT_CONTENT">';

	$committeeNames = array();
	// COLLECT ALL COMMITTEE NAMES
	foreach ($ROWs as $row) {
		$committeeNames[$row["committee_name"]][] = $row;
	}


	foreach ($committeeNames as $committeeName => $committee) {

		$functionNames = array();
		foreach ($committee as $function) {
			$functionNames[$function["function_name"]][] = $function;
		}

		foreach ($functionNames as $function_name => $persons) {

			$DATAs[1]["introtext"].= '<hr class="AVC_SEPARATOR" />';

			$DATAs[1]["introtext"].= '<h3>'.$function_name.'</h3>';

			foreach ($persons as $person) {

				$DATAs[1]["introtext"].= '<h1 style="text-transform:uppercase">'.$person["last_name"].' '.$person["first_name"].'</h1>';

				$address_data = "";
				if(!empty($person["address"])){$address_data.= '<p>'.$person["address"].'</p>';}
				if(!empty($person["zip"])){$address_data.= '<p>'.$person["zip"].' '.$person["town"].'</p>';}
				if(!empty($person["country"])){$address_data.= '<p>'.$person["country"].'</p><p>&nbsp;</p>';};
				if(!empty($address_data)){
					$DATAs[1]["introtext"].= '<div class="AVC_SECTION_ADDRESS">';
					$DATAs[1]["introtext"].= $address_data;
					$DATAs[1]["introtext"].= '</div>';
				}


				if(!empty($person["phone"])){
					$phone = json_decode($person["phone"]);
					if(!empty($phone)){
						$DATAs[1]["introtext"].= '<div class="AVC_SECTION_PHONE">';
						foreach ($phone as $key => $value) {					
							$DATAs[1]["introtext"].= '<li>'.$key.': '.$value.'</li>';
						}	
						$DATAs[1]["introtext"].= '</ul><p>&nbsp;</p>';
						$DATAs[1]["introtext"].= '</div>';
					}
				}

				if(!empty($person["e_mail"])){
					if(!empty($phone)){
						$DATAs[1]["introtext"].= '<div class="AVC_SECTION_EMAIL">';
						$mejl = json_decode($person["e_mail"]);
						foreach ($mejl as $key => $value) {					
							$DATAs[1]["introtext"].= '<li>'.$key.': '.$value.'</li>';
						}	
						$DATAs[1]["introtext"].= '</ul><p>&nbsp;</p>';
						$DATAs[1]["introtext"].= '</div>';
					}
				}

			}
			
			$DATAs[1]["introtext"].= '<p>&nbsp;</p>';

		}			

	}	

	$DATAs[1]["introtext"].= '<p style="clear:both">&nbsp;</p>';

	$DATAs[1]["introtext"].= '</div>';

}

?>