<?php

/**
 * @package		Joomla.Site
 * @subpackage	mod_breadcrumbs
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
// no direct access
defined('_JEXEC') or die;

if(JDEBUG){
    ini_set('display_errors', 'On');
    error_reporting(E_ALL | E_STRICT);
}

class AVC {

    private $DEBUG = false;

    private $dbObject;
    private $mainframe;
    private $dbView;
    private $viewDefaults;
    private $default_query;
    private $refresh_history;

    public $module_id;
    public $group;
    public $output;
    public $rows_total;
    public $collection;

    public $state_view;
    public $state_view_name;
    public $state_tmpl;
    public $state_order_by;
    public $state_limit;
    public $state_having;
    public $state_history;
    public $state_history_count;    
    public $state_visuals;
    public $state_where;
    public $state_left_join;
    public $state_fieldNames;

    function __construct($module_id, $view_id) {

        $this->dbView = "#__avc_view";
        $this->dbObject = JFactory::getDBO();
        $this->module_id = $module_id;  
        $this->mainframe = JFactory::getApplication();

        $this->state_view = $view_id;
        $this->state_history_count = 0;

        // LOAD VIEWS DEFAULTS        
        $this->viewDefaults = $this->getViewData($view_id);

        // CLEAR HISTORY
        // if entry view has table field "refresh_history" se to true it will purge history records from memory
        $this->refreshHistory();

        // LOAD HISTORY
        $this->loadHistory();

        // LOAD DEFAULTS FOR MISSING HISTORY
        // default are loaded after history because history controls which view is used.
        $this->loadDefaults();

        // UPDATE COLLECTION
        $AVC_STATE_COLLECTION = $this->mainframe->getUserStateFromRequest( "AVC_COLLECTION", "AVC_COLLECTION", $this->mainframe->getCfg("AVC_COLLECTION") );

        if(empty($AVC_STATE_COLLECTION)){
            $AVC_STATE_COLLECTION = '
            {
                "customer":{
                    "name_and_surname":"",
                    "street":"",
                    "street_number":"",
                    "zip":"",
                    "city":"",
                    "country":"",
                    "phone":""
                },
                "additional_details":{
                    "message":""
                }
            }
            ';
        }

        $this->collection = json_decode($AVC_STATE_COLLECTION, true);
        
        $this->checkJSON("TEST_COLLECTION");

        // CREATE OUTPUT
        $this->output = $this->getOutput();

        // CREATE CURRENT STEP IN HISTORY RECORDS          
        $this->createStep();  

        if(JDEBUG || $this->DEBUG){
            echo "<h1>MODULE HISTORY (".$this->module_id.")</h1>";
            echo "<pre>";
            var_export($this->state_history);
            echo "</pre><hr />";
            echo "<h1>OUTPUT (".$this->module_id.")</h1>";
            echo "<pre>";
            var_export($this->output);
            echo "</pre><hr />";
            echo "<h1>COLLECTION (".$this->module_id.")</h1>";
            echo "<pre>";
            var_export($this->collection);
            echo "</pre><hr />";
        }

    }

    protected function createStep(){
        if($this->state_history_count==0){
            $this->state_history_count=1;
        }

        $this->state_history[$this->group][$this->state_history_count]["modules"][$this->module_id] = array();
        $this->state_history[$this->group][$this->state_history_count]["modules"][$this->module_id]["view"] = $this->state_view;
        if(!empty($this->state_view_name)){
            $this->state_history[$this->group][$this->state_history_count]["modules"][$this->module_id]["view_name"] = $this->state_view_name;
        }
        if(!empty($this->state_order_by)){
            $this->state_history[$this->group][$this->state_history_count]["modules"][$this->module_id]["order_by"] = $this->state_order_by;
        }
        if(!empty($this->state_limit)){
            $this->state_history[$this->group][$this->state_history_count]["modules"][$this->module_id]["limit"] = $this->state_limit;
        }
        if(!empty($this->state_where)){
            $this->state_history[$this->group][$this->state_history_count]["modules"][$this->module_id]["where"] = $this->state_where;
        }
        if(!empty($this->state_having)){
            $this->state_history[$this->group][$this->state_history_count]["modules"][$this->module_id]["having"] = $this->state_having;
        }
        if(!empty($this->state_left_join)){
            $this->state_history[$this->group][$this->state_history_count]["modules"][$this->module_id]["left_join"] = $this->state_left_join;
        }

        $this->mainframe->setUserState("AVC_LAYOUT_STATE_HISTORY", json_encode($this->state_history));

    }

    protected function loadHistory(){

        // UPDATE SCROLL TO VAR
        $this->state_visuals = json_decode($this->mainframe->getUserStateFromRequest( "AVC_LAYOUT_STATE_VISUALS", "AVC_LAYOUT_STATE_VISUALS", $this->mainframe->getCfg("AVC_LAYOUT_STATE_VISUALS") ), true);
        if( empty($this->state_visuals) ){
            $this->state_visuals = array();
            $this->state_visuals["scroll_to"] = array();
            $this->state_visuals["scroll_to"]["x"] = "0";
            $this->state_visuals["scroll_to"]["y"] = "0";
        }

        // UPDATE STATES VARS FOR CURRENT MODULE FROM HISTORY
        $history = $this->mainframe->getUserStateFromRequest( "AVC_LAYOUT_STATE_HISTORY", "AVC_LAYOUT_STATE_HISTORY", $this->mainframe->getCfg("AVC_LAYOUT_STATE_HISTORY") );

        $this->group = $this->viewDefaults["view_group"];
        if( empty($this->group) ){
            $this->group = $this->module_id;
        }

        if(!empty($history)){

            $this->state_history = json_decode($history, true);
            $this->checkJSON("Get History State");

            if( !empty( $this->state_history[ $this->group ] ) ){

                $this->state_history_count = count($this->state_history[ $this->group ]);

                $last_step = end( $this->state_history[ $this->group ] );

                $history_curr = $last_step["modules"][ $this->module_id ];

                if(!empty($history_curr["view"])){
                    $this->state_view = $this->checkVar($history_curr["view"], $this->state_view);
                }

                if(!empty($history_curr["view_name"])){
                    $this->state_view_name = $history_curr["view_name"];
                }  
                if(!empty($history_curr["order_by"])){
                    $this->state_order_by = $history_curr["order_by"];
                }
                if(!empty($history_curr["limit"])){
                    $this->state_limit = $history_curr["limit"];
                }
                if(!empty($history_curr["where"])){ 
                    $this->state_where = $history_curr["where"];
                }
                if(!empty($history_curr["having"])){
                    $this->state_having = $history_curr["having"];
                }
                if(!empty($history_curr["left_join"])){
                    $this->state_left_join = $history_curr["left_join"];
                }
                if(!empty($history_curr["tmpl"])){
                    $this->state_tmpl = $history_curr["tmpl"];
                }

            }

        }

    }

    protected function loadDefaults(){

        // GET DEFAULTS FOR CURRENT VIEW
        // must be loaded agaim, maybe view is changed in history state
        $this->viewDefaults = $this->getViewData($this->state_view);

        $this->default_query = json_decode($this->viewDefaults["query"], true);
        $this->checkJSON("Parse View Query");
        
        // SET DEFAULTS FOR EMPTY STATES
        if(!empty($this->viewDefaults["name"])){
            $this->state_view_name = $this->checkVar( $this->state_view_name, $this->viewDefaults["name"] );
        }
        if(!empty($this->default_query["order_by"])){
            $this->state_order_by = $this->checkVar( $this->state_order_by, $this->default_query["order_by"] );
        }
        if(!empty($this->default_query["limit"])){
            $this->state_limit = $this->checkVar( $this->state_limit, $this->default_query["limit"] );
        }
        if(!empty($this->default_query["having"])){
            $this->state_having = $this->checkVar( $this->state_having, $this->default_query["having"] );
        }
        if(!empty($this->default_query["where"])){
            $this->state_where = $this->checkVar( $this->state_where, $this->default_query["where"] );
        }
        if(!empty($this->default_query["left_join"])){
            $this->state_left_join = $this->checkVar( $this->state_left_join, $this->default_query["left_join"] );
        }

        // GET DEFAULT TEMPLATE
        if(empty($this->state_tmpl)){
            if(!empty($this->viewDefaults["view_tmpl"])){
                $this->state_tmpl = json_decode($this->viewDefaults["view_tmpl"], true);
                $this->checkJSON("Parse View Template");
            }
        }
        if(empty( $this->state_tmpl["name"] )){
            $this->state_tmpl["name"] = "default";
        }

    }

    protected function refreshHistory(){

        $this->refresh_history = $this->viewDefaults["refresh_history"];

        if($this->refresh_history==1){// IF DEFAULTS ARE CHANGED

            // CLEAR HISTORY            
            $this->mainframe->setUserState("AVC_LAYOUT_STATE_HISTORY", null);

            // CLEAR SNAPSHOTS
            $this->mainframe->setUserState( "AVC_LAYOUT_STATE_HISTORY_SNAPSHOT", null );

            //TURN OFF REFRESH HISTORY
            $query = $this->dbObject->getQuery(true);
            $query->update( $this->dbObject->nameQuote($this->dbView) );
            $query->where( "id =".(int)$this->state_view );
            $query->set( "refresh_history = 0" );
            $this->dbObject->setQuery($query);
            $this->dbObject->query();
        }

    }

    function makeSafe($str, $replace=array(), $delimiter='_') {
        if( !empty($replace) ) {
            $str = str_replace((array)$replace, ' ', $str);
        }
        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
        $clean = strtolower(trim($clean, '_'));
        $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
        return $clean;
    }

    function checkJSON($itemName=null){
        if(version_compare(PHP_VERSION, '5.3.0') >= 0) { 
            if(!empty($this->module_id)){            
                $itemName.=' ( Module: '.$this->module_id.' )';
            }
            switch (json_last_error()) {
                case JSON_ERROR_NONE:
                break;
                case JSON_ERROR_DEPTH:
                    JFactory::getApplication()->enqueueMessage('Maximum stack depth exceeded: '.$itemName, 'error');
                break;
                case JSON_ERROR_STATE_MISMATCH:
                    JFactory::getApplication()->enqueueMessage('Underflow or the modes mismatch: '.$itemName, 'error');
                break;
                case JSON_ERROR_CTRL_CHAR:
                    JFactory::getApplication()->enqueueMessage('Unexpected control character found: '.$itemName, 'error');
                break;
                case JSON_ERROR_SYNTAX:
                    JFactory::getApplication()->enqueueMessage('Syntax error, malformed JSON: '.$itemName, 'error');
                break;
                case JSON_ERROR_UTF8:
                    JFactory::getApplication()->enqueueMessage('Malformed UTF-8 characters, possibly incorrectly encoded: '.$itemName, 'error');
                break;
                default:
                    JFactory::getApplication()->enqueueMessage(' Unknown error: '.$itemName, 'error');
                break;
            }
        }
    }

    protected function checkVar($check, $default){
        $value = $check;
        if(empty($value)){
            $value = $default;
        }
        if(empty($value)){
            $value = "";
        }
        return $value;
    }

    protected function getViewData($viewID){

        // GET CURRENT VIEW SETTINGS
        $query = $this->dbObject->getQuery(true);
        $query->select("*");
        $query->from($this->dbObject->nameQuote($this->dbView));
        $query->where($this->dbObject->nameQuote('id') . '=' . (int) $viewID);
        $this->dbObject->setQuery($query);
        $output = $this->dbObject->loadAssocList(); 
        return $output[0];

    }

    protected function getOutput()
    {    

        // CREATE QUERY
        $query = $this->dbObject->getQuery(true);
        if(!empty($this->default_query["select"]) && !empty($this->default_query["from"])){

            $query->select(array($this->default_query["select"])); 

            $query->from($this->default_query["from"]);

            // HAVING
            if(!empty($this->state_having)){
                foreach ($this->state_having as $value) {                    
                    $query->having($value);
                }
            } 

            // WHERE
            if(!empty($this->state_where)){
                foreach ($this->state_where as $value) { 
                    $query->where($value);
                }
            }

            // LEFT JOIN
            if(!empty($this->state_left_join)){
                foreach ($this->state_left_join as $value) { 
                    $query->leftJoin($value);
                }
            }

            // ORDER
            if(!empty($this->state_order_by)){
                $query->order($this->state_order_by);
            }

            // GET TOTAL
            // must be on the end, but before limit
            $this->dbObject->setQuery($query);
            $this->dbObject->query();
            $this->rows_total = $this->dbObject->getNumRows();

            // GET FINAL WITH LIMIT

            // PATCH FOR LIMIT RANGE GREATER THER NUM OF RESULTS
            if(!empty($this->state_limit)){

                $limit_split = explode(',', $this->state_limit);            
                if($limit_split[1] > $this->rows_total){
                    $limit_split[1]="0";
                }

                if($this->state_limit && $limit_split[1]!="0"){
                    $query.= "\nLIMIT ".$this->state_limit;
                }

            }

            if(JDEBUG || $this->DEBUG){                
                echo "<h1>OUTPUT QUERY (".$this->module_id.")</h1>";
                echo "select: ".$this->default_query["select"]."<br />";
                echo "from: ".$this->default_query["from"]."<br />";
                echo "having: <br />";
                var_export($this->state_having);
                echo "<br />";
                echo "where: <br />";
                var_export($this->state_where);
                echo "<br />";
                echo "left join: <br />";
                var_export($this->state_left_join);
                echo "<br />";
                echo "order by: ".$this->state_order_by."<br />";
                echo "<hr />";
            }

            $this->dbObject->setQuery($query);
            $this->dbObject->query();
            $output = $this->dbObject->loadAssocList();

            ///////////////////////////////////////////
            //
            // GET FIELD NAMES
            //
            $mySelect = $this->default_query["select"];
            $mySelect = str_replace(" AS ", " as ", $mySelect);
            $mySelectArray = explode(",", $mySelect);
            $myNames = array();
            foreach ($mySelectArray as $value) {
                $my_as = explode(" as ", $value);
                if(!empty($my_as[1])){
                    $myNames[] = str_replace(" ", "", $my_as[1]);
                }else{
                    $my_dots = explode(".", $value);
                    if(!empty($my_dots[1])){
                        $myNames[] = str_replace(" ", "", $my_dots[1]);
                    }else{
                        $myNames[] = str_replace(" ", "", $value);
                    }
                }
            }
            $this->state_fieldNames = $myNames;

        }

        return $output;
        
    }

}