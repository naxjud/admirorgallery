<?php
/**
 * Hello Controller for Hello World Component
 * 
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://docs.joomla.org/Developing_a_Model-View-Controller_Component_-_Part_4
 * @license		GNU/GPL
 */
 
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
 
/**
 * Hello Hello Controller
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class CcsControllerCcs extends CcsController
{
	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	function __construct()
	{    
	   
		parent::__construct();
 
		// Register Extra tasks
		$this->registerTask( 'add', 'add' );
		$this->registerTask( 'apply', 'apply' );
		$this->registerTask( 'save', 'save' );
		$this->registerTask( 'saveAndNew', 'saveAndNew' );
		$this->registerTask( 'duplicate', 'duplicate' );
		$this->registerTask( 'edit', 'edit' );
		$this->registerTask( 'remove', 'remove' );
		$this->registerTask( 'refresh', 'refresh' );
		$this->registerTask( 'cancel', 'cancel' );
		$this->registerTask( 'adminListSorting', 'adminListSorting' );
	}

	function add(){
	    JRequest::setVar( 'layout', 'row' );	
        JRequest::setVar( 'hidemainmenu', 1 );
		parent::display();
	}
	
	function duplicate(){
	    $cids = JRequest::getVar( 'cid', array(), 'post', 'array' );
        if(!empty($cids))
        {        
	    	JRequest::setVar( 'layout', 'row' );
            JRequest::setVar( 'hidemainmenu', 1 );	
        }else{
            JFactory::getApplication()->enqueueMessage(JText::_('COM_CCS_ERROR_NOSELECT'), 'error');
            JRequest::setVar( 'layout', 'table' );
            JRequest::setVar( 'task', 'default' );	
        }
		parent::display();
	}
	
	function edit(){
	    $cids = JRequest::getVar( 'cid', array(), 'post', 'array' );
        if(!empty($cids))
        {        
	    	JRequest::setVar( 'layout', 'row' );
            JRequest::setVar( 'hidemainmenu', 1 );	
        }else{
            JFactory::getApplication()->enqueueMessage(JText::_('COM_CCS_ERROR_NOSELECT'), 'error');
            JRequest::setVar( 'layout', 'table' );
            JRequest::setVar( 'task', 'default' );	
        }
		parent::display();
	}
	
	function delete(){
	    $cids = JRequest::getVar( 'cid', array(), 'post', 'array' );
        if(!empty($cids))
        {      
            $model = $this->getModel();
            $model->delete();
        }else{
            JFactory::getApplication()->enqueueMessage(JText::_('COM_CCS_ERROR_NOSELECT'), 'error');
        }   
        JRequest::setVar( 'layout', 'table'  );	
	    parent::display();
	}
	
	function refresh(){	
		JRequest::setVar( 'layout', 'table' );	
		parent::display();
	}
	
	function cancel(){
        JRequest::setVar( 'layout', 'table' );	
		parent::display();
	}

	function apply(){
		$rowID = JRequest::getVar( 'id' );
	    $model = $this->getModel();
	    if(empty($rowID))
        {  
	    	$model->store();
		}else{
	    	$model->replace();
		}
	    JRequest::setVar( 'task', 'edit' );
	    JRequest::setVar( 'layout', 'row' );
		parent::display();
	}

	function saveAndNew(){
		$rowID = JRequest::getVar( 'id' );
	    $model = $this->getModel();
	    if(empty($rowID))
        {  
	    	$model->store();
		}else{
	    	$model->replace();
		}
	    JRequest::setVar( 'task', 'add' );
	    JRequest::setVar( 'layout', 'row' );
		parent::display();
	}
	
	function save(){
		$rowID = JRequest::getVar( 'id' );
	    $model = $this->getModel();
	    if(empty($rowID))
        {  
	    	$model->store();
		}else{
	    	$model->replace();
		}
	    JRequest::setVar( 'layout', 'table' );
		parent::display();
	}

    function adminListSorting(){
        $model = $this->getModel();
        $model->adminListSorting();
	    JRequest::setVar( 'layout', 'table' );
        parent::display();
    }
}
