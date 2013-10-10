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
    private $db_generator_log = "#__avc_generator_log";
    private $dbObject; // Container for DB class
    private $curr_view_id; // Current view id   
    private $curr_row_id; // Current row id, used for editing
    public $views;
    private $mainframe;
    private $fieldsArray;
    private $user;

    function __construct() {

        parent::__construct();

        $this->dbObject = JFactory::getDBO();
        $this->user = JFactory::getUser();
        $this->curr_view_id = JRequest::getVar('curr_view_id', 0); 

        $cids = JRequest::getVar('cid', array(), 'post', 'array');
        if (!empty($cids)) {
            $this->curr_row_id = (int)$cids[0];
        } else {
            $this->curr_row_id = 0;
        }

        $this->views = $this->getListViews();

        $this->mainframe = JFactory::getApplication();

        // Run autogenerate
        // Get autogenerate from component params
        $query = $this->dbObject->getQuery(true);
        $query->select("params");
        $query->from("#__extensions");
        $query->where( "name='com_avc'" );
        $this->dbObject->setQuery($query);
        $output = $this->dbObject->loadAssocList();
        $params = json_decode($output[0]["params"], true);
        if($params["autogenerate"]=="true"){
            $this->generate();
        }

    }


    public static function execQuery($QUERY, $RETURN_QUERY = false){

        if( empty($QUERY["select"]) || empty($QUERY["from"]) ){
            return;
        }

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

        // FOR ROW LAYOUT
        if (JRequest::getVar('layout', 'default') == "row") {
            if($this->curr_row_id > 0){     
                $QUERY["having"]["row_layout"] = $this->dbObject->nameQuote("id") . " = " . $this->curr_row_id;
            }
        }else{ // FOR TABLE LAYOUT

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
            $this->views[$viewID]["admin_only"] = $value["admin_only"];
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
                    $query->insert( $this->views[$this->curr_view_id]["query"]["from"] );
                    foreach ($this->views[$this->curr_view_id]["fields_config"] as $FIELD_ALIAS => $FIELD_VALUE){
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
                    $query->update( $this->views[$this->curr_view_id]["query"]["from"] );
                    $query->where( "id=".(int)$this->curr_row_id );
                    foreach ($this->views[$this->curr_view_id]["fields_config"] as $FIELD_ALIAS => $FIELD_VALUE){
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

        $this->updateGeneratorState();

    }

    function generate_toggle(){

        // Get autogenerate from component params
        $query = $this->dbObject->getQuery(true);
        $query->select("params");
        $query->from("#__extensions");
        $query->where( "name='com_avc'" );
        $this->dbObject->setQuery($query);
        $output = $this->dbObject->loadAssocList();
        $params = json_decode($output[0]["params"], true);
        if($params["autogenerate"] == "false"){
            $params["autogenerate"]="true";
        }else{
            $params["autogenerate"]="false";
        }

        // Set new params
        $query = $this->dbObject->getQuery(true);
        $query->update( "#__extensions" );
        $query->where( "name=".$this->dbObject->Quote('com_avc') );
        $query->set( $this->dbObject->nameQuote('params')."=".$this->dbObject->Quote(json_encode($params)) );
        $this->dbObject->setQuery($query);
        $this->dbObject->query();

        if($params["autogenerate"] == "true"){
            JFactory::getApplication()->enqueueMessage(JText::_('COM_AVC_GENERATOR_TOGGLE_TRUE'), 'message');
        }else{
            JFactory::getApplication()->enqueueMessage(JText::_('COM_AVC_GENERATOR_TOGGLE_FALSE'), 'message');  
        }
    }

    function getGeneratorStates(){
        // LIST ALL GENERATOR CONFIGURATIONS
        $query_json["select"] = '*';
        $query_json["from"] = $this->db_generator;
        $query_json["where"]["w1"] = "generate = 1";
        $generator_configs = $this->execQuery($query_json); 
        if(!empty($generator_configs)){
            JFactory::getApplication()->enqueueMessage(JText::_('COM_AVC_GENERATOR_NEEDED'), 'warning');
        }
    }

    function updateGeneratorState(){
        // LIST ALL GENERATOR CONFIGURATIONS
        $query_json["select"] = '*';
        $query_json["from"] = $this->db_generator;
        $generator_configs = $this->execQuery($query_json); 

        if(!empty($generator_configs)){
            foreach ($generator_configs as $key => $value) {
                if ( strpos(json_encode($value), $this->views[$this->curr_view_id]["query"]["from"]) ) {
                    // TURN ON GENERATE REQUEST
                    $query = $this->dbObject->getQuery(true);
                    $query->update( $this->db_generator );
                    $query->where( "id =". (int)$value["id"] );
                    $query->set( $this->dbObject->nameQuote("generate")."=1" );
                    $this->dbObject->setQuery($query);
                    $this->dbObject->query();
                }
            }
        } 
    }


    protected function avcGen_getVar($var, $default){
        if(empty($var)){
            $var = $default;
        }
        return $var;
    }

    function generate() {

        // COLLECT ALL GENERATOR CONFIGURATIONS
        $query_json["select"] = '*';
        $query_json["from"] = $this->db_generator;
        $query_json["where"]["w1"] = "generate = 1";
        $generator_configs = $this->execQuery($query_json); 

        // LOOP THROUGH GENERATOR CONFIGS
        if(!empty($generator_configs)){
        foreach ($generator_configs as $config) {

            // EXIT IF MANDATORY DATA MISSING
            if( empty($config["query"]) || empty($config["template_path"]) || empty($config["content_type"]) ){
                JFactory::getApplication()->enqueueMessage(JText::_('COM_AVC_ERROR_GEN_MANDATORY_EMPTY').': '.$config["name"], 'error');
                return;
            }

            if($config["test"]){
                    echo '<h2>GENERATOR TEST RUN ('.$config["name"].')</h2>';
            }//if($config["test"]){

            ////////////////////////////////////////////////////////////////////////////////
            //
            //  SET VARS AND PREPARATION
            //

            // UPDATE TAGET VARS DEPENDING ON CONTENT TYPE (ARTICLE, CATEGORY...)
            switch ($config["content_type"]) {
                case 'article':
                    $target_table = "#__content";
                    $target_target_primary_key = "id";
                    break;
                case 'category':
                    $target_table = "#__categories";
                    $target_target_primary_key = "id";
                    break;
                
                default:
                    # code...
                    break;
            }

            // JSON DECODE FOR CUSTOM VALUES
            $config["custom_values"] = json_decode($config["custom_values"], true);                
            $this->checkJSON();

            // JSON DECODE FOR QUERY
            $config["query"] = json_decode($config["query"], true);                
            $this->checkJSON();

            // SET DEFAULT FOR PRIMARY KEY
            if( empty($config["primary_key"]) ){
                $config["primary_key"] = "id";
            }

            // COLLECT ALL GENERATOR LOGS DATA
            $log_query = array();
            $log_query["select"] = "*";
            $log_query["from"]   = $this->db_generator_log;
            $log_rows = $this->execQuery($log_query);

            // FORMAT LOGS FOR EASY CHECKING OF IDs
            foreach ($log_rows as $log_row) {
                if( empty($LOGs[ $log_row["gen_id"] ]) ){
                    $LOGs[ $log_row["gen_id"] ] = array();
                }
                $LOGs[ $log_row["gen_id"] ][ $log_row["source_id"] ] = $log_row["target_id"];
            }

            // GET NEXT INCREMENT FOR PRIMARY KEY
            $max_query["select"] = "MAX(".$target_target_primary_key.")";
            $max_query["from"]   = $target_table;
            $output = $this->execQuery($max_query);
            $target_primary_key_max = (int)$output[0]["MAX(".$target_target_primary_key.")"];
            if(empty($target_primary_key_max)){
                $target_primary_key_max = 0;
            }

            // COLLECT ROWS
            $ROWs = $this->execQuery($config["query"]);

            //
            // SET VARS AND PREPARATION
            //
            //////////////////////////////////////////////////////////////////////


            //////////////////////////////////////////////////////////////////////
            //
            // REMOVE OLD CONTENT AND LOGS
            //

            // INIT REMOVE LOGS QUERY
            $query_del_logs = $this->dbObject->getQuery(true);
            $query_del_logs->delete();
            $query_del_logs->from( $this->db_generator_log );
            $query_del_logs->where( "gen_id = ".$config["id"] );

            // INIT REMOVE CONTENT QUERY
            $query_del_content = $this->dbObject->getQuery(true);
            $query_del_content->delete();
            $query_del_content->from( $target_table );
            if(!empty($LOGs[ $config["id"] ])){
                foreach ($LOGs[ $config["id"] ] as $data_id) {
                    $query_del_content->where( $this->dbObject->quoteName($target_target_primary_key)." = ".$data_id, "OR" );
                }
            }

            if(!$config["test"]){

                // REMOVE LOGS
                $this->dbObject->setQuery($query_del_logs);
                if(!$this->dbObject->query()){
                    JFactory::getApplication()->enqueueMessage(JText::_('COM_AVC_ERROR_REMOVE_LOG_ERROR').': '.$config["name"], 'error');
                }

                // REMOVE CONTENTS
                if( $query_del_content->where != NULL ){ // IF QUERY->WHERE EMPTY AVOID REMOVE CONTENT (It sometimes removes all content from table, maybe because I missed this shit)
                    $this->dbObject->setQuery($query_del_content);
                    if(!$this->dbObject->query()){
                        JFactory::getApplication()->enqueueMessage(JText::_('COM_AVC_ERROR_REMOVE_CONTENT_ERROR').': '.$config["name"], 'error');
                    }
                }

            }else{//if(!$config["test"]){

                echo '<b>DELETE LOGS</b>';
                echo '<pre>';
                var_dump($query_del_logs->where);
                echo '</pre>'; 
                echo '<b>DELETE CONTENTS</b>';
                echo '<pre>';
                var_dump($query_del_content->where);
                echo '</pre>';     

            }//if(!$config["test"]){

            //
            // REMOVE OLD CONTENT AND LOGS
            //
            //////////////////////////////////////////////////////////////////////


            //////////////////////////////////////////////////////////////////////
            //
            // ROWS AND TMPL MARRIED 
            //

            require( JPATH_ROOT.DS.$config["template_path"] );

            //
            // ROWS AND TMPL MARRIED 
            //
            //////////////////////////////////////////////////////////////////////


            //////////////////////////////////////////////////////////////////////
            //
            // POLISHING OF DATA, ADD NEW LOGS, ADD NEW CONTENT
            //

            if(!empty($DATAs)){// Data is created in template file. It's an array object, each element is a new content.

                // DECLARE PREDEFINED VALUES FOR CONTENT TYPES
                switch ($config["content_type"]) {
                    case 'article':
                        $PREDEFINEDs = array( "id" => 1, "asset_id" => 0, "state" => 1, "catid" => 1, "created" => date("Y-m-d H:i:s"), "access" => 1, "featured" => 0, "language" => "*", "created_by" => $this->user->id );
                        break;
                    case 'category':
                        $PREDEFINEDs = array( "id" => 1, "asset_id" => 0, "parent_id" => 1, "extension" => "com_content", "created_time" => date("Y-m-d H:i:s"), "published" => 1, "access" => 1, "asset_id" => 0, "parent_id" => 1, "language" => "*", "created_user_id" => $this->user->id );                          
                        break;
                }  

                // INIT LOGS QUERY
                $query_make_logs = $this->dbObject->getQuery(true);
                $query_make_logs->insert( $this->db_generator_log );                    
                $query_make_logs_columns = array("gen_id", "source_id", "target_id");
               
                // INIT NEW CONTENT QUERY
                $query_make_content = $this->dbObject->getQuery(true);
                $query_make_content->insert( $target_table );
                $query_make_content_columns = array();

                ////////////////////////////
                //
                // LOOP THROUGH DATA AND PREPARE LOGS AND CONTENT FOR WRITTING
                //

                foreach ($DATAs as $key => $data) {


                    // USE PREDEFINED VALUES FOR EMPTY DATA VALUES
                    if(!empty($PREDEFINEDs)){
                    foreach ($PREDEFINEDs as $predefined_key => $predefined_value) {
                        if( empty($data[$predefined_key]) ){
                            $data[$predefined_key] = $predefined_value;

                        }
                    }      
                    }

                    // PREPARE NEW LOGS VALUES & SET NEW DATA ID
                    if( empty($LOGs[ $config["id"] ][ $data["id"] ]) ){// Set primary key max increment value if content with the ID doesn't exists in Log
                        $target_primary_key_max++;
                        $LOGs[ $config["id"] ][ $data["id"] ] = $target_primary_key_max;
                    }
                    $ORIG_data_id = $data["id"];
                    $data["id"] = $LOGs[ $config["id"] ][ $data["id"] ];// Update for data id must be in front of make new content
                    $query_make_logs->values($config["id"].','.$ORIG_data_id.','.$data["id"]);

                    // PREPARE MAKE NEW CONTENT VALUES
                    if( empty($query_make_content_columns) ){
                        foreach ($data as $column_key => $column_value){  
                            $query_make_content_columns[] = $column_key;
                        }
                    }
                    $query_make_content_values = array();
                    foreach ($data as $column_key => $column_value){
                        if(!is_numeric($column_value)){$column_value = $this->dbObject->Quote($column_value);}
                        $query_make_content_values[] = $column_value;
                    }
                    $query_make_content->values(implode(',', $query_make_content_values));

                }//foreach ($DATAs as $key => $data) {

                //
                // LOOP THROUGH DATA AND PREPARE LOGS AND CONTENT FOR WRITTING
                //
                ////////////////////////////

                ////////////////////////////
                //
                // WRITE NEW LOGS AND CONTENT TO DB
                //
                
                if(!$config["test"]){

                    // MAKE NEW LOG
                    $query_make_logs->columns( $this->dbObject->quoteName($query_make_logs_columns) );
                    $this->dbObject->setQuery($query_make_logs);
                    if(!$this->dbObject->query()){
                        JFactory::getApplication()->enqueueMessage(JText::_('COM_AVC_ERROR_MAKE_LOG_ERROR').': '.$config["name"], 'error');
                    }

                    // MAKE NEW CONTENT IN DATABASE
                    $query_make_content->columns( $this->dbObject->quoteName($query_make_content_columns) );
                    $this->dbObject->setQuery($query_make_content);
                    if($this->dbObject->query()){
                        JFactory::getApplication()->enqueueMessage(JText::_('COM_AVC_ERROR_GEN_SUCCESS').': '.$config["name"].', '.count($DATAs).' rows created', 'message');
                    }

                }else{//if(!$config["test"]){

                    echo '<b>CONTENT VALUES</b>';
                    echo '<pre>';
                    var_dump($query_make_content->values);
                    echo '</pre>'; 

                    echo '<b>LOGS VALUES</b>';
                    echo '<pre>';
                    var_dump($query_make_logs->values);
                    echo '</pre>';   

                }//if(!$config["test"]){

                //
                // WRITE NEW LOGS AND CONTENT TO DB
                //
                ////////////////////////////

                ////////////////////////////
                //
                // POSTGENERATE PROCESSES (Required for fixing category's structure)
                //

                switch ($config["content_type"]) {

                    case 'article':
                        // SAVE ARTICLE
                        // JTable::addIncludePath(JPATH_SITE.DS."administrator".DS."components".DS."com_content".DS."tables");
                        // JModel::addIncludePath (JPATH_SITE.DS."administrator".DS."components".DS."com_content".DS."models");
                        // $article_model = JModel::getInstance('article', 'contentModel');                     
                        break;

                    case 'category':
                        // MUST BE REBUILTED
                        JTable::addIncludePath(JPATH_SITE.DS."administrator".DS."components".DS."com_categories".DS."tables");
                        JModel::addIncludePath (JPATH_SITE.DS."administrator".DS."components".DS."com_categories".DS."models");
                        $category_model = JModel::getInstance('category', 'categoriesModel');    
                        $category_model->rebuild();
                        break;

                }//switch ($config["content_type"]) {

                //
                // POSTGENERATE PROCESSES (Required for fixing category's structure)
                //
                ////////////////////////////

            }//if(!empty($DATAs)){

            //
            // POLISHING OF DATA, ADD NEW LOGS, ADD NEW CONTENT
            //
            //////////////////////////////////////////////////////////////////////


            //////////////////////////////////////////////////////////////////////
            //
            // TURN OF GENERATE REQUEST
            //

            $query = $this->dbObject->getQuery(true);
            $query->update( $this->db_generator );
            $query->where( "id =". (int)$config["id"] );
            $query->set( $this->dbObject->nameQuote("generate")."=0" );
            $this->dbObject->setQuery($query);
            $this->dbObject->query();

            //
            // TURN OF GENERATE REQUEST
            //
            //////////////////////////////////////////////////////////////////////

        }//foreach ($generator_configs as $config) {
        }//if(!empty($generator_configs)){
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
        $this->updateGeneratorState();
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
