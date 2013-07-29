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
    private $db_generator = "#__avc_generator";
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

        // GENERATOR
        require_once("gen.php");
        //$this->execGenerator();

    }

    protected function execGenerator(){

        // LIST ALL GENERATOR CONFIGURATIONS
        $query_json["select"] = '*';
        $query_json["from"] = $this->db_generator;
        $generator_configs = $this->execQuery($query_json);

        // SEARCH REQUEST FOR GENERATE CONTENT
        if(!empty($generator_configs)){
            foreach ($generator_configs as $config) {
                if($config["generate"]==1){

                    if(empty($config["target_table"])){
                        JFactory::getApplication()->enqueueMessage(JText::_('COM_AVC_GENERATE_ERROR_NOTABLE') . ": " . $config["name"], 'error');
                        return;
                    }

                    if(empty($config["where_column"]) || empty($config["equals_value"])){
                        JFactory::getApplication()->enqueueMessage(JText::_('COM_AVC_GENERATE_ERROR_NOWHERE') . ": " . $config["name"], 'error');
                        return;
                    }

                    $WHERE_COLUMN = $this->dbObject->nameQuote( $config["where_column"] );
                    $EQUALS_VALUE = $config["equals_value"];
                    if(!is_numeric($EQUALS_VALUE)){$EQUALS_VALUE = $this->dbObject->Quote($EQUALS_VALUE);}

                    $CUSTOM_VALUES = json_decode($config["custom_values"], true);
                    $this->checkJSON();

                    // GENERATE CONTENT

                    // GET ROWS
                    $query_json = json_decode($config["query"], true);
                    $this->checkJSON();
                    $ROWS = $this->execQuery($query_json);

                    // ROWS AND TMPL MARRIED 
                    require( JPATH_ROOT.DS.$config["template_path"] );

                    // DELETE ALL ARTICLES IN SETTED CATEGORY
                    $query = $this->dbObject->getQuery(true);
                    $query->delete();
                    $query->from( $config["target_table"] );
                    $query->where( $WHERE_COLUMN."=".$EQUALS_VALUE );
                    $this->dbObject->setQuery($query);
                    $this->dbObject->query();

                    // ADD NEW ARTICLES
                    if(!empty($ARTICLES)){
                        foreach ($ARTICLES as $ARTICLE) {
                            if(!empty($ARTICLE)){
                                $query = $this->dbObject->getQuery(true);
                                $query->insert( $config["target_table"] );
                                $query->set( $WHERE_COLUMN."=".$EQUALS_VALUE );
                                foreach ($ARTICLE as $COLUMN_KEY => $COLUMN_VALUE){
                                    if(!is_numeric($COLUMN_VALUE)){$COLUMN_VALUE = $this->dbObject->Quote($COLUMN_VALUE);}
                                    $query->set( $this->dbObject->nameQuote($COLUMN_KEY)."=".$COLUMN_VALUE );
                                }
                                $this->dbObject->setQuery($query);
                                if ($this->dbObject->query()) {
                                    JFactory::getApplication()->enqueueMessage(JText::_('COM_AVC_OK_STORE').' ('.$ARTICLE["title"].')', 'message');
                                    // TURN OF GENERATE REQUEST
                                    $query = $this->dbObject->getQuery(true);
                                    $query->update( $this->db_generator );
                                    $query->where( "id =". (int)$config["id"] );
                                    $query->set( $this->dbObject->nameQuote("generate")."= 0" );
                                    $this->dbObject->setQuery($query);
                                    $this->dbObject->query();
                                } else {
                                    JFactory::getApplication()->enqueueMessage(JText::_('COM_AVC_ERROR_STORE').' ('.$ARTICLE["title"].')', 'error');
                                }
                            }
                        }
                    }//if(!empty($ARTICLES)){          

                }//if($config["generate"]==1){
            }//foreach ($generator_configs as $config) {
        }//if(!empty($generator_configs)){

    }//protected function execGenerator(){


    public static function execQuery($QUERY, $RETURN_QUERY = false){

        ///////////////////////////////////////////////
        //  CREATE LISTING
        ///////////////////////////////////////////////
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select( array( $QUERY["select"] ) );
        $query->from( $QUERY["from"] );

        // WHERE
        if(!empty($QUERY["where"])){
            foreach ($QUERY["where"] as $value) {
                $query->where($value);
            }
        }

        // HAVING
        if(!empty($QUERY["having"])){
            foreach ($QUERY["having"] as $value) {
                $query->having($value);
            }
        }

        // LEFT JOIN
        if(!empty($QUERY["left_join"])){
            foreach ($QUERY["left_join"] as $value) { 
                $query->leftJoin($value);
            }
        }

        // RIGHT JOIN
        if(!empty($QUERY["right_join"])){
            foreach ($QUERY["right_join"] as $value) { 
                $query->rightJoin($value);
            }
        }

        // INNER JOIN
        if(!empty($QUERY["inner_join"])){
            foreach ($QUERY["inner_join"] as $value) { 
                $query->innerJoin($value);
            }
        }

        // ORDER
        if(!empty($QUERY["order_by"])){
            $query->order($QUERY["order_by"]);
        }

        $db->setQuery($query);

        if($RETURN_QUERY){// MANDATORY FOR GET LIST
            return $query;
        }

        return $db->loadAssocList();

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

        $QUERY = $this->views[$this->curr_view_id]["query"];

        // Filter Search      
        $search_value = $this->mainframe->getUserStateFromRequest( 'filter_search_value', 'filter_search_value', $this->mainframe->getCfg('filter_search_value') );  
        if ( !empty($search_value) ) {
            $QUERY["having"]["search"] = $search_value;
        }

        // Filter Filters      
        $filter_value = $this->mainframe->getUserStateFromRequest( 'filter_filter_value', 'filter_filter_value', $this->mainframe->getCfg('filter_filter_value') );  
        if ( !empty($filter_value) ) {
            $QUERY["having"]["filter"] = $filter_value;
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
            $QUERY["order_by"] = $this->dbObject->getEscaped($order);
        }

        // GET ONLY SELECTED ROW FOR ROW LAYOUT
        if (JRequest::getVar('layout', 'default') == "row") {
            if($this->curr_row_id > 0){     
                $QUERY["having"]["row_layout"] = $this->dbObject->nameQuote("id") . " = " . $this->curr_row_id;
            }
        }

        return $this->execQuery($QUERY, true);

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
            $this->views[$viewID]["description"] = $value["description"];
            $this->views[$viewID]["icon_path"] = $value["icon_path"];
            $this->views[$viewID]["query"] = json_decode($value["query"], true);
            $this->checkJSON();
            $this->views[$viewID]["fields_config"] = json_decode($value["fields_config"], true);
            $this->checkJSON();
            $this->views[$viewID]["filters_config"] = json_decode($value["filters_config"], true);
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
