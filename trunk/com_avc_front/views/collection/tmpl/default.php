<?php

JHTML::_('behavior.mootools', 'more');

//var_dump($this->collection);

echo '
<form action=\'' . JRoute::_('index.php') . '\' method=\'post\' name=\'AVC_COLLECTION_FORM\'>
<input type=\'hidden\' id=\'AVC_COLLECTION\' name=\'AVC_COLLECTION\' value=\'\' />
<input type=\'hidden\' id=\'AVC_TASK\' name=\'task\' value=\'\' />
<input type=\'hidden\' id=\'AVC_CONTROLLER\' name=\'controller\' value=\'collection\' />
<input type=\'hidden\' id=\'AVC_CAPTCHA\' name=\'AVC_CAPTCHA\' value=\'\' />
</form>
';

if(!empty($this->collection["order"])){

	echo '<table cellpadding="0" cellspacing="0" border="0" class="avc_table_order" width="100%"><thead>';
		echo '<tr>
		<th>'.JText::_(strtoupper("item_code")).'</th>
		<th>'.JText::_(strtoupper("description")).'</th>
		<th class="avc_table_right">'.JText::_(strtoupper("price_per_item")).'</th>
		<th class="avc_table_right">'.JText::_(strtoupper("num_of_items")).'</th>
		<th class="avc_table_right">'.JText::_(strtoupper("total_price")).'</th>
		</tr>';
	echo '</thead><tbody>';

	$priceTotal=0;
	foreach ($this->collection["order"] as $key => $value) {
		echo '<tr>
		<td>'.$key.'</td>
		<td>'.$value["description"].'</td>
		<td class="avc_table_right">'.$value["price_per_item"].'</td>
		<td class="avc_table_right"><a href="#" onclick="AVC_COLLECTION_UPDATE_ORDER(\'del\', \''.$key.'\'); return false;" class="AVC_LAYOUT_BUTTON">-</a> '.$value["num_of_items"].' <a href="#" onclick="AVC_COLLECTION_UPDATE_ORDER(\'add\', \''.$key.'\'); return false;" class="AVC_LAYOUT_BUTTON">+</a></td>
		<td class="avc_table_right">'.(intval($value["num_of_items"])*intval($value["price_per_item"])).'</td>
		</tr>';
		$priceTotal+=(intval($value["num_of_items"])*intval($value["price_per_item"]));
	}

	echo '<tr>
	<td colspan="4" class="avc_table_right"><h3>'.JText::_("SUM_TOTAL").': </h3></td>
	<td class="avc_table_right"><h3>'.$priceTotal.'</h3></td>
	</tr>';
	$priceTotal.=$value["num_of_items"]*$value["price_per_item"];

	echo '</tbody></table>';
}else{
	echo '<h3>'.JText::_("ORDER_EMPTY").'</h3>';
}

echo '<p style="clear:both">&nbsp;</p>';

echo '<table cellpadding="0" cellspacing="20" border="0"><tbody><tr>';

echo '<td>';
echo '<h2>'.JText::_("CUSTOMER_DETAILS").'</h2>';
echo '<table cellpadding="0" cellspacing="0" border="0" class="avc_table_customer"><tbody>';
foreach ($this->collection["customer"] as $key => $value) {
	echo '<tr><td>'.JText::_(strtoupper($key)).': </td><td><input type="text" value="'.$value.'" name="'.$key.'" /></td></tr>';
}
echo '</tbody></table>';
echo '</td>';

echo '<td>';
echo '<h2>'.JText::_("ADDITIONAL_DETAILS").'</h2>';
echo '<table cellpadding="0" cellspacing="0" border="0" class="avc_table_additional_detail"><tbody>';
foreach ($this->collection["additional_details"] as $key => $value) {
	echo '<tr><td>'.JText::_(strtoupper($key)).': </td><td>';

	switch ($key) {

		case 'message':
			echo '<textarea name="'.$key.'" rows="4">'.$value.'</textarea>';
			break;
		
		default:
			echo '<input type="text" value="'.$value.'" name="'.$key.'" />';
			break;

	}

	echo '</td></tr>';
}
echo '</tbody></table>';
echo '</td>';

echo '</tr></tbody></table>';

echo '<p style="clear:both">&nbsp;</p>';

echo '
<img src="components/com_avc/views/collection/tmpl/cool-php-captcha-0.3.1/captcha.php" id="captcha" /><br/>
<!-- CHANGE TEXT LINK -->
<a href="#" onclick="
    document.getElementById(\'captcha\').src=\'components/com_avc/views/collection/tmpl/cool-php-captcha-0.3.1/captcha.php?\'+Math.random();
    document.getElementById(\'captcha-form\').focus();"
    id="change-image">Generiši novi Sigurnosni kod.</a><br/><br/>
<input type="text" name="captcha" id="captcha-form" autocomplete="off" />
<p style="clear:both">&nbsp;</p>
';

echo '<a href="#" class="AVC_LAYOUT_BIGBUTTON" onclick="AVC_COLLECTION_SEND(); return false;"><h3>'.JText::_("SEND").'</h3></a>';

echo '<p style="clear:both">&nbsp;</p>';

$JS_AVC_COLLECTION = '
///////////////////////////////////////
//
// AVC COLLECTION
//
///////////////////////////////////////

var AVC_COLLECTION='.json_encode($this->collection).';

function countObjAttr(obj) {
    var count = 0;
    for(var prop in obj) {
        if(obj.hasOwnProperty(prop))
            count = count + 1;
    }
    return count;
}

function AVC_COLLECTION_UPDATE_ORDER(operation, key){

	if(operation == "del"){
		AVC_COLLECTION["order"][key]["num_of_items"]-=1;
	}else{
		AVC_COLLECTION["order"][key]["num_of_items"]+=1;
	}
	if(AVC_COLLECTION["order"][key]["num_of_items"]<=0){
		delete AVC_COLLECTION["order"][key];
	}

	AVC_COLLECTION_UPDATE_CUSTOMER();
	AVC_COLLECTION_UPDATE_ADDITIONAL_DETAILS();

	$("AVC_COLLECTION").value = JSON.stringify(AVC_COLLECTION);
	document.AVC_COLLECTION_FORM.submit();
}

function AVC_COLLECTION_UPDATE_CUSTOMER(){
	$$(".avc_table_customer input").each(function(item,index){
		AVC_COLLECTION["customer"][item.get("name")]=item.value;
	});
}

function AVC_COLLECTION_UPDATE_ADDITIONAL_DETAILS(){
	$$(".avc_table_additional_detail input").each(function(item,index){
		AVC_COLLECTION["additional_details"][item.get("name")]=item.value;
	});

	$$(".avc_table_additional_detail textarea").each(function(item,index){
		AVC_COLLECTION["additional_details"][item.get("name")]=item.value;
	});
}

function AVC_COLLECTION_SEND(){

	AVC_COLLECTION_UPDATE_CUSTOMER();
	AVC_COLLECTION_UPDATE_ADDITIONAL_DETAILS();

	var requestValid = true;
	Object.each(AVC_COLLECTION["customer"], function(value, key){
		if(value == ""){
			requestValid = false;
		}
	});

	if(countObjAttr(AVC_COLLECTION["order"]) <= 0){
		requestValid = false;
	}

	if(requestValid){
        $("AVC_COLLECTION").value = JSON.stringify(AVC_COLLECTION);
        $("AVC_CAPTCHA").value = $("captcha-form").value;
        $("AVC_TASK").value = "send";
        document.AVC_COLLECTION_FORM.submit();
	}else{		
		alert("'.JText::_("ORDER_ERROR").'");
	}
}

';
$this->doc->addScriptDeclaration($JS_AVC_COLLECTION);


?>
