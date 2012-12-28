<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.multiselect'); // Select/Deselect all
jimport('joomla.html.html.grid');
jimport('joomla.filesystem.file');

$VIEW_PARAMS = json_decode($this->view['params']);

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


<div class="fltlft">      

    <label class="filter-search-lbl" for="filter_search"><?php echo JText::_('COM_AVC_FILTER'); ?>&nbsp;&nbsp;</label>

    <?php
    echo '<select name="filter_search_column" id="filter_search_column">';
    foreach ($this->view_fields as $view_field) {
        $selected = '';
        if ($this->escape($this->state->get('filter_search_column')) == $view_field["name"]) {
            $selected = ' SELECTED';
        }
        echo '<option value="' . $view_field["name"] . '"' . $selected . '>' . JText::_(strtoupper($view_field["title"])) . '</option>';
    }
    echo '</select>';
    ?>  

    <input type="text" name="filter_search_value" id="filter_search_value" value="<?php echo $this->escape($this->state->get('filter_search_value')); ?>" title="<?php echo JText::_('COM_CONTACT_SEARCH_IN_NAME'); ?>" />
    <button type="submit"><?php echo JText::_('COM_AVC_SEARCH'); ?></button>
    <button type="button" onclick="document.id('filter_search_value').value='';this.form.submit();"><?php echo JText::_('COM_AVC_CLEAR'); ?></button>
</div>
<div class="fltrt">
<?php


$FILTER_TYPES = explode(",", $VIEW_PARAMS->filters);
if(!empty($FILTER_TYPES[0])){
foreach ($FILTER_TYPES as $SELECT_FIELD) {

    // Get field title
    foreach ($this->view_fields as $view_field) {
        if($view_field["name"]==$SELECT_FIELD)
        {
            $SELECT_FIELD_CONF = $view_field;
        }
    }

    // Get current entries
    $dbObject = JFactory::getDBO();
    $query = $dbObject->getQuery(true);
    $query->select( $dbObject->nameQuote( $SELECT_FIELD ) );
    $query->from( $dbObject->nameQuote( $this->view["name"] ) );
    $dbObject->setQuery($query);
    $AssocList = $dbObject->loadAssocList();

    // Populate options
    $OPTION = array();
    foreach($AssocList as $AssocList_value)
    {
        if($AssocList_value[$SELECT_FIELD]!=""){
            $OPTION[]=$AssocList_value[$SELECT_FIELD];
        }
    }
    $OPTION = array_unique($OPTION);
    $SELECT_FIELD_OPTIONS = array(); 
    foreach($OPTION as $value){

    $FIELD_VALUE = $value;

    if($SELECT_FIELD_CONF["type"]=="rel"){
        
        $FIELD_TYPE = $SELECT_FIELD_CONF["type"];
        $FIELD_PARAMS = json_decode($SELECT_FIELD_CONF['params']);
        $FIELD_REL = json_decode($SELECT_FIELD_CONF['relationship']);

        $dbObject = JFactory::getDBO();
        $query = $dbObject->getQuery(true);
        $query->select('*');
        $query->order($dbObject->getEscaped($FIELD_REL->key.' ASC'));
        $query->from($dbObject->nameQuote($FIELD_REL->table));
        $query->where($dbObject->nameQuote($FIELD_REL->key)."=".$dbObject->Quote($FIELD_VALUE));
        $dbObject->setQuery($query);
        $ROW = $dbObject->loadAssocList();

        if($FIELD_VALUE!=""){   
            $FIELD_VALUE = $ROW[0][$FIELD_PARAMS->label] . " (" . $FIELD_VALUE . ")";
        }

    }




        if($this->escape($this->state->get('filter_search_column')) == $SELECT_FIELD && $this->escape($this->state->get('filter_search_value')) == $value)
        {            
            $SELECT_FIELD_OPTIONS[] = JHTML::_('select.option', $value, $FIELD_VALUE, true);
        }else{
            $SELECT_FIELD_OPTIONS[] = JHTML::_('select.option', $value, $FIELD_VALUE);
        }
    }

    // Create select
    echo
    '
    <div style="float:left !important">
    <label style="">' . JText::_( strtoupper( $SELECT_FIELD_CONF["title"] ) ) . '</label>
    <select onchange="document.id(\'filter_search_column\').value=\'' . $SELECT_FIELD . '\'; document.id(\'filter_search_value\').value=this.value; this.form.submit();">
        <option value="">' . JText::_('COM_AVC_SELECT') . '</option>
        ' . JHtml::_('select.options', $SELECT_FIELD_OPTIONS) . '
    </select>
    </div>
    ';

}
}

?>

</div>

</fieldset>
<div class="clr"> </div>

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