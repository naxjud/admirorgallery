<?php
/**
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://docs.joomla.org/Developing_a_Model-View-Controller_Component_-_Part_1
 * @license    GNU/GPL
*/
 
// no direct access
 
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.view');
 
/**
 * HTML View class for the HelloWorld Component
 *
 * @package    HelloWorld
 */
 
class CcsViewCcs extends JView
{

    // Declare vars
    protected $doc;
	protected $items;
	protected $fields;	
	protected $row;
	protected $pagination;
	protected $state;
	protected $listDirn;
	protected $listOrder;
	protected $task;	
	protected $alias;// Used for current_db_name
	protected $dbChildren;	
	protected $menuItems;// id, default, alias, name, image, parent
	protected $breadcrumbmenuState;
	protected $quickIcons;
        
    function display($tpl = null)
    {
    	
        // Add CSS
        $this->doc = &JFactory::getDocument(); 
		$this->doc->addStyleSheet(JURI::root().'administrator/components/com_ccs/assets/template.css');
        
        $this->alias=$this->get('Alias');
        
        // Update vars        
        $this->task         = JRequest::getVar( 'task' ); 
        $this->layout       = JRequest::getVar( 'layout', 'default' );  
        
        if($this->layout != "default"){

			$this->fields				= $this->get('Fields');
			$this->state				= $this->get('State'); 
			$this->pagination			= $this->get('Pagination');
			$this->listDirn				= $this->state->get('filter_order_Dir');
			$this->listOrder			= $this->state->get('filter_order');
			$this->breadcrumbmenuState	= $this->state->get('breadcrumbmenuState');

			switch(true) {
			case JRequest::getVar( 'task' )=='add':
			break;		
			case JRequest::getVar( 'task' )=='edit' || JRequest::getVar( 'task' )=='duplicate':	
				$this->row					= $this->get('Row');
			break;
			default:// TABLE PAGE
				$this->items				= $this->get('Items');
				$this->dbChildren			= $this->get('Children');
				$this->menuItems			= $this->get('MenuItems');			
			break;
			}

        }else{// HOME PAGE
			$this->menuItems			= $this->get('MenuItems');
			$this->quickIcons			= $this->get('QuickIcons');
			$this->state				= $this->get('State');
			$this->listDirn				= $this->state->get('filter_order_Dir');
			$this->listOrder			= $this->state->get('filter_order');
			$this->breadcrumbmenuState	= $this->state->get('breadcrumbmenuState');
			$this->layout = $this->alias;
        }
        
        // Render toolbar & view
        $this->addToolbar();              
        parent::display($tpl);
        
    }

	protected function addToolbar()
	{
	
	    // Add title
        JToolBarHelper::title( JText::_( 'COM_CCS' ), 'CCS_default' ); 
        
        // Add buttons
        switch($this->layout) {
			case 'table':
					JToolBarHelper::custom('add', 'CCS_add', 'CCS_add', JText::_( 'COM_CCS_ADD' ), false, false );
                    JToolBarHelper::custom('duplicate', 'CCS_duplicate', 'CCS_duplicate', JText::_( 'COM_CCS_DUPLICATE' ), false, false );  
                    JToolBarHelper::custom('edit', 'CCS_edit', 'CCS_edit', JText::_( 'COM_CCS_EDIT' ), false, false );
                    JToolBarHelper::custom('delete', 'CCS_delete', 'CCS_delete', JText::_( 'COM_CCS_DELETE' ), false, false );  
                    JToolBarHelper::divider();
                    JToolBarHelper::custom('refresh', 'CCS_refresh', 'CCS_refresh', JText::_( 'COM_CCS_REFRESH' ), false, false );

			    break;
			case 'row':
			        JToolBarHelper::custom('apply', 'CCS_apply', 'CCS_apply', JText::_( 'COM_CCS_APPLY' ), false, false );
			        JToolBarHelper::custom('save', 'CCS_save', 'CCS_save', JText::_( 'COM_CCS_SAVE_AND_CLOSE' ), false, false );
			        JToolBarHelper::custom('saveAndNew', 'CCS_save_and_new', 'CCS_save_and_new', JText::_( 'COM_CCS_SAVE_AND_NEW' ), false, false );
			        JToolBarHelper::custom('cancel', 'CCS_cancel', 'CCS_cancel', JText::_( 'COM_CCS_CANCEL' ), false, false );
			    break;
			default:
			        		}            
		
	}
}
