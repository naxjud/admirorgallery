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

    function display($tpl = null) {


        JHTML::_('behavior.mootools'); 
        JHTML::_('behavior.mootools', 'more');

        // Update vars        
        $this->doc = JFactory::getDocument();
        $this->task = JRequest::getVar('task');
        $this->layout = JRequest::getVar('layout', 'default');
        $this->views = $this->get('Views');
        $this->curr_view_id = $this->get('CurrViewId');

        // Track outputs in debug mode
        if(JDEBUG){
            echo "VIEWS:<br />";
            var_dump($this->views);
            echo "<hr />";
        }

        switch ($this->layout) {

            case 'table':

                // Update vars
                $this->state = $this->get('State');   
                $this->listDirn = $this->state->get('filter_order_Dir');
                $this->listOrder = $this->state->get('filter_order');
                $this->items = $this->get('Items');
                $this->pagination = $this->get('Pagination');

                // Track outputs in debug mode
                if(JDEBUG){
                    echo "ITEMS:<br />";
                    var_dump($this->items);
                    echo "<hr />";
                }

            break;

            case 'row':

                // Update vars
                $this->state = $this->get('State');   
                $this->listDirn = $this->state->get('filter_order_Dir');
                $this->listOrder = $this->state->get('filter_order');
                $this->items = $this->get('Items');    
                $this->curr_row_id = $this->get('CurrRowId');  
                
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
        $this->doc->addStyleSheet( 'http://fonts.googleapis.com/css?family=Unica+One|Homenaje&subset=latin,latin-ext' );
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
