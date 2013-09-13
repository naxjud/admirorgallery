<?php

defined('_JEXEC') or die('Restricted access');

if($config["test"]){
	echo '<h2>ROWs</h2>';
	echo '<pre>';
	var_dump($ROWs);
	echo '</pre>'; 
}

$DATAs = array();
if(!empty($ROWs)){

	$key = 0;
	$DATAs[ $key ] = array();
	$DATAs[ $key ]["id"] = $LOGs[$config["id"]][ 1 ];	
	$DATAs[ $key ]["title"] = "Accommodation";
	$DATAs[ $key ]["alias"] = "accommodation";
	$DATAs[ $key ]["parent_id"] = $config["custom_values"]["parent_id"];

	// CONTENT
	$DATAs[$key]["description"] = '';

	$DATAs[$key]["description"].= '
		<hr />
		<p>Please DO NOT CALL Hotels directly. All the accommodation activities / bookings have to be coordinated with <b>Branko Perisic</b>, Technical Organization Manager.</p>
		<p>Email: perisic@uns.ac.rs</p>
		<p>Phone: 00 381 21 485 2244</p>
		<p>Mob. 00 381 64 1340173</p>
		<p>&nbsp;</p>
		<p>We kindly ask all national teams to declare, as soon as possible, the expected number of participants and special requests concerning the accommodation and prices in order to enable early reservation and better conditions.</p>
		<p>&nbsp;</p>
		<p>More informations about <a href="images/21_SKDUN_2013/documents/accommodation_and_lunches.pdf" target="_blank">Accommodation and Lunches</a></p>
		<p>&nbsp;</p>
		Prices are per person per day, in EUR.
		<hr />
	';

	$hotels = array();
	$accommodation_types = array();
	foreach ($ROWs as $row) {
		if(empty($accommodation_types[ $row["aalias"] ])){
			$accommodation_types[ $row["aalias"] ] = $row["atitle"];
		}
	}

	if(!empty($accommodation_types)){
		$DATAs[$key]["description"].= '
		<script type="text/javascript">

			window.addEvent("domready", function(){

				$("accommodation_type").addEvents({
					"change":function(e){
						if($("accommodation_type").get("value")!=""){
							$$(".accommodation_data").setStyles({"display":"none"});
							$$("."+$("accommodation_type").get("value")).setStyles({"display":"table-row"});
						}else{
							$$(".accommodation_data").setStyles({"display":"table-row"});
						}
					}
				});

			});

		</script>
		';
		$DATAs[$key]["description"].= '<div style="float:right;display:block;"><select id="accommodation_type" name="accommodation_type">';
		$DATAs[$key]["description"].= '<option value="">All Accommodation Types</option>';	
		foreach ($accommodation_types as $type_alias => $type_title) {
			$DATAs[$key]["description"].= '<option value="'.$type_alias.'">'.$type_title.'</option>';
		}
		$DATAs[$key]["description"].= '<select></div>';
	}

}

?>



