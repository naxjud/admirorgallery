<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.multiselect'); // Select/Deselect all
jimport('joomla.html.html.grid');
jimport('joomla.filesystem.file');

//$VIEW_PARAMS = json_decode($this->view['params']);

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

     <?php
        echo '<select name="filter_search_column" id="filter_search_column">';
        foreach ($this->items[0] as $FIELD_ALIAS => $FIELD_VALUE) {// Loop through Show Fields
            $selected = '';
            if ($this->escape($this->state->get('filter_search_column')) == $FIELD_ALIAS) {
                $selected = ' SELECTED';
            }
            echo '<option value="' . $FIELD_ALIAS . '"' . $selected . '>' . JText::_(strtoupper($FIELD_ALIAS)) . '</option>';
        }
        echo '</select>';
    ?>  

    <input type="text" name="filter_search_value" id="filter_search_value" value="<?php echo $this->escape($this->state->get('filter_search_value')); ?>" title="<?php echo JText::_('COM_CONTACT_SEARCH_IN_NAME'); ?>" />
    <button type="submit"><?php echo JText::_('COM_AVC_SEARCH'); ?></button>
    <button type="button" onclick="document.id('filter_search_value').value='';this.form.submit();"><?php echo JText::_('COM_AVC_CLEAR'); ?></button>

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

        if(!empty($this->items[0])){
            $FIELD_LIST = $this->items[0];
        }else{
            $FIELD_LIST = $this->fieldsArray;
        }

        foreach ($FIELD_LIST as $key => $value) {
            echo '<th>';
            echo JHtml::_('grid.sort', JText::_(strtoupper($key)), $key, $this->listDirn, $this->listOrder);
            echo '</th>'."\n";
        }

        echo '</tr>'."\n";
        echo '</thead>'."\n";


        // FORMAT DATA
        echo '<tbody>'."\n";

        if(!empty($this->items)){
        $row_counts = 0;
        foreach ($this->items as $item) { 

            $ROW_ID = 'row_' . $row_counts;

            echo '<tr id="'.$ROW_ID.'">'."\n";

            echo '<td style="display:none;">';
            echo '<input type="checkbox" class="cid" name="cid[]" value="' . $item->id . '" style="display:none" />';
            echo '</td>'."\n";

            foreach ($item as $FIELD_ALIAS => $FIELD_VALUE) {   

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
    require_once("adminlist_interface.php");
?>