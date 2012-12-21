<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.multiselect'); // Select/Deselect all
jimport('joomla.html.html.grid');
jimport('joomla.filesystem.file');

?>

<div>
    <h1 class="pageTitle"><?php echo JText::_(strtoupper($this->view["title"])); ?></h1>
    <form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="adminForm" id="adminForm">
        <table cellpadding="0" cellspacing="0" border="0" width="100%">
            <tbody>
                <tr>
                    <td class="AVC_sidePanel">
                        <label><?php echo JText::_('COM_AVC_MENU'); ?></label>
                        
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
<div class="filter-search fltlft">        
    <label class="filter-search-lbl" for="filter_search"><?php echo JText::_('COM_AVC_FILTER'); ?>&nbsp;&nbsp;</label>

    <?php
    echo '<select name="filter_search_column" id="filter_search_column">';
    foreach ($this->view_fields as $view_field) {
        $selected = '';
        if ($this->escape($this->state->get('filter_search_column')) == $view_field["name"]) {
            $selected = ' SELECTED';
        }
        echo '<option value="' . $view_field["name"] . '"' . $selected . '>' . JText::_(strtoupper($view_field["name"])) . '</option>';
    }
    echo '</select>';
    ?>  

    <input type="text" name="filter_search_value" id="filter_search_value" value="<?php echo $this->escape($this->state->get('filter_search_value')); ?>" title="<?php echo JText::_('COM_CONTACT_SEARCH_IN_NAME'); ?>" />
    <button type="submit"><?php echo JText::_('COM_AVC_SEARCH'); ?></button>
    <button type="button" onclick="document.id('filter_search_value').value='';this.form.submit();"><?php echo JText::_('COM_AVC_CLEAR'); ?></button>
</div>
<div class="filter-select fltrt"> 
</div>

</fieldset>

<div class="AVC_table_wrap">
    <table id="adminlist" class="adminlist" cellspacing="0" cellpadding="0" border="0" width="100%">

        <?php

        // FORMAT HEADER  
        echo '<thead>';
        echo '<tr>';

        echo '<th>';
        echo JHtml::_('grid.sort', JText::_(strtoupper($this->view["key_field_name"])), $this->view["key_field_name"], $this->listDirn, $this->listOrder);
        echo '</th>';

        foreach ($this->view_fields as $view_field) {
            echo '<th>';
            echo JHtml::_('grid.sort', JText::_(strtoupper($view_field["title"])), $view_field["name"], $this->listDirn, $this->listOrder);
            echo '</th>';
        }

        echo '</tr>';
        echo '</thead>';

        // FORMAT DATA
        echo '<tbody>';

        $row_counts = 0;
        foreach ($this->items as $item) { 

            $ROW_ID = 'row_' . $row_counts;

            echo '<tr id="' . $ROW_ID . '">';

            foreach ($item as $FIELD_ALIAS => $FIELD_VALUE) {


                if($FIELD_ALIAS == $this->view["key_field_name"]){           
                    echo '<td>';
                    echo '<input type="checkbox" class="cid" name="cid[]" value="' . $FIELD_VALUE . '" style="display:none" />';
                    echo $FIELD_VALUE;                    
                    echo '</td>';
                }else{                    
                    echo '<td>';

                    // GET FIELD DETAILS
                    foreach ($this->view_fields as $view_field) {
                        if($view_field["name"] == $FIELD_ALIAS){ 
                            $FIELD_TYPE = $view_field["type"];
                            $FIELD_PARAMS = json_decode($view_field['params']);
                            $FIELD_REL = json_decode($view_field['relationship']);
                        }
                    }

                    // SET DEFAULT FIELD TEMPLATE
                    $fld_types_file = JPATH_COMPONENT . DS . "views" . DS . "avc" . DS . "tmpl" . DS . "fld_types" . DS . "table" . DS . "default.php";
                    if ($FIELD_TYPE) {
                        $fld_types_file_test = JPATH_COMPONENT . DS . "views" . DS . "avc" . DS . "tmpl" . DS . "fld_types" . DS . "table" . DS . $FIELD_TYPE . ".php";
                        if (file_exists($fld_types_file_test)) {
                            $fld_types_file = $fld_types_file_test;
                        }
                    }
                    require($fld_types_file); // Load template file

                    echo '</td>';
                }
                
            }

            echo '</tr>';
            $row_counts++;
        }
        
        echo '</tbody>';

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