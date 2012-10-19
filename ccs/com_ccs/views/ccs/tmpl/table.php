<?php
// No direct access
defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.multiselect'); // Select/Deselect all
jimport('joomla.html.html.grid');
jimport('joomla.filesystem.file');

$document = JFactory::getDocument();

$this->doc->addScript(JURI::root() . 'administrator/components/com_ccs/assets/js/mootools-more-1.4-full.js');

require_once('adminlist_interface.php');

$JS = '
function tblEdit(tblEdit_id,tblEdit_alias,tblEdit_value){
    $$("#task").set("value", "save");
    $$("#id").set("value", tblEdit_id);
    $$("#tblEdit_alias").set("value", tblEdit_alias);
    $$("#tblEdit_value").set("value", tblEdit_value);
    document.adminForm.submit();
}

window.addEvent("domready", function(){ 

});
';
$document->addScriptDeclaration($JS);
?>    
<div class="<?php echo $this->alias; ?>">
    <h2><?php echo JText::_(strtoupper($this->alias)); ?></h2>
    <form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="adminForm" id="adminForm">
        <table cellpadding="0" cellspacing="0" border="0" width="100%" style="table-layout:fixed">
            <tbody>
                <tr>
                    <td class="CCS_sidePanel">
                        <label><?php echo JText::_('COM_CCS_MENU'); ?></label>
                        <input type="text" name="p" title="<?php echo JText::_("COM_CCS_BREADCRUMBMENU_FILTER"); ?>" id="breadcrumbMenu_input" />
                        <ul id="breadcrumbMenu"></ul>
                        <?php
                        require_once("breadcrumbMenu.php");
                        ?>
                    </td>
                    <td class="CCS_tableView">



                        <!-- ################################################################################################################## -->
                        <!-- ################################################################################################################## -->


                        <fieldset id="filter-bar">
                            <div class="filter-search fltlft">        
                                <label class="filter-search-lbl" for="filter_search"><?php echo JText::_('COM_CCS_FILTER'); ?>&nbsp;&nbsp;</label>

                                <?php
                                echo '<select name="filter_search_column" id="filter_search_column">';
                                foreach ($this->fields as $field) {
                                    $selected = '';
                                    if ($this->escape($this->state->get('filter_search_column')) == $field["fld_alias"]) {
                                        $selected = ' SELECTED';
                                    }
                                    echo '<option value="' . $field["fld_alias"] . '"' . $selected . '>' . JText::_(strtoupper($field["fld_alias"])) . '</option>';
                                }
                                echo '</select>';
                                ?>	

                                <input type="text" name="filter_search_value" id="filter_search_value" value="<?php echo $this->escape($this->state->get('filter_search_value')); ?>" title="<?php echo JText::_('COM_CONTACT_SEARCH_IN_NAME'); ?>" />
                                <button type="submit"><?php echo JText::_('COM_CCS_SEARCH'); ?></button>
                                <button type="button" onclick="document.id('filter_search_value').value='';this.form.submit();"><?php echo JText::_('COM_CCS_CLEAR'); ?></button>
                            </div>
                            <div class="filter-select fltrt">
                                <!-- Here goes options on the right side -->		    
                            </div>
                        </fieldset>

                        <div class="CCS_table_wrap">
                            <table id="adminlist" class="adminlist" cellspacing="0" cellpadding="0" border="0" width="100%">
                                <?php
// FORMAT HEADER  
                                echo '<thead>';
                                echo '<tr>';
                                echo '<th style="display:none;">';
                                echo '</th>';
                                foreach ($this->fields as $field) {
                                    echo '<th>';
                                    echo JHtml::_('grid.sort', JText::_(strtoupper($field["fld_alias"])), $field["fld_alias"], $this->listDirn, $this->listOrder);
                                    echo '</th>';
                                }
                                echo '</tr>';
                                echo '</thead>';

// FORMAT DATA
                                echo '<tbody>';
                                foreach ($this->items as $i => $item) {

                                    echo '<tr id="row' . $i . '">';
                                    //echo '<td  style="display:none;'.$float.'">';
                                    echo '<td  style="display:none;">';
                                    echo '<input type="checkbox" name="cid[]" id="cb' . $i . '" value="' . $item->id . '" style="display:none" />';
                                    echo '</td>';

                                    $index = 0;

                                    foreach ($item as $field_value) {

                                        $field_alias = $this->fields[$index]["fld_alias"];
                                        $field_params = $this->fields[$index]["fld_params"];
                                        $field_type = $this->fields[$index]["fld_type"];

                                        $attributes = "";
                                        if ($field_type == "number") {
                                            $attributes.=' class="number"';
                                        }
                                        echo '<td' . $attributes . '>';

                                        // SET DEFAULT FIELD TEMPLATE
                                        $fld_types_file = JPATH_COMPONENT . DS . "views" . DS . "ccs" . DS . "tmpl" . DS . "fld_types" . DS . "table" . DS . "default.php";

                                        if ($field_type) {
                                            $fld_types_file_test = JPATH_COMPONENT . DS . "views" . DS . "ccs" . DS . "tmpl" . DS . "fld_types" . DS . "table" . DS . $field_type . ".php";
                                            if (file_exists($fld_types_file_test)) {
                                                $fld_types_file = $fld_types_file_test;
                                            }
                                        }

                                        require($fld_types_file); // Load template file

                                        echo '</td>';

                                        $index++;
                                    }

                                    echo '</tr>';
                                }
                                echo '</tbody>';
                                ?>

                            </table>
                        </div>

                        <div class="CCS_pagination_wrap">
                            <?php echo $this->pagination->getListFooter(); ?>
                        </div>
                        <br style="clear:both;" />
                        <?php
                        require_once('personal_notes.php');
                        ?>



                    </td>
                </tr>
            </tbody>
        </table>

        <input type="hidden" name="option" value="com_ccs" />
        <input type="hidden" name="layout" id="layout" value="table" />
        <input type="hidden" name="task" id="task" value="" />
        <input type="hidden" name="controller" value="ccs" />
        <input type="hidden" name="alias" id="alias" value="<?php echo $this->alias; ?>" />
        <input type="hidden" name="filter_order" id="filter_order" value="<?php echo $this->listOrder; ?>" />
        <input type="hidden" name="filter_order_Dir" id="filter_order_Dir" value="<?php echo $this->listDirn; ?>" />
        <input type="hidden" name="breadcrumbmenuState" id="breadcrumbmenuState" value="<?php echo $this->breadcrumbmenuState; ?>" />
        <input type="hidden" name="adminListSorting" id="adminListSorting" value="" />

        <input type="hidden" name="id" id="id" value="" />
        <input type="hidden" name="tblEdit_alias" id="tblEdit_alias" value="" />
        <input type="hidden" name="tblEdit_value" id="tblEdit_value" value="" />

    </form>
</div>

