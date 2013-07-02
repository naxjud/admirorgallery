<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

$form_items_width = 2;
if( !empty( $FIELD_PARAMS["width"] ) ){
    $form_items_width = $FIELD_PARAMS["width"];
}
$form_items_height = 0;
if( !empty( $FIELD_PARAMS["height"] ) ){
    $form_items_height = $FIELD_PARAMS["height"];
}

echo '
<div class="form_items form_item_width_'. $form_items_width .' form_item_height_'. $form_items_height .'">
';

echo '
	<label id="jform_enabled-lbl" for="jform_enabled">
		' . JText::_( strtoupper($FIELD_TITLE)) . '
	</label>
';	


echo '<table cellpadding="0" cellspacing="0" border="0" width="100%"><tbody><tr><td width="10%">';

echo '<textarea tabindex="'.$TABINDEX.'" wrap="off" name="'.$FIELD_ALIAS.'" id="'.$FIELD_ALIAS.'" rows="30" class="required validate-text editlinktip">'.$FIELD_VALUE.'</textarea>';

echo '</td><td>';

echo '
<div id="accordion_'.$FIELD_ALIAS.'" class="AVC_LAYOUT_ACCORDION">
  
  <h2>'. JText::_( strtoupper( 'COMMANDS PRESETS' )) .'</h2>
  
  <div class="content">
  	<a href="#" onclick="$(\''.$FIELD_ALIAS.'\').insertAtCursor( (\''.htmlentities( '"column":{}' ).'\') ); return false;">EMPTY</a>
    <a href="#" onclick="$(\''.$FIELD_ALIAS.'\').insertAtCursor( (\''.htmlentities( '"column":{\n\t"type":"boolean"\n}' ).'\') ); return false;">boolean</a>
    <a href="#" onclick="$(\''.$FIELD_ALIAS.'\').insertAtCursor( (\''.htmlentities( '"column":{\n\t"type":"button",\n\t"params":{\n\t\t"label":"buttonName",\n\t\t"onclick":"javascript"\n\t\}\n}' ).'\') ); return false;">button</a>
    <a href="#" onclick="$(\''.$FIELD_ALIAS.'\').insertAtCursor( (\''.htmlentities( '"column":{\n\t"type":"checkbox",\n\t"params":{\n\t\t"value1":"label1",\n\t\t"value2":"label2"\n\t\}\n}' ).'\') ); return false;">checkbox</a>
    <a href="#" onclick="$(\''.$FIELD_ALIAS.'\').insertAtCursor( (\''.htmlentities( '"column":{\n\t"type":"configFields"\n}' ).'\') ); return false;">configFields</a>
    <a href="#" onclick="$(\''.$FIELD_ALIAS.'\').insertAtCursor( (\''.htmlentities( '"column":{\n\t"type":"configSQL"\n}' ).'\') ); return false;">configSQL</a>
    <a href="#" onclick="$(\''.$FIELD_ALIAS.'\').insertAtCursor( (\''.htmlentities( '"column":{\n\t"type":"date"\n}' ).'\') ); return false;">date</a>
    <a href="#" onclick="$(\''.$FIELD_ALIAS.'\').insertAtCursor( (\''.htmlentities( '"column":{\n\t"type":"droplist",\n\t"params":{\n\t\t"value1":"label1",\n\t\t"value2":"label2"\n\t\}\n}' ).'\') ); return false;">droplist</a>
    <a href="#" onclick="$(\''.$FIELD_ALIAS.'\').insertAtCursor( (\''.htmlentities( '"column":{\n\t"type":"fileList",\n\t"params":{\n\t\t"folder":"folderName"\n\t\}\n}' ).'\') ); return false;">fileList</a>
    <a href="#" onclick="$(\''.$FIELD_ALIAS.'\').insertAtCursor( (\''.htmlentities( '"column":{\n\t"type":"img",\n\t"params":{\n\t\t"folder":"folderName"\n\t\}\n}' ).'\') ); return false;">img</a>
    <a href="#" onclick="$(\''.$FIELD_ALIAS.'\').insertAtCursor( (\''.htmlentities( '"column":{\n\t"type":"json",\n\t"params":{\n\t\t"0":"option",\n\t\t"1":"other"\n\t\}\n}' ).'\') ); return false;">json</a>
    <a href="#" onclick="$(\''.$FIELD_ALIAS.'\').insertAtCursor( (\''.htmlentities( '"column":{\n\t"type":"key"\n}' ).'\') ); return false;">key</a>
    <a href="#" onclick="$(\''.$FIELD_ALIAS.'\').insertAtCursor( (\''.htmlentities( '"column":{\n\t"type":"plainText"\n}' ).'\') ); return false;">plainText</a>
    <a href="#" onclick="$(\''.$FIELD_ALIAS.'\').insertAtCursor( (\''.htmlentities( '"column":{\n\t"type":"radio",\n\t"params":{\n\t\t"value1":"label1",\n\t\t"value2":"label2"\n\t\}\n}' ).'\') ); return false;">radio</a>
    <a href="#" onclick="$(\''.$FIELD_ALIAS.'\').insertAtCursor( (\''.htmlentities( '"column":{\n\t"type":"rel",\n\t"key":"tblid",\n\t"select":"column1, tbl.id as tblid",\n\t"from":"table as tbl",\n\t"where":{"w1":"tbl.id = FIELD_VALUE"},\n\t"order_by":"column2 ASC"\n}' ).'\') ); return false;">rel</a>
    <a href="#" onclick="$(\''.$FIELD_ALIAS.'\').insertAtCursor( (\''.htmlentities( '"column":{\n\t"type":"richText"\n}' ).'\') ); return false;">richText</a>
    <a href="#" onclick="$(\''.$FIELD_ALIAS.'\').insertAtCursor( (\''.htmlentities( '"column":{\n\t"type":"varlimit",\n\t"params":{\n\t\t"length":"number"\n\t}\n}' ).'\') ); return false;">varlimit</a>

  </div>

	<h2>'. JText::_( strtoupper( 'PRESETS' )) .'</h2>
  
	<div class="content">
		<a href="#" onclick="$(\''.$FIELD_ALIAS.'\').insertAtCursor( (\''.htmlentities('{\n\t"column":{}\n}').'\') ); return false;">STARTUP</a>
    <a href="#" onclick="$(\''.$FIELD_ALIAS.'\').insertAtCursor( (\''.htmlentities('{\n\n}').'\') ); return false;">{}</a>
    <a href="#" onclick="$(\''.$FIELD_ALIAS.'\').insertAtCursor( (\''.htmlentities('"":""').'\') ); return false;">"":""</a>
    <a href="#" onclick="$(\''.$FIELD_ALIAS.'\').insertAtCursor( (\''.htmlentities('"":\n{\n\t"":""\n}').'\') ); return false;">"":{"":""}</a>
	</div>

  <h2>'. JText::_( strtoupper( 'COMMANDS' )) .'</h2>
  
  <div class="content">
    <a href="#" onclick="$(\''.$FIELD_ALIAS.'\').insertAtCursor( this.get(\'text\') ); return false;">select</a>
    <a href="#" onclick="$(\''.$FIELD_ALIAS.'\').insertAtCursor( this.get(\'text\') ); return false;">from</a>
    <a href="#" onclick="$(\''.$FIELD_ALIAS.'\').insertAtCursor( this.get(\'text\') ); return false;">left_join</a>
    <a href="#" onclick="$(\''.$FIELD_ALIAS.'\').insertAtCursor( this.get(\'text\') ); return false;">right_join</a>
    <a href="#" onclick="$(\''.$FIELD_ALIAS.'\').insertAtCursor( this.get(\'text\') ); return false;">inner_join</a>
    <a href="#" onclick="$(\''.$FIELD_ALIAS.'\').insertAtCursor( this.get(\'text\') ); return false;">order_by</a>  
    <a href="#" onclick="$(\''.$FIELD_ALIAS.'\').insertAtCursor( this.get(\'text\') ); return false;">limit</a>    
    <a href="#" onclick="$(\''.$FIELD_ALIAS.'\').insertAtCursor( this.get(\'text\') ); return false;">having</a>  
    <a href="#" onclick="$(\''.$FIELD_ALIAS.'\').insertAtCursor( this.get(\'text\') ); return false;">where</a> 
    <a href="#" onclick="$(\''.$FIELD_ALIAS.'\').insertAtCursor( this.get(\'text\') ); return false;">ASC</a> 
    <a href="#" onclick="$(\''.$FIELD_ALIAS.'\').insertAtCursor( this.get(\'text\') ); return false;">DESC</a> 
  </div>
  
  <h2>'. JText::_( strtoupper( 'OPERATORS' )) .'</h2>
  
  <div class="content">
    <a href="#" onclick="$(\''.$FIELD_ALIAS.'\').insertAtCursor( this.get(\'text\') ); return false;">AND</a>
    <a href="#" onclick="$(\''.$FIELD_ALIAS.'\').insertAtCursor( this.get(\'text\') ); return false;">AS</a> 
    <a href="#" onclick="$(\''.$FIELD_ALIAS.'\').insertAtCursor( this.get(\'text\') ); return false;">ON</a> 
    <a href="#" onclick="$(\''.$FIELD_ALIAS.'\').insertAtCursor( this.get(\'text\') ); return false;">BETWEEN</a>
    <a href="#" onclick="$(\''.$FIELD_ALIAS.'\').insertAtCursor( this.get(\'text\') ); return false;">NOT BETWEEN</a>
    <a href="#" onclick="$(\''.$FIELD_ALIAS.'\').insertAtCursor( this.get(\'text\') ); return false;">BINARY</a>
    <a href="#" onclick="$(\''.$FIELD_ALIAS.'\').insertAtCursor( this.get(\'text\') ); return false;">CASE</a> 
    <a href="#" onclick="$(\''.$FIELD_ALIAS.'\').insertAtCursor( this.get(\'text\') ); return false;">DIV</a>  
    <a href="#" onclick="$(\''.$FIELD_ALIAS.'\').insertAtCursor( this.get(\'text\') ); return false;">IS NOT NULL</a>    
    <a href="#" onclick="$(\''.$FIELD_ALIAS.'\').insertAtCursor( this.get(\'text\') ); return false;">IS NOT</a>  
    <a href="#" onclick="$(\''.$FIELD_ALIAS.'\').insertAtCursor( this.get(\'text\') ); return false;">IS NULL</a>     
    <a href="#" onclick="$(\''.$FIELD_ALIAS.'\').insertAtCursor( this.get(\'text\') ); return false;">IS</a>  
    <a href="#" onclick="$(\''.$FIELD_ALIAS.'\').insertAtCursor( this.get(\'text\') ); return false;">LIKE</a> 
    <a href="#" onclick="$(\''.$FIELD_ALIAS.'\').insertAtCursor( this.get(\'text\') ); return false;">NOT LIKE</a> 
    <a href="#" onclick="$(\''.$FIELD_ALIAS.'\').insertAtCursor( this.get(\'text\') ); return false;">NOT REGEXP</a>  
    <a href="#" onclick="$(\''.$FIELD_ALIAS.'\').insertAtCursor( this.get(\'text\') ); return false;">NOT</a> 
    <a href="#" onclick="$(\''.$FIELD_ALIAS.'\').insertAtCursor( this.get(\'text\') ); return false;">OR</a>    
    <a href="#" onclick="$(\''.$FIELD_ALIAS.'\').insertAtCursor( this.get(\'text\') ); return false;">REGEXP</a>  
    <a href="#" onclick="$(\''.$FIELD_ALIAS.'\').insertAtCursor( this.get(\'text\') ); return false;">RLIKE</a> 
    <a href="#" onclick="$(\''.$FIELD_ALIAS.'\').insertAtCursor( this.get(\'text\') ); return false;">SOUNDS LIKE</a> 
    <a href="#" onclick="$(\''.$FIELD_ALIAS.'\').insertAtCursor( this.get(\'text\') ); return false;">XOR</a> 
  </div>   

</div>
';

echo '</td></tr></tbody></table>';


echo '
</div>
';


$JS_FIELD_TEXTAREA_TAB= '

///////////////////////////////////////////
// FIELD_TEXTAREA_TAB
///////////////////////////////////////////

//////////////////////////////
// DECLARE DOM READY
//////////////////////////////

window.addEvent("domready", function(){

	var indentor = new MooIndent($("'.$FIELD_ALIAS.'"));

});


';

$this->doc->addScriptDeclaration($JS_FIELD_TEXTAREA_TAB);



$JS_FIELD_ACCORDION = '

///////////////////////////////////////////
// FIELD_MASONRY
///////////////////////////////////////////

//////////////////////////////
// DECLARE DOM READY
//////////////////////////////

window.addEvent("domready", function(){
  new Fx.Accordion("#accordion_'.$FIELD_ALIAS.' h2", "#accordion_'.$FIELD_ALIAS.' .content",{
    fixedHeight: 320
  });
});

';

$this->doc->addScriptDeclaration($JS_FIELD_ACCORDION);