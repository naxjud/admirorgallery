<?php
// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Ccs Controller
 *
 * @package    CSS
 * @subpackage Components
 */
class CcsControllerCcs extends CcsController {

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
        $this->registerTask('adminListSorting', 'adminListSorting');
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
            JFactory::getApplication()->enqueueMessage(JText::_('COM_CCS_ERROR_NOSELECT'), 'error');
            JRequest::setVar('layout', 'table');
            JRequest::setVar('task', 'default');
        }
        parent::display();
    }

    function edit() {
        $cids = JRequest::getVar('cid', array(), 'post', 'array');
        if (!empty($cids)) {
            JRequest::setVar('layout', 'row');
            JRequest::setVar('hidemainmenu', 1);
        } else {
            JFactory::getApplication()->enqueueMessage(JText::_('COM_CCS_ERROR_NOSELECT'), 'error');
            JRequest::setVar('layout', 'table');
            JRequest::setVar('task', 'default');
        }
        parent::display();
    }

    function delete() {
        $cids = JRequest::getVar('cid', array(), 'post', 'array');
        if (!empty($cids)) {
            $this->model->delete($cids);
        } else {
            JFactory::getApplication()->enqueueMessage(JText::_('COM_CCS_ERROR_NOSELECT'), 'error');
        }
        JRequest::setVar('layout', 'table');
        parent::display();
    }

    function refresh() {
        JRequest::setVar('layout', 'table');
        parent::display();
    }

    function cancel() {
        JRequest::setVar('layout', 'table');
        parent::display();
    }

    function apply() {
        $rowID = JRequest::getVar('id');
        if (empty($rowID)) {
            $this->model->store();
        } else {
            $this->model->replace($rowID);
        }
        JRequest::setVar('task', 'edit');
        JRequest::setVar('layout', 'row');
        parent::display();
    }

    function saveAndNew() {
        $rowID = JRequest::getVar('id');
        if (empty($rowID)) {
            $this->model->store();
        } else {
            $this->model->replace($rowID);
        }
        JRequest::setVar('task', 'add');
        JRequest::setVar('layout', 'row');
        parent::display();
    }

    function save() {
        $rowID = JRequest::getVar('id');
        if (empty($rowID)) {
            $this->model->store();
        } else {
            $this->model->replace($rowID);
        }
        JRequest::setVar('layout', 'table');
        parent::display();
    }

    function adminListSorting() {
        $this->model->adminListSorting();
        JRequest::setVar('layout', 'table');
        parent::display();
    }

}
