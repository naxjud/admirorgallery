<?php

/**
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://docs.joomla.org/Developing_a_Model-View-Controller_Component_-_Part_1
 * @license    GNU/GPL
 */
// no direct access

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

/**
 * HTML View class for the HelloWorld Component
 *
 * @package    HelloWorld
 */
class AvcViewAvc extends JView {

    // Declare vars
    protected $doc;
    protected $views;
    protected $curr_view_id;  
    protected $curr_row_id;
    protected $view;  
    protected $view_fields;
    protected $listDirn;
    protected $listOrder;
    protected $itemsRow;
    protected $fieldsArray;

    function display($tpl = null) {


        JHTML::_('behavior.mootools'); 
        JHTML::_('behavior.mootools', 'more');

        // Update vars        
        $this->doc = JFactory::getDocument();
        $this->task = JRequest::getVar('task');
        $this->layout = JRequest::getVar('layout', 'default');
        $this->views = $this->get('Views');
        $this->curr_view_id = $this->get('CurrViewId');

        function execQuery($FIELD_PARAMS){
        ///////////////////////////////////////////////
        //  CREATE LISTING
        ///////////////////////////////////////////////
        $dbObject = JFactory::getDBO();
        $query = $dbObject->getQuery(true);
        $query->select( array( $FIELD_PARAMS["select"] ) );
        $query->from( $FIELD_PARAMS["from"] );

        // WHERE
        if(!empty($FIELD_PARAMS["where"])){
            foreach ($FIELD_PARAMS["where"] as $value) {
                if(!is_numeric($FIELD_VALUE)){
                    $value = str_replace("FIELD_VALUE", $dbObject->Quote($FIELD_VALUE), $value);
                }else{
                    $value = str_replace("FIELD_VALUE", $FIELD_VALUE, $value);
                }
                $query->where($value);
            }
        }

        // HAVING
        if(!empty($FIELD_PARAMS["having"])){
            foreach ($FIELD_PARAMS["having"] as $value) {       
                if(!is_numeric($FIELD_VALUE)){
                    $value = str_replace("FIELD_VALUE", $dbObject->Quote($FIELD_VALUE), $value);
                }else{
                    $value = str_replace("FIELD_VALUE", $FIELD_VALUE, $value);
                }
                $query->where($value);
            }
        }

        // LEFT JOIN
        if(!empty($FIELD_PARAMS["left_join"])){
            foreach ($FIELD_PARAMS["left_join"] as $value) { 
                $query->leftJoin($value);
            }
        }

        // ORDER
        if(!empty($FIELD_PARAMS["order_by"])){
            $query->order($FIELD_PARAMS["order_by"]);
        }

        $dbObject->setQuery($query);
        return $dbObject->loadAssocList();
    }

        // Track outputs in debug mode
        if(JDEBUG){
            echo "VIEWS:<br /><pre>";
            var_dump($this->views);
            echo "</pre><hr />";
        }

        switch ($this->layout) {

            case 'table':

                // Update vars
                $this->state = $this->get('State');   
                $this->listDirn = $this->state->get('filter_order_Dir');
                $this->listOrder = $this->state->get('filter_order');
                $this->items = $this->get('Items');
                $this->pagination = $this->get('Pagination');
                $this->fieldsArray = $this->get('FieldsArray');

                // Track outputs in debug mode
                if(JDEBUG){
                    echo "ITEMS:<br /><pre>";
                    var_dump($this->items);
                    echo "</pre><hr />";
                }

            break;

            case 'row':

                // Update vars
                $this->state = $this->get('State');   
                $this->listDirn = $this->state->get('filter_order_Dir');
                $this->listOrder = $this->state->get('filter_order');
                $this->items = $this->get('Items');    
                $this->curr_row_id = $this->get('CurrRowId'); 
                $this->fieldsArray = $this->get('FieldsArray'); 
                
                // Track outputs in debug mode
                if(JDEBUG){ 
                    echo "ITEMS:<br />";                   
                    var_dump($this->items);
                    echo "<hr />";
                }
                
            break;

            default:
            break;

        }

        // Render toolbar & view
        $this->addToolbar();
        parent::display($tpl);


        // Add after default head scirpt & styles loaded
        $this->doc->addStyleSheet( JURI::root() . 'administrator/components/com_avc/assets/template.css' ); 
        $this->doc->addStyleSheet( 'http://fonts.googleapis.com/css?family=Oswald:400,300,700&subset=latin,latin-ext' );
        $this->doc->addStyleSheet( 'http://fonts.googleapis.com/css?family=Reenie+Beanie&subset=latin,latin-ext,cyrillic' );

    }

    protected function addToolbar() {

        // Add title
        JToolBarHelper::title(JText::_('COM_AVC'), 'AVC_default');

        // Add buttons
        switch ($this->layout) {
            case 'table':
                JToolBarHelper::custom('add', 'AVC_add', 'AVC_add', JText::_('COM_AVC_ADD'), false, false);
                JToolBarHelper::custom('duplicate', 'AVC_duplicate', 'AVC_duplicate', JText::_('COM_AVC_DUPLICATE'), false, false);
                JToolBarHelper::custom('edit', 'AVC_edit', 'AVC_edit', JText::_('COM_AVC_EDIT'), false, false);
                JToolBarHelper::custom('delete', 'AVC_delete', 'AVC_delete', JText::_('COM_AVC_DELETE'), false, false);
                JToolBarHelper::custom('refresh', 'AVC_refresh', 'AVC_refresh', JText::_('COM_AVC_REFRESH'), false, false);

                break;
            case 'row':
                JToolBarHelper::custom('apply', 'AVC_apply', 'AVC_apply', JText::_('COM_AVC_APPLY'), false, false);
                JToolBarHelper::custom('save', 'AVC_save', 'AVC_save', JText::_('COM_AVC_SAVE_AND_CLOSE'), false, false);
                JToolBarHelper::custom('saveAndNew', 'AVC_save_and_new', 'AVC_save_and_new', JText::_('COM_AVC_SAVE_AND_NEW'), false, false);
                JToolBarHelper::custom('cancel', 'AVC_cancel', 'AVC_cancel', JText::_('COM_AVC_CANCEL'), false, false);
                JToolBarHelper::custom('refresh', 'AVC_refresh', 'AVC_refresh', JText::_('COM_AVC_REFRESH'), false, false);
                break;
            default:
        }
    }   

}
