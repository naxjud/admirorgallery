<?php
// Author: Igor Kekeljevic, 2012.
// No direct access
defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.formvalidation'); // Form Validation Libraries
JHTML::_('behavior.calendar'); // Callendar Libraries
JHTML::_('behavior.modal'); // Modal Libriries (SqueezeBox)

$this->doc->addScript(JURI::root() . 'administrator/components/com_ccs/assets/js/mootools-more-1.4-full.js');


$calendar_js = '
    // Calendar support
	function calendarInit(){
		Calendar.setup({
		        inputField     :    "entrydate",     // id of the input field
		        ifFormat       :    "%Y-%m-%d",      // format of the input field
		        button         :    "entrydate_img",  // trigger for the calendar (button ID)
		        align          :    "Bl",           // alignment (defaults to "Bl" = Bottom Left, 
		// "Tl" = Top Left, "Br" = Bottom Right, "Bl" = Botton Left)
		        singleClick    :    true
		    });
}
';

$JS_validation = '
    // Validation support
    Joomla.submitbutton = function(task){
    if(task=="cancel"){
        Joomla.submitform(task);
    }else{
        var f = document.adminForm;
        if (document.formvalidator.isValid(f)) {
            Joomla.submitform(task);
        }else{
				var answer = confirm("' . JText::_("COM_CCS_TABLE_FIELDS_ERROR") . '");
				if (answer){
					Joomla.submitform(task);
				}
        }
    }
    return false;
    }
';

$JS_varcharLimited = '
// varcharLimited_array
var varcharLimited_array=new Array();

// varcharLimited
function varcharLimited_validator(event,item_alias, item_id, item_value){
	if(event.keyCode==9){// Ignore TAB
		return;
	}
	if(event.keyCode==16){// Ignore SHIFT
		return;
	}

	// Update value for post
	varcharLimited_array[item_alias][item_id]=item_value;
	$(item_alias).value=varcharLimited_array[item_alias].join("");

	// Update focus field
	var current_value = $(item_alias+"_"+item_id).get("value");
	current_length = current_value.length;
	if(current_length>0){
		if ($(item_alias+"_"+(item_id+1))) {
			$(item_alias+"_"+(item_id+1)).selectRange(0,1).focus();
		}
	}else{
		if ($(item_alias+"_"+(item_id-1))) {
			$(item_alias+"_"+(item_id-1)).selectRange(0,1).focus();
		}
	}

}
';

$JS_imageFieldType = '
var imgFld_id, imgFld_url;

function imgFld_refresh(){
	$(imgFld_id).value=imgFld_url;
	if(imgFld_url!=""){
		$("img_"+imgFld_id).set("src","' . JURI::root() . '"+imgFld_url);	
	}else{
		$("img_"+imgFld_id).set("src","' . JURI::root() . 'administrator/components/com_ccs/assets/images/no-image.png");
	}
}
 
function jInsertFieldValue(url_value){
	imgFld_url=url_value;
	imgFld_refresh()
}
';

$JS = '
var newTABLE,newTR,newTD,newHR;

// Form Template
var tmp_els,tmp_item,tmp_item_height;
var tmp_num_of_cols = 0;
var tmp_curr_col = -1;
var tmp_col_heigths = new Array();

function tmp_select_col(ii){
	if(tmp_curr_col==tmp_num_of_cols){
		tmp_curr_col=0;
	}
	if(tmp_curr_col>0){// BACKWARD CORRECTION
		if(tmp_col_heigths[tmp_curr_col]+tmp_item_height>tmp_col_heigths[tmp_curr_col-1]){
			tmp_curr_col--;
			tmp_select_col(ii);
		}
	}
	if(tmp_curr_col<(tmp_num_of_cols-1)){// FORWARD CORRECTION
		if(tmp_col_heigths[tmp_curr_col]>tmp_col_heigths[tmp_curr_col+1]+tmp_item_height){
			tmp_curr_col++;
			tmp_select_col(ii);
		}
	}
}

function tmp_init(){
	tmp_els = document.getElements(".form_items");	
	var el_dimensions = tmp_els[0].getSize();
	var dimensions = document.id("element-box").getSize();
	tmp_num_of_cols = Math.floor(dimensions.x/el_dimensions.x);

	newTABLE = document.createElement("table");
	newTR = document.createElement("tr");
	document.getElement(".adminform").appendChild(newTABLE)
									 .set("width","100%")
									 .set("cellpadding","0")
									 .set("cellmargin","0")
									 .set("border","0")
									 .set("id","tmp_tb")
									 .appendChild(newTR);

	for(var i=0; i<tmp_num_of_cols; i++){
		tmp_col_heigths[i]=0;		
		newTD = document.createElement("td");
		document.getElement("#tmp_tb tr").appendChild(newTD)
										 .set("class","tmp_col")
										 .set("id","tmp_col_"+i);
	}

	for(var ii=0; ii<tmp_els.length; ii++){
		tmp_item = tmp_els[ii];
		dimensions = tmp_item.getSize();
		tmp_item_height = dimensions.y;
	
		tmp_curr_col++;
		tmp_select_col(ii);

		tmp_col_heigths[tmp_curr_col]+=tmp_item_height;
		tmp_item.clone(true,true).inject(document.id("tmp_col_"+tmp_curr_col));

		newHR = document.createElement("hr");
		document.id("tmp_col_"+tmp_curr_col).appendChild(newHR);

		tmp_item.destroy();
	}
}

function cxb_change(field_alias){
	var cxb_array = new Array();
	var cxbs = document.getElements(".cxb_"+field_alias+":checked");
	for(var i=0; i<cxbs.length; i++){
		cxb_array[i]=cxbs[i].get("value");
	}
	document.id(field_alias).value = cxb_array.join(",");
}

window.addEvent("domready", function(){ 

	if($$(".calendar").length>0){
		calendarInit();
	}

	if($$("a.modal").length>0){
		SqueezeBox.initialize({});
			SqueezeBox.assign($$("a.modal"), {
				parse: \'rel\'
			});
	}

	tmp_init();

});
';

$this->doc->addScriptDeclaration($calendar_js);
$this->doc->addScriptDeclaration($JS_validation);
$this->doc->addScriptDeclaration($JS_varcharLimited);
$this->doc->addScriptDeclaration($JS_imageFieldType);
$this->doc->addScriptDeclaration($JS);
?>

<div class="<?php echo $this->alias; ?>">
    <form enctype="multipart/form-data" action="index.php" method="post" name="adminForm" class="form-validate">

        <fieldset class="adminform form-validate">

            <h2><?php echo JText::_(strtoupper($this->alias)) . " | " . JText::_(strtoupper("COM_CCS_" . $this->task)); ?></h2>

<?php
if ($this->row["id"] && $this->task != "duplicate") {// Patch for add and duplicate	
    echo '<input type="hidden" name="id" value="' . $this->row["id"] . '" />';
    echo '<input type="hidden" name="cid[]" value="' . $this->row["id"] . '" />';
}

$tabIndex = 0;

foreach ($this->fields as $field) {// Loop through Show Fields

    $field_alias = $field["fld_alias"];
    $field_params = $field["fld_params"];
    $field_type = $field["fld_type"];

    if ($field_alias != "id") {

        $field_value = $this->row[$field_alias];

        $tabIndex++;

        // SET DEFAULT FIELD TEMPLATE
        $fld_types_file = JPATH_COMPONENT . DS . "views" . DS . "ccs" . DS . "tmpl" . DS . "fld_types" . DS . "row" . DS . "default.php";

        if ($field_type) {
            $fld_types_file_test = JPATH_COMPONENT . DS . "views" . DS . "ccs" . DS . "tmpl" . DS . "fld_types" . DS . "row" . DS . $field_type . ".php";
            if (file_exists($fld_types_file_test)) {
                $fld_types_file = $fld_types_file_test;
            }
        }

        require($fld_types_file); // Load template file
    }//IF != ID
}//foreach ($this->fields as $field)
?>



        </fieldset>

            <?php
            require_once('personal_notes.php');
            ?>


        <input type="hidden" name="option" value="com_ccs" />
        <input type="hidden" name="task" value="<?php echo $this->task; ?>" />
        <input type="hidden" name="layout" value="row" />
        <input type="hidden" name="controller" value="ccs" />
        <input type="hidden" name="alias" value="<?php echo $this->alias; ?>" />
        <input type="hidden" name="filter_order" id="filter_order" value="<?php echo $this->listOrder; ?>" />
        <input type="hidden" name="filter_order_Dir" id="filter_order_Dir" value="<?php echo $this->listDirn; ?>" />
        <input type="hidden" name="breadcrumbmenuState" id="breadcrumbmenuState" value="<?php echo $this->breadcrumbmenuState; ?>" />
        <input type="hidden" name="filter_search_value" value="<?php echo JRequest::getVar('filter_search_value'); ?>" />
        <input type="hidden" name="filter_search_column" value="<?php echo JRequest::getVar('filter_search_column'); ?>" />

    </form>

</div>

<?php
    require_once("forcedStyles.php");
?>

