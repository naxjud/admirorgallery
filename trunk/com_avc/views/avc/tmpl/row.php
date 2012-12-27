<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.modal'); // Modal Libriries (SqueezeBox)

?>

<div>
    <h1 class="pageTitle"><?php echo JText::_(strtoupper($this->view["title"])) . " | " . JText::_(strtoupper("COM_AVC_" . $this->task)); ?></h1>
    <form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="adminForm" id="adminForm">

        <fieldset class="adminform form-validate">

        <?php


        $TABINDEX = 0;

        foreach ($this->view_fields as $view_field) {// Loop through Show Fields

            if ($view_field['name'] != $this->view["key_field_name"]) {

                // VAR DEFINITIONS
                $TABINDEX++;
                $FIELD_NAME = $view_field['name'];
                $FIELD_TITLE = $view_field['title'];
                $FIELD_VALUE = $this->itemsRow[$view_field['name']];
                $FIELD_TYPE = $view_field['type'];
                $FIELD_PARAMS = json_decode($view_field['params']);
                $FIELD_REL = json_decode($view_field['relationship']);

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

       }

        if ($this->task == "duplicate") {// Patch for duplicate
            $this->curr_row_id = 0;
        }


        ?>

        </fieldset> 

        <!-- PERSONAL NOTES -->
        <?php
            require_once('personal_notes.php');
        ?>
        
        <input type="hidden" name="option" value="com_avc" />
        <input type="hidden" name="controller" value="avc" />
        <input type="hidden" name="task" id="task" value="" />
        <input type="hidden" name="cid[]" id="cid" value="<?php echo $this->curr_row_id;?>" />
        <input type="hidden" name="curr_view_id" id="curr_view_id" value="<?php echo $this->curr_view_id;?>" />
        <input type="hidden" name="filter_order" id="filter_order" value="<?php echo $this->listOrder; ?>" />
        <input type="hidden" name="filter_order_Dir" id="filter_order_Dir" value="<?php echo $this->listDirn; ?>" />

    </form>
</div>

<?php
    require_once("forcedStyles.php");
    require_once("row_requireOnce.php");
?>