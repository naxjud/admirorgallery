<?php

defined('_JEXEC') or die('Restricted access');



if(!empty($ROWs)){

$hotels = array();
foreach ($ROWs as $key => $row) {
	if(empty($hotels[ $row["halias"] ])){
		$hotels[ $row["halias"] ] = array();
	}
	$hotels[ $row["halias"] ][] = $row;
}

if($config["test"]){
	echo '<h2>hotels</h2>';
	echo '<pre>';
	var_dump($hotels);
	echo '</pre>'; 
}

$DATAs = array();
$key = -1;
foreach ($hotels as $hotel_alias => $hotel_data) {

	$key ++;
	$row = $hotel_data[0];

	$DATAs[$key] = array();
	$DATAs[$key]["id"] = $LOGs[$config["id"]][ $row["hid"] ];
	$DATAs[$key]["title"] = $row["htitle"];
	$DATAs[$key]["alias"] = $row["halias"];
	$DATAs[$key]["catid"] = $config["custom_values"]["catid"];

	// CONTENT
	$DATAs[$key]["introtext"] = '';

	$DATAs[$key]["introtext"].= '<table class="myTable" width="100%" border="0" cellspacing="0" cellpading="0"><thead><tr><th>Accommodation</th><th style="text-align:right;">Quantity</th><th style="text-align:right;">Price</th><th style="text-align:right;">Supper</th></tr></thead><tbody>';
	foreach ($hotel_data as $accommodation) {	
		$DATAs[$key]["introtext"].= '<tr class="accommodation_data '.$accommodation["aalias"].'"><td>'.$accommodation["atitle"].'</td>'.'<td style="text-align:right;">'.$accommodation["quantity"].'</td>'.'<td style="text-align:right;">'.$accommodation["price"].'</td>'.'<td style="text-align:right;">'.(empty($accommodation["supper"]) ? "No" : $accommodation["supper_price"]).'</td></tr>';
	}
	$DATAs[$key]["introtext"].= '</tbody></table>';

	$DATAs[$key]["fulltext"] = '';

	$address_data = "";
	if(!empty($row["haddress"])){$address_data.= '<p>'.$row["haddress"].'</p>';}
	if(!empty($row["nzip"])){$address_data.= '<p>'.$row["nzip"].' '.$row["ntown"].'</p>';}
	if(!empty($row["ccountry"])){$address_data.= '<p>'.$row["ccountry"].'</p><p>&nbsp;</p>';};	
	if(!empty($row["hcontact"])){$address_data.= '<p>'.$row["hcontact"].'</p><p>&nbsp;</p>';};
	if(!empty($address_data)){
		$DATAs[$key]["fulltext"].= '<div class="AVC_SECTION_ADDRESS">';
		$DATAs[$key]["fulltext"].= $address_data;
		$DATAs[$key]["fulltext"].= '</div>';
	}

	if(!empty($row["hweb"])){
		$DATAs[$key]["fulltext"].= '
		<div class="AVC_SECTION_WEB">
		<p><a href="http://'.$row["hweb"].'" target="blank">'.$row["hweb"].'</a></p>
		</div>
		';
	}

}
}



?>



