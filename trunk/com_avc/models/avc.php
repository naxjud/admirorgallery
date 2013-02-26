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

    private $db_editor = "#__avc_editor";
    private $dbObject; // Container for DB class
    private $curr_view_id; // Current view id   
    private $curr_row_id; // Current row id, used for editing
    public $views;


    function __construct() {

        parent::__construct();

        $this->dbObject = JFactory::getDBO();
        $this->curr_view_id = JRequest::getVar('curr_view_id', 0); 

        $cids = JRequest::getVar('cid', array(), 'post', 'array');
        if (!empty($cids)) {
            $this->curr_row_id = (int)$cids[0];
        } else {
            $this->curr_row_id = 0;
        }

        $this->views = $this->getListViews();

    }


    // This is actually getItems in JModelList
    // Pagination works with this by itself
    // Here goes Fiters
    protected function getListQuery()
    {
          
        $query = $this->dbObject->getQuery(true);

        $query->select( array($this->views[$this->curr_view_id]["query"]["select"]) );

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

        // GET ONLY SELECTED ROW FOR ROW LAYOUT
        if (($this->curr_row_id > 0) && (JRequest::getVar('layout', 'default') == "row")) {
            $query->where($this->dbObject->nameQuote("id") . " = " . $this->curr_row_id);
        }

        $query->from( $this->views[$this->curr_view_id]["query"]["from"] );

        return $query;

    }

    public function populateState($ordering = null, $direction = null) {

        // Filter Search
        $search_column = JRequest::getCmd('filter_search_column');
        $this->setState('filter_search_column', $search_column);
        $search_value = JRequest::getCmd('filter_search_value');
        $this->setState('filter_search_value', $search_value);

        // Filter Order
        $filter_order = JRequest::getCmd('filter_order');
        $filter_order_Dir = JRequest::getCmd('filter_order_Dir');
        $this->setState('filter_order', $filter_order);
        $this->setState('filter_order_Dir', $filter_order_Dir);

        parent::populateState();
    }

    public function getListViews()
    {

        $query = $this->dbObject->getQuery(true);
        $query->select("*");
        $query->from($this->dbObject->nameQuote($this->db_editor));

        // FILTER PUBLISHED        
        $groupsUserIsIn = JAccess::getGroupsByUser(JFactory::getUser()->id);
        if(in_array(7,$groupsUserIsIn) || in_array(8,$groupsUserIsIn))
        {
            // is admin
        }else{
            // not admin
            $query->where( $this->dbObject->nameQuote("published") . " = 1" );
            $query->where( $this->dbObject->nameQuote("admin_only") . " = 0" );
        }

        $query->order($this->dbObject->getEscaped('ordering ASC'));

        $this->dbObject->setQuery($query);
        $myViewList = $this->dbObject->loadAssocList();

        $this->views = array();
        foreach ($myViewList as $key => $value) {
            $viewID = $value["id"];
            $this->views[$viewID] = array();
            $this->views[$viewID]["name"] = $value["name"];
            $this->views[$viewID]["icon_path"] = $value["icon_path"];
            $this->views[$viewID]["query"] = json_decode($value["query"], true);
            $this->views[$viewID]["fields_config"] = json_decode($value["fields_config"], true);
            $this->views[$viewID]["group_alias"] = $value["group_alias"];
            $this->views[$viewID]["published"] = $value["published"];
        }

        return $this->views;

    }

    public function getViews() {
        return $this->views;
    }

    public function getCurrViewId() {
        return $this->curr_view_id;
    }

    public function getCurrRowId() {
        return $this->curr_row_id;
    }

    function store() {

        switch ($this->curr_row_id) {
            case 0: // ADD NEW
                    $query = $this->dbObject->getQuery(true);
                    $query->insert( $this->dbObject->nameQuote($this->views[$this->curr_view_id]["query"]["from"]) );
                    $FIELDS_str = str_replace(" ", "", $this->views[$this->curr_view_id]["query"]["select"]);
                    $FIELDS  = explode(",",$FIELDS_str);
                    foreach ($FIELDS as $FIELD_ALIAS){
                        $FIELD_VALUE = JRequest::getVar( $FIELD_ALIAS, null, 'post', 'STRING', JREQUEST_ALLOWRAW );
                        if($FIELD_ALIAS != "id" && $FIELD_VALUE!=null){
                            if(!is_numeric($FIELD_VALUE)){$FIELD_VALUE = $this->dbObject->Quote($FIELD_VALUE);}
                            $query->set( $this->dbObject->nameQuote($FIELD_ALIAS)."=".$FIELD_VALUE );
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
                    $query->update( $this->dbObject->nameQuote($this->views[$this->curr_view_id]["query"]["from"]) );
                    $query->where( "id =".$this->curr_row_id );
                    $FIELDS_str = str_replace(" ", "", $this->views[$this->curr_view_id]["query"]["select"]);
                    $FIELDS  = explode(",",$FIELDS_str);
                    foreach ($FIELDS as $FIELD_ALIAS){
                        $FIELD_VALUE = JRequest::getVar( $FIELD_ALIAS, null, 'post', 'STRING', JREQUEST_ALLOWRAW );
                        if($FIELD_ALIAS != "id" && $FIELD_VALUE!=null){
                            if(!is_numeric($FIELD_VALUE)){$FIELD_VALUE = $this->dbObject->Quote($FIELD_VALUE);}
                            $query->set( $this->dbObject->nameQuote($FIELD_ALIAS)."=".$FIELD_VALUE );
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
            $query->from( $this->dbObject->nameQuote($this->views[$this->curr_view_id]["query"]["from"]) );
            $query->where( "id =".(int) $cid );
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
        $query->select( $this->dbObject->nameQuote( "id" ) );
        $query->from( $this->dbObject->nameQuote($this->views[$this->curr_view_id]["query"]["from"]) );
        $query->order( $this->dbObject->getEscaped('ordering ASC') );
        $this->dbObject->setQuery($query);
        $rows = $this->dbObject->loadAssocList();

        // REMOVE FROM ARRAY
        foreach ($rows as $key => $row) {
            if($row[ "id" ]==(int)$ordering_id){
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
                if($row[ "id" ]==(int)$ordering_targetId){
                    $newRows[]=$cacheRow;
                }
            }
            $rows = $newRows;
        }

        // UPDATE ORDERINGS
        foreach ($rows as $key => $row) {
            $query = $this->dbObject->getQuery(true);
            $query->update( $this->dbObject->nameQuote($this->views[$this->curr_view_id]["query"]["from"]) );
            $query->where( $this->dbObject->nameQuote( "id" ) . "=" . (int)$row[ "id" ] );
            $query->set( $this->dbObject->nameQuote("ordering") . "=" . (int)($key+1) );
            $this->dbObject->setQuery($query);
            $this->dbObject->query();
        }

    }

}
