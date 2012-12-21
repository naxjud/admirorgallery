<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modellist');

/**
 * AvcModelAvc
 *
 * @package    AVC
 * @subpackage Components
 */
class AvcModelAvc extends JModelList {

    private $db_view = "#__avc_view";
    private $db_field = "#__avc_field";
    private $db_view_fields = "#__avc_view_fields";
    private $dbObject; // Container for DB class
    private $curr_view_id; // Current view id
    private $views; // Array of DB rows of all views (needed for menu)
    private $curr_view_row; // Current DB row
    private $viewFields; // Object of DB rows of fields are connected to current view    
    private $curr_row_id; // Current row id, used for editing

    function __construct() {

        parent::__construct();

        $this->dbObject = JFactory::getDBO();
        $this->curr_view_id = JRequest::getVar('curr_view_id', 0);
        $this->views = $this->views();
        $this->curr_view_row = $this->currViewRow();
        $this->viewFields = $this->viewFields();

        $cids = JRequest::getVar('cid', array(), 'post', 'array');
        if (!empty($cids)) {
            $this->curr_row_id = $cids[0];
        } else {
            $this->curr_row_id = 0;
        }

    }

    // This is actually getItems in JModelList
    // Pagination works with this by itself
    // Here goes Fiters
    protected function getListQuery()
    {
          
        $query = $this->dbObject->getQuery(true);

        $query->select( $this->dbObject->nameQuote($this->curr_view_row["key_field_name"]) );

        foreach ($this->viewFields as $viewField) {
            if($viewField["name"]!=$this->curr_view_row["key_field_name"]){
                $query->select( $this->dbObject->nameQuote($viewField["name"]) );
            }
        }


        // Filter Search
        $search_column = $this->getState('filter_search_column');
        $search_value = $this->getState('filter_search_value');    
        if (!empty($search_value)) {
            $query->where($this->dbObject->nameQuote($search_column) . " LIKE " . $this->dbObject->Quote('%' . $search_value . '%'));
        }

        // Filter Order
        $order = $this->getState('filter_order');
        if (!empty($order)) {
            $query->order($this->dbObject->getEscaped($order . ' ' . $this->getState('filter_order_Dir', 'DESC')));
        }

        $query->from($this->dbObject->nameQuote($this->curr_view_row["name"]));

        return $query;
    }

    function populateState($ordering = null, $direction = null) {

        // Filter Search
        $search_column = JRequest::getCmd('filter_search_column');
        $this->setState('filter_search_column', $search_column);
        $search_value = JRequest::getCmd('filter_search_value');
        $this->setState('filter_search_value', $search_value);

        // Filter Order
        $filter_order = JRequest::getCmd('filter_order');
        $filter_order_Dir = JRequest::getCmd('filter_order_Dir');
        if (empty($filter_order)) {
            $filter_order = 'ordering';
            $filter_order_Dir = 'asc';
        }
        $this->setState('filter_order', $filter_order);
        $this->setState('filter_order_Dir', $filter_order_Dir);

        parent::populateState();
    }


    function currViewRow() {
        foreach ($this->views as $view) {
            if($view["id"]==$this->curr_view_id){                
                return $view;
            }
        }
    }

    function views() {
        $query = $this->dbObject->getQuery(true);
        $query->select('*');
        $query->order($this->dbObject->getEscaped('ordering ASC'));
        $query->from($this->dbObject->nameQuote($this->db_view));
        $this->dbObject->setQuery($query);
        $rows = $this->dbObject->loadAssocList();
        return $rows;
    }

    function viewFields() {
        $query = $this->dbObject->getQuery(true);
        $query->select( $this->db_field.'.*' );
        $query->from( $this->dbObject->nameQuote($this->db_view_fields) );
        $query->where( "view_id=".$this->curr_view_id );
        $query->join( "INNER" , $this->dbObject->nameQuote($this->db_field).' ON '.$this->db_view_fields.'.field_id='.$this->db_field.'.id' );
        $query->order($this->dbObject->getEscaped($this->db_field.'.ordering ASC'));
        $this->dbObject->setQuery($query);
        $rows = $this->dbObject->loadAssocList();
        return $rows;
    }

    function getItemsRow() {
        $query = $this->dbObject->getQuery(true);
        foreach ($this->viewFields as $viewField) {
            if($viewField["name"]!=$this->curr_view_row["key_field_name"]){
                $query->select( $this->dbObject->nameQuote($viewField["name"]) );
            }
        }
        $query->from( $this->dbObject->nameQuote($this->curr_view_row["name"]) );
        $query->where( $this->curr_view_row["key_field_name"]."=".$this->curr_row_id );
        $this->dbObject->setQuery($query);
        $output = $this->dbObject->loadAssocList();
        $rows = $output[0];
        return $rows;
    }

    function getCurrViewId(){
        return $this->curr_view_id;
    }

    function getCurrRowId(){
        return $this->curr_row_id;
    }

    function getView() {
        return $this->curr_view_row;
    }

    function getViews() {
            return $this->views;
    }

    function getViewFields() {
            return $this->viewFields;
    }

    function store() {
        switch ($this->curr_row_id) {
            case 0: // ADD NEW
                    $query = $this->dbObject->getQuery(true);
                    $query->insert( $this->dbObject->nameQuote($this->curr_view_row["name"]) );
                    foreach ($this->viewFields as $viewField) {
                        if($viewField["name"]!=$this->curr_view_row["key_field_name"]){ // DON'T TOUCH PRIMARY KEY
                            $value = JRequest::getVar( $viewField["id"], null, 'post', 'STRING', JREQUEST_ALLOWRAW );
                            if(!is_numeric($value)){$value = $this->dbObject->Quote($value);}
                            $query->set( $this->dbObject->nameQuote($viewField["name"])."=".$value );
                        }
                    }
                    $this->dbObject->setQuery($query);
                    if ($this->dbObject->query()) {
                        JFactory::getApplication()->enqueueMessage(JText::_('COM_AVC_OK_STORE'), 'message');
                    } else {
                        JFactory::getApplication()->enqueueMessage(JText::_('COM_AVC_ERROR_STORE'), 'error');
                    }
                break;
            
            default: // REPLACE
                    $query = $this->dbObject->getQuery(true);
                    $query->update( $this->dbObject->nameQuote($this->curr_view_row["name"]) );
                    $query->where( $this->curr_view_row["key_field_name"]."=".$this->curr_row_id );
                    foreach ($this->viewFields as $viewField) {
                        if(isset($_POST[$viewField["name"]])){ // REPLACE ONLY NEW VALUES
                            if($viewField["name"]!=$this->curr_view_row["key_field_name"]){ // DON'T TOUCH PRIMARY KEY
                                $value = JRequest::getVar( $viewField["name"], null, 'post', 'STRING', JREQUEST_ALLOWRAW );
                                if(!is_numeric($value)){$value = $this->dbObject->Quote($value);}
                                $query->set( $this->dbObject->nameQuote($viewField["name"])."=".$value );
                            }
                        }
                    }
                    $this->dbObject->setQuery($query);
                    if ($this->dbObject->query()) {
                        JFactory::getApplication()->enqueueMessage(JText::_('COM_AVC_OK_REPLACE') . ": " . $this->curr_row_id, 'message');
                    } else {
                        JFactory::getApplication()->enqueueMessage(JText::_('COM_AVC_ERROR_REPLACE') . ": " . $this->curr_row_id, 'error');
                    }
                break;
        }
    }

    function delete() {
        $cids = JRequest::getVar('cid', array(), 'post', 'array');
        foreach ($cids as $cid) {
            $query = $this->dbObject->getQuery(true);
            $query->delete();
            $query->from( $this->dbObject->nameQuote($this->curr_view_row["name"]) );
            $query->where( $this->curr_view_row["key_field_name"]."=".(int) $cid );
            $this->dbObject->setQuery($query);
            if ($this->dbObject->query()) {
                JFactory::getApplication()->enqueueMessage(JText::_('COM_AVC_OK_DELETE') . ": " . $cid, 'message');
            } else {
                JFactory::getApplication()->enqueueMessage(JText::_('COM_AVC_ERROR_DELETE') . ": " . $cid, 'error');
            }
        }
    }

    function ordering() {
        
        $ordering_id = JRequest::getVar('ordering_id');
        $ordering_pos = JRequest::getVar('ordering_pos');
        $ordering_targetId = JRequest::getVar('ordering_targetId');

        $query = $this->dbObject->getQuery(true);
        $query->select( $this->dbObject->nameQuote($this->curr_view_row["key_field_name"]) );
        $query->from( $this->dbObject->nameQuote($this->curr_view_row["name"]) );
        $query->order( $this->dbObject->getEscaped('ordering ASC') );
        $this->dbObject->setQuery($query);
        $rows = $this->dbObject->loadAssocList();

        // REMOVE FROM ARRAY
        foreach ($rows as $key => $row) {
            if($row[$this->curr_view_row["key_field_name"]]==(int)$ordering_id){
                $cacheRow = $rows[$key];
                unset($rows[$key]);
            }
        }

        // INSERT INTO NEW POSITION
        if($ordering_pos=="bef"){
            array_unshift($rows, $cacheRow);
        }else{
            $newRows = array();
            foreach ($rows as $key => $row) {
                $newRows[]=$row;
                if($row[$this->curr_view_row["key_field_name"]]==(int)$ordering_targetId){
                    $newRows[]=$cacheRow;
                }
            }
            $rows = $newRows;
        }

        // UPDATE ORDERINGS
        foreach ($rows as $key => $row) {
            $query = $this->dbObject->getQuery(true);
            $query->update( $this->dbObject->nameQuote($this->curr_view_row["name"]) );
            $query->where( $this->dbObject->nameQuote($this->curr_view_row["key_field_name"]) . "=" . (int)$row[$this->curr_view_row["key_field_name"]] );
            $query->set( $this->dbObject->nameQuote("ordering") . "=" . (int)($key+1) );
            $this->dbObject->setQuery($query);
            $this->dbObject->query();
        }

    }

}
