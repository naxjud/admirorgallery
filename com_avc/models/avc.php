<?php

defined('_JEXEC') or die('Restricted access');

if(JDEBUG){
    ini_set('display_errors', 'On');
    error_reporting(E_ALL | E_STRICT);
}

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
    private $mainframe;
    private $fieldsArray;


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

        $this->mainframe = JFactory::getApplication();

    }

    protected function checkJSON(){
        if(version_compare(PHP_VERSION, '5.3.0') >= 0) { 
            switch (json_last_error()) {
                case JSON_ERROR_NONE:
                break;
                case JSON_ERROR_DEPTH:
                    JFactory::getApplication()->enqueueMessage('Maximum stack depth exceeded', 'error');
                break;
                case JSON_ERROR_STATE_MISMATCH:
                    JFactory::getApplication()->enqueueMessage('Underflow or the modes mismatch', 'error');
                break;
                case JSON_ERROR_CTRL_CHAR:
                    JFactory::getApplication()->enqueueMessage('Unexpected control character found', 'error');
                break;
                case JSON_ERROR_SYNTAX:
                    JFactory::getApplication()->enqueueMessage('Syntax error, malformed JSON', 'error');
                break;
                case JSON_ERROR_UTF8:
                    JFactory::getApplication()->enqueueMessage('Malformed UTF-8 characters, possibly incorrectly encoded', 'error');
                break;
                default:
                    JFactory::getApplication()->enqueueMessage(' Unknown error', 'error');
                break;
            }
        }
    }

    protected function checkVar($check, $default){
        $value = $check;
        if(empty($value)){
            $value = $default;
        }
        return $value;
    }

    public function getFieldsArray(){
        if( !empty($this->views[$this->curr_view_id]["fields_config"]) ){
            foreach ($this->views[$this->curr_view_id]["fields_config"] as $fld_key => $fld_value) {
                $this->fieldsArray[] = $fld_key;
            }
        }            
        return $this->fieldsArray;
    }

    // This is actually getItems in JModelList
    // Pagination works with this by itself
    // Here goes Fiters
    protected function getListQuery()
    {


           
        $query = $this->dbObject->getQuery(true);

        $query->select( array($this->views[$this->curr_view_id]["query"]["select"]) );

        $query->from( $this->views[$this->curr_view_id]["query"]["from"] );

        // Filter Search      
        $search_value = $this->mainframe->getUserStateFromRequest( 'filter_search_value', 'filter_search_value', $this->mainframe->getCfg('filter_search_value') );  
        if ( !empty($search_value) ) {
            $query->having( $search_value );
        }

        // LEFT JOIN
        if(!empty($this->views[$this->curr_view_id]["query"]["left_join"])){
            foreach ($this->views[$this->curr_view_id]["query"]["left_join"] as $value) { 
                $query->leftJoin($value);
            }
        }

        // RIGHT JOIN
        if(!empty($this->views[$this->curr_view_id]["query"]["right_join"])){
            foreach ($this->views[$this->curr_view_id]["query"]["right_join"] as $value) { 
                $query->rightJoin($value);
            }
        }

        // INNER JOIN
        if(!empty($this->views[$this->curr_view_id]["query"]["inner_join"])){
            foreach ($this->views[$this->curr_view_id]["query"]["inner_join"] as $value) { 
                $query->innerJoin($value);
            }
        }

        // Filter Order
        if($this->getState('filter_order')!=""){
            $order = $this->getState('filter_order') . ' ' . $this->getState('filter_order_Dir');
        }else{
            if (!empty($this->views[$this->curr_view_id]["query"]["order_by"])) {
                $order = $this->views[$this->curr_view_id]["query"]["order_by"];
            }
        }
        if (!empty($order)) {
            $query->order($this->dbObject->getEscaped($order));
        }

        // GET ONLY SELECTED ROW FOR ROW LAYOUT
        if (JRequest::getVar('layout', 'default') == "row") {
            if($this->curr_row_id > 0){                
                $query->having($this->dbObject->nameQuote("id") . " = " . $this->curr_row_id);
            }
        }

        return $query;

    }

    public function populateState($ordering = null, $direction = null) {

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
        if(in_array(8,$groupsUserIsIn))
        {
            // is superadmin
        }else{
            // not superadmin
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
            $this->checkJSON();
            $this->views[$viewID]["fields_config"] = json_decode($value["fields_config"], true);
            $this->checkJSON();
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
                        if($FIELD_ALIAS != "id"){
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
                        $FIELD_VALUE = JRequest::getVar( $FIELD_ALIAS, "nemanihtnull", 'post', 'STRING', JREQUEST_ALLOWRAW );
                        if($FIELD_ALIAS != "id" && $FIELD_VALUE!="nemanihtnull"){
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
