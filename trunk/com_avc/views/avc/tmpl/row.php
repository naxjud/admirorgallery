<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.modal'); // Modal Libriries (SqueezeBox)

?>

<div>
    <h1 class="pageTitle"><?php echo JText::_(strtoupper($this->views[$this->curr_view_id]["name"])) . " | " . JText::_(strtoupper("COM_AVC_" . $this->task)); ?></h1>
    <form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="adminForm" id="adminForm">

        <div id="masonry">

        <?php

        if(!empty($this->items[0])){
            $FIELD_LIST = $this->items[0];
        }else{
            $FIELD_LIST = $this->fieldsArray;
        }

        $TABINDEX = 0;        

        foreach ($FIELD_LIST as $FIELD_ALIAS => $FIELD_VALUE) {// Loop through Show Fields

            if($this->curr_row_id==0){// PATHC FOR NEW ITEM
                $FIELD_VALUE = "";
            }

            if(!empty($this->views[$this->curr_view_id]["fields_config"][$FIELD_ALIAS]["type"])){
                $FIELD_TYPE = $this->views[$this->curr_view_id]["fields_config"][$FIELD_ALIAS]["type"];
            }else{
                $FIELD_TYPE = "";
            }
            
            if(!empty($this->views[$this->curr_view_id]["fields_config"][$FIELD_ALIAS]["params"])){
                $FIELD_PARAMS = $this->views[$this->curr_view_id]["fields_config"][$FIELD_ALIAS]["params"]; 
            }else{
                $FIELD_PARAMS = "";
            }

            $FIELD_TITLE = $FIELD_ALIAS; 

            if( $FIELD_ALIAS == "id" ){$FIELD_TYPE = "key";}

            // VAR DEFINITIONS
            $TABINDEX++;

            //////////////////////////////
            // LOAD FIELD TEMPLATE
            //////////////////////////////
            $fld_types_file = JPATH_COMPONENT . DS . "views" . DS . "avc" . DS . "tmpl" . DS . "fld_types" . DS . "row" . DS . "default.php";
            if ($FIELD_TYPE) {
                $fld_types_file_test = JPATH_COMPONENT . DS . "views" . DS . "avc" . DS . "tmpl" . DS . "fld_types" . DS . "row" . DS . $FIELD_TYPE . ".php";
                if (file_exists($fld_types_file_test)) {
                    $fld_types_file = $fld_types_file_test;
                }
            }
            require($fld_types_file); // Load template file

       }

        if ($this->task == "duplicate") {// Patch for duplicate
            $this->curr_row_id = 0;
        }


        ?>

        </div> 

        <!-- PERSONAL NOTES -->
        <?php
            require_once('personal_notes.php');
        ?>
        
        <input type="hidden" name="option" value="com_avc" />
        <input type="hidden" name="controller" value="avc" />
        <input type="hidden" name="task" id="task" value="" />
        <input type="hidden" name="layout" id="layout" value="row" /> 
        <input type="hidden" name="cid[]" id="cid" value="<?php echo $this->curr_row_id;?>" />
        <input type="hidden" name="curr_view_id" id="curr_view_id" value="<?php echo $this->curr_view_id;?>" />
        <input type="hidden" name="filter_order" id="filter_order" value="<?php echo $this->listOrder; ?>" />
        <input type="hidden" name="filter_order_Dir" id="filter_order_Dir" value="<?php echo $this->listDirn; ?>" />
        <input type="hidden" name="filter_search_value" id="filter_search_value" value="<?php echo JRequest::getVar('filter_search_value'); ?>" />


    </form>
</div>

<?php
    require_once("forcedStyles.php");
    require_once("row_requireOnce.php");
    $this->doc->addScript(JURI::root() . 'administrator/components/com_avc/assets/js/mooMasonry.js');
    $this->doc->addScript(JURI::root() . 'administrator/components/com_avc/assets/js/mooIndent.js');
?>

