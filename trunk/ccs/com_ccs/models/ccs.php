<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modellist');

/**
 * CcsModelCcs
 *
 * @package    CCS
 * @subpackage Components
 */
class CcsModelCcs extends JModelList {

    private $current_alias;
    private $ccs_database_config; // List of Databases Identifier
    private $ccs_fields_config; // List of Databases Identifier
    private $dbPrefix;
    private $dbObject;
    private $fields;
    private $user;

    function __construct() {
        parent::__construct();
        $conf = JFactory::getConfig();
        $this->current_alias = JRequest::getVar('alias', 'com_ccs_home');
        $this->ccs_database_config = "#__ccs_databases";
        $this->ccs_fields_config = "#__ccs_admin_fields";
        $this->db_prefix = $conf->get('dbprefix');
        $this->dbObject = JFactory::getDBO();
        $this->user = JFactory::getUser();
        $this->fields = $this->loadVisibleFields();
    }

    function getFields() {
        return $this->fields;
    }

    function getAlias() {
        return $this->current_alias;
    }

    protected function loadVisibleFields() {
        $query = $this->dbObject->getQuery(true);
        $query->select($this->dbObject->nameQuote('fld_alias'));
        $query->select($this->dbObject->nameQuote('fld_type'));
        $query->select($this->dbObject->nameQuote('fld_params'));
        $query->from($this->dbObject->nameQuote($this->ccs_fields_config));
        $query->where($this->dbObject->nameQuote('db_alias') . ' = ' . $this->dbObject->Quote($this->current_alias));
        $query->order($this->dbObject->getEscaped('ordering' . ' ' . 'ASC'));
        $this->dbObject->setQuery($query);
        $fieldsArray = $this->dbObject->loadAssocList();
        $idArray = Array();
        $idArray['fld_alias'] = "id";
        $idArray['fld_type'] = "number";
        $idArray['fld_params'] = "";
        array_unshift($fieldsArray, $idArray); // <-------------- Always insert id
        return $fieldsArray;
    }

    protected function getListQuery() {
        $query = $this->dbObject->getQuery(true);

        foreach ($this->fields as $field) {
            $query->select($this->dbObject->nameQuote($field['fld_alias']));
        }

        $query->from($this->dbObject->nameQuote($this->db_prefix . $this->current_alias));

        // Filter Order
        $order = $this->getState('filter_order');
        if (!empty($order)) {
            $query->order($this->dbObject->getEscaped($order . ' ' . $this->getState('filter_order_Dir', 'DESC')));
        }

        // Filter Search
        $search_column = $this->getState('filter_search_column');
        $search_value = $this->getState('filter_search_value');
        if (!empty($search_value)) {
            $query->where($db->nameQuote($search_column) . " LIKE " . $this->dbObject->Quote('%' . $search_value . '%'));
        }

        return $query;
    }

    function fieldsExists($fields, $fieldsArray) {// Compare 2 arrays is item from first array exists in second
        $check = 1;
        if (count($fields) > 0) {
            while (list($k, $v) = each($fields)) {
                if (!in_array($v, $fieldsArray)) {
                    $check = 0;
                }
            }
        } else {
            RETURN 0;
        }
        RETURN $check;
    }

    function getChildren() {
        $query = $this->dbObject->getQuery(true);
        $query->select($this->dbObject->nameQuote('db_alias'));
        $query->from($this->dbObject->nameQuote($this->ccs_database_config));
        $query->where($this->dbObject->nameQuote('parent_db_alias') . ' = ' . $this->dbObject->Quote($this->current_alias));
        $this->dbObject->setQuery($query);

        return $this->dbObject->loadAssocList();
    }

    function getRow() {
        $cids = JRequest::getVar('cid', array(), 'post', 'array');
        $query = $this->dbObject->getQuery(true);
        $query->select('*');
        $query->from($this->dbObject->nameQuote($this->db_prefix . $this->current_alias));
        $query->where($this->dbObject->nameQuote('id') . '=' . (int) $cids[0]);
        $this->dbObject->setQuery($query);
        $this->dbObject->loadRow();
        $this->dbObject->query();
        $row = $this->dbObject->loadAssoc();
        return $row;
    }

    function populateState($ordering = null, $direction = null) {
        // Filter Order
        $filter_order = JRequest::getCmd('filter_order');
        $filter_order_Dir = JRequest::getCmd('filter_order_Dir');
        if (empty($filter_order)) {
            $filter_order = 'ordering';
            $filter_order_Dir = 'asc';
        }
        $this->setState('filter_order', $filter_order);
        $this->setState('filter_order_Dir', $filter_order_Dir);

        // Filter Search
        $search_column = JRequest::getCmd('filter_search_column');
        $this->setState('filter_search_column', $search_column);
        $search_value = JRequest::getCmd('filter_search_value');
        $this->setState('filter_search_value', $search_value);


        $breadcrumbmenuState = JRequest::getCmd('breadcrumbmenuState');
        $this->setState('breadcrumbmenuState', $breadcrumbmenuState);

        parent::populateState();
    }

    function store() {
        $query = $this->dbObject->getQuery(true);
        $query->insert($this->dbObject->nameQuote($this->db_prefix . $this->current_alias));

        foreach ($this->fields as $field) {
            if ($field["fld_alias"] != "id") {
                $field_value = JRequest::getVar($field["fld_alias"], null, 'post', 'STRING', JREQUEST_ALLOWRAW);
                $query->set($this->dbObject->nameQuote($field["fld_alias"]) . '=' . $this->dbObject->Quote($field_value));
            }
        }

        $this->dbObject->setQuery($query);
        if ($this->dbObject->query()) {
            JFactory::getApplication()->enqueueMessage(JText::_('COM_CCS_OK_STORE'), 'message');
        } else {
            JFactory::getApplication()->enqueueMessage(JText::_('COM_CCS_ERROR_STORE'), 'error');
        }
    }

    function replace($rowID) {
        $query = $this->dbObject->getQuery(true);
        $query->update($this->dbObject->nameQuote($this->db_prefix . $this->current_alias));
        $query->where($this->dbObject->nameQuote('id') . '=' . (int) $rowID);

        $tblEdit_alias = JRequest::getVar("tblEdit_alias", null, 'post', 'STRING', JREQUEST_ALLOWRAW);
        $tblEdit_value = JRequest::getVar("tblEdit_value", null, 'post', 'STRING', JREQUEST_ALLOWRAW);
        if ($tblEdit_alias != "" && $tblEdit_value != "") {// Phatch for Field editing form Table View
            $query->set($this->dbObject->nameQuote($tblEdit_alias) . '=' . $this->dbObject->Quote($tblEdit_value));
        } else {
            foreach ($this->fields as $field) {
                if ($field["fld_alias"] != "id") {
                    $field_value = JRequest::getVar($field["fld_alias"], null, 'post', 'STRING', JREQUEST_ALLOWRAW);
                    $query->set($this->dbObject->nameQuote($field["fld_alias"]) . '=' . $this->dbObject->Quote($field_value));
                }
            }
        }
        $this->dbObject->setQuery($query);
        if ($this->dbObject->query()) {
            JFactory::getApplication()->enqueueMessage(JText::_('COM_CCS_OK_REPLACE') . ": " . $rowID, 'message');
        } else {
            JFactory::getApplication()->enqueueMessage(JText::_('COM_CCS_ERROR_REPLACE') . ": " . $rowID, 'error');
        }
    }

    function delete($cids) {
        foreach ($cids as $cid) {
            $query = $this->dbObject->getQuery(true);
            $query->delete();
            $query->from($this->dbObject->nameQuote($this->db_prefix . $this->current_alias));
            $query->where($this->dbObject->nameQuote('id') . '=' . (int) $cid);
            $this->dbObject->setQuery($query);
            if ($this->dbObject->query()) {
                JFactory::getApplication()->enqueueMessage(JText::_('COM_CCS_OK_DELETE') . ": " . $cid, 'message');
            } else {
                JFactory::getApplication()->enqueueMessage(JText::_('COM_CCS_ERROR_DELETE') . ": " . $cid, 'error');
            }
        }
    }

    function getMenuItems() {
        // Public 1
        // Registered 2 
        // Manager 6
        // Super User 8
        $query = $this->dbObject->getQuery(true);
        $query->select($this->dbObject->nameQuote('id'));
        $query->select($this->dbObject->nameQuote('ordering'));
        $query->select($this->dbObject->nameQuote('db_alias'));
        $query->select($this->dbObject->nameQuote('access'));
        $query->select($this->dbObject->nameQuote('db_image'));
        $query->select($this->dbObject->nameQuote('parent_db_alias'));
        $query->from($this->dbObject->nameQuote($this->ccs_database_config));
        $query->where($this->dbObject->nameQuote('access') . '<=' . (int) (end($this->user->groups)));
        $this->dbObject->setQuery($query);
        $result = $this->dbObject->loadAssocList();
        return $result;
    }

    function adminListSorting() {

        $cids = JRequest::getVar('cid', array(), 'post', 'array');
        $cid = (int) $cids[0]; // <-------------------------------------- Get ROW ID
        $drop_target = JRequest::getVar('adminListSorting');
        $drop_pos = substr($drop_target, 0, 3); // <---------------------- Get Target Position
        $drop_cid = (int) substr($drop_target, 3, strlen($drop_target)); // <---------------------- Get Target ROW ID

        if ($cid == $drop_cid) {
            return; // <---------------------- RETURN if same row
        }
        $query = $this->dbObject->getQuery(true);
        $query->select($this->dbObject->nameQuote('id'));
        $query->select($this->dbObject->nameQuote('ordering'));
        $query->from($this->dbObject->nameQuote($this->db_prefix . $this->current_alias));
        $query->order($this->dbObject->getEscaped('ordering' . ' ' . 'ASC'));
        $this->dbObject->setQuery($query);
        $ROWs = $this->dbObject->loadAssocList(); // <---------------------- Get ROWs

        $newROWs = Array();
        $cacheROW = Array();

        foreach ($ROWs as $i => $ROW) {
            if ($ROW["id"] == $cid) {
                $cacheROW = $ROW;
            }
        }

        foreach ($ROWs as $i => $ROW) {
            if ($ROW["id"] != $cid) {
                if ($ROW["id"] == $drop_cid && $drop_pos == "bef") {// After
                    $newROWs[] = $cacheROW;
                }
                $newROWs[] = $ROW;
                if ($ROW["id"] == $drop_cid && $drop_pos == "aft") {// After
                    $newROWs[] = $cacheROW;
                }
            }
        }

        // Loop through rows and update orderings
        foreach ($newROWs as $i => $ROW) {
            $query = $this->dbObject->getQuery(true);
            $query->update($this->dbObject->nameQuote($this->db_prefix . $this->current_alias));
            $query->where($this->dbObject->nameQuote('id') . '=' . $ROW["id"]);
            $query->set($this->dbObject->nameQuote('ordering') . '=' . $i);
            $this->dbObject->setQuery($query);
            $this->dbObject->query();
        }
    }

    function getQuickIcons() {
        $query = $this->dbObject->getQuery(true);
        $query->select($this->dbObject->nameQuote('id'));
        $query->select($this->dbObject->nameQuote('db_alias'));
        $query->select($this->dbObject->nameQuote('db_image'));
        $query->from($this->dbObject->nameQuote($this->ccs_database_config));
        $query->where($this->dbObject->nameQuote('quick_icon') . '=' . (int) 1);
        $query->where($this->dbObject->nameQuote('access') . '<=' . (int) (end($this->user->groups)));
        $this->dbObject->setQuery($query);
        return $this->dbObject->loadAssocList();
    }

}
