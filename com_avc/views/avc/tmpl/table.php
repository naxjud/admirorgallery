<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.multiselect'); // Select/Deselect all
jimport('joomla.html.html.grid');
jimport('joomla.filesystem.file');

//$VIEW_PARAMS = json_decode($this->view['params']);


require_once("table_requireBefore.php");

?>

<div>
    <h1 class="pageTitle"><?php echo JText::_(strtoupper($this->views[$this->curr_view_id]["name"])); ?></h1>
    <form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="adminForm" id="adminForm">
        <table cellpadding="0" cellspacing="0" border="0" width="100%">
            <tbody>
                <tr>
                    <td class="AVC_sidePanel">
                        
                        <!-- MENU -->
                        <?php
                            require_once("menu.php");
                        ?>

                    </td>
                    <td class="AVC_tableView">

<!-- =========================================================================== -->
<!-- TABLE -->
<!-- =========================================================================== -->


<fieldset id="filter-bar">

<div class="fltlft">      

    <?

    $AVC_having_value = "";
    $AVC_having_search = JRequest::getVar('filter_search_value');
    if( !empty( $AVC_having_search ) ){
        $AVC_having_split = explode("%", $AVC_having_search );
        $AVC_having_value = $AVC_having_split[1];
    }

    ?>

    <input type="text" onkeyup="AVC_SEARCH_UPDATE(document.id('filter_search_value_input').value);" name="filter_search_value_input" id="filter_search_value_input" value="<?php echo $AVC_having_value;?>" title="<?php echo JText::_('COM_CONTACT_SEARCH_IN_NAME'); ?>" />
    <button type="button" onclick="AVC_SEARCH_UPDATE(document.id('filter_search_value_input').value);document.adminForm.submit();"><?php echo JText::_('COM_AVC_SEARCH'); ?></button>
    <button type="button" onclick="AVC_SEARCH_UPDATE('');document.adminForm.submit();"><?php echo JText::_('COM_AVC_CLEAR'); ?></button>
    <input type="hidden" name="filter_search_value" id="filter_search_value" value="<?php echo $AVC_having_search;?>" />

</div>
<div class="fltrt">

</div>

</fieldset>
<div class="clr"> </div>

<div class="AVC_table_wrap">
    <table id="adminlist" class="adminlist" cellspacing="0" cellpadding="0" border="0" width="100%">
    <?php

        // FORMAT HEADER  
        echo '<thead>'."\n";
        echo '<tr>'."\n";

        echo '<th style="display:none;"></th>';

        if ( !empty($this->fieldsArray) ) {
            foreach ($this->fieldsArray as $fields_key => $fields_value) {
                echo '<th>';
                echo JHtml::_('grid.sort', JText::_(strtoupper($fields_value)), $fields_value, $this->listDirn, $this->listOrder);
                echo '</th>'."\n";
            }
        }

        echo '</tr>'."\n";
        echo '</thead>'."\n";


        // FORMAT DATA
        echo '<tbody>'."\n";

        if( !empty($this->items) && !empty($this->fieldsArray) ){
        $row_counts = 0;
        foreach ($this->items as $item) { 

            $ROW_ID = 'row_' . $row_counts;

            echo '<tr id="'.$ROW_ID.'">'."\n";

            echo '<td style="display:none;">';
            echo '<input type="checkbox" class="cid" name="cid[]" value="' . $item->id . '" style="display:none" />';
            echo '</td>'."\n";

            foreach ($this->fieldsArray as $FIELD_ALIAS) {

                    $FIELD_VALUE = $item->$FIELD_ALIAS;

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

                    echo '<td>';
                        // SET DEFAULT FIELD TEMPLATE
                        $fld_types_file = JPATH_COMPONENT . DS . "views" . DS . "avc" . DS . "tmpl" . DS . "fld_types" . DS . "table" . DS . "default.php";
                        if ($FIELD_TYPE) {
                            $fld_types_file_test = JPATH_COMPONENT . DS . "views" . DS . "avc" . DS . "tmpl" . DS . "fld_types" . DS . "table" . DS . $FIELD_TYPE . ".php";
                            if (file_exists($fld_types_file_test)) {
                                $fld_types_file = $fld_types_file_test;
                            }
                        }
                        require($fld_types_file); // Load template file
                    echo '</td>'."\n";

            }

            echo '</tr>'."\n";
            $row_counts++;
        }
        }//if(!empty($this->items)){
        
        echo '</tbody>'."\n";


        ?>


    </table>
</div>
<div class="AVC_pagination_wrap">
<?php echo $this->pagination->getListFooter(); ?>
</div>
<br style="clear:both;" />



                        <!-- PERSONAL NOTES -->
                        <?php
                            require_once('personal_notes.php');
                        ?>

                    </td>
                </tr>
            </tbody>
        </table>

        <input type="hidden" name="option" value="com_avc" />
        <input type="hidden" name="controller" value="avc" />
        <input type="hidden" name="layout" id="layout" value="table" />        
        <input type="hidden" name="task" id="task" value="" />
        <input type="hidden" name="curr_view_id" id="curr_view_id" value="<?php echo $this->curr_view_id;?>" />
        <input type="hidden" name="filter_order" id="filter_order" value="<?php echo $this->listOrder; ?>" />
        <input type="hidden" name="filter_order_Dir" id="filter_order_Dir" value="<?php echo $this->listDirn; ?>" />       
        <input type="hidden" name="ordering_id" id="ordering_id" value="" />       
        <input type="hidden" name="ordering_pos" id="ordering_pos" value="" />
        <input type="hidden" name="ordering_targetId" id="ordering_targetId" value="" />


    </form>
</div>

<?php
    require_once("forcedStyles.php");
    require_once("table_requireAfter.php");
?>