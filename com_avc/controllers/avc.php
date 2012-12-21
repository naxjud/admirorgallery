<?php
// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Avc Controller
 *
 * @package    CSS
 * @subpackage Components
 */
class AvcControllerAvc extends AvcController {

    /**
     * constructor (registers additional tasks to methods)
     * @return void
     */
    private $model;

    function __construct() {

        parent::__construct();

        $this->model = $this->getModel();

        // Register Extra tasks
        $this->registerTask('add', 'add');
        $this->registerTask('apply', 'apply');
        $this->registerTask('save', 'save');
        $this->registerTask('saveAndNew', 'saveAndNew');
        $this->registerTask('duplicate', 'duplicate');
        $this->registerTask('edit', 'edit');
        $this->registerTask('remove', 'remove');
        $this->registerTask('refresh', 'refresh');
        $this->registerTask('cancel', 'cancel');
        $this->registerTask('ordering', 'ordering');
    }

    function edit() {
        $cids = JRequest::getVar('cid', array(), 'post', 'array');
        if (!empty($cids)) {
            JRequest::setVar('layout', 'row');
            JRequest::setVar('hidemainmenu', 1);
        } else {
            JFactory::getApplication()->enqueueMessage(JText::_('COM_AVC_ERROR_NOSELECT'), 'error');
            JRequest::setVar('layout', 'table');
            JRequest::setVar('task', 'default');
        }
        parent::display();
    }

    function add() {
        JRequest::setVar('layout', 'row');
        JRequest::setVar('hidemainmenu', 1);
        parent::display();
    }

    function duplicate() {
        $cids = JRequest::getVar('cid', array(), 'post', 'array');
        if (!empty($cids)) {
            JRequest::setVar('layout', 'row');
            JRequest::setVar('hidemainmenu', 1);
        } else {
            JFactory::getApplication()->enqueueMessage(JText::_('COM_AVC_ERROR_NOSELECT'), 'error');
            JRequest::setVar('layout', 'table');
            JRequest::setVar('task', 'default');
        }
        parent::display();
    }

    function delete() {
        $cids = JRequest::getVar('cid', array(), 'post', 'array');
        if (!empty($cids)) {
            $this->model->delete();
        } else {
            JFactory::getApplication()->enqueueMessage(JText::_('COM_AVC_ERROR_NOSELECT'), 'error');
        }
        JRequest::setVar('layout', 'table');
        parent::display();
    }

    function refresh() {        
        JRequest::setVar('layout', 'table');
        parent::display();
    }

    function save() {
        $this->model->store();
        JRequest::setVar('layout', 'table');
        parent::display();
    }

    function apply() {
        $this->model->store();
        JRequest::setVar('layout', 'row');
        parent::display();
    }

    function saveAndNew() {
        $this->model->store();        
        JRequest::setVar('cid', array());
        JRequest::setVar('layout', 'row');
        parent::display();
    }
    
    function cancel() {
        JRequest::setVar('layout', 'table');
        JRequest::setVar('task', 'default');
        parent::display();
    }

    function ordering() {
        $this->model->ordering();
        JRequest::setVar('layout', 'table');
        parent::display();
    }

}
