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
    private $default_query;
    private $refresh_history;

    public $module_id;
    public $output;
    public $rows_total;

    public $state_view;
    public $state_view_name;
    public $state_tmpl;
    public $state_order_by;
    public $state_limit;
    public $state_having;
    public $state_history;
    public $state_history_count;    
    public $state_history_snapshot;
    public $state_history_snapshot_is_master;
    public $state_scrollTo;
    public $state_where;
    public $state_left_join;
    public $state_fieldNames;

    function __construct($module_id, $view_id, $is_master) {

        $this->dbView = "#__avc_view";
        $this->dbObject = JFactory::getDBO();
        $this->module_id = $module_id;  
        $this->mainframe = JFactory::getApplication();
        $this->state_history_snapshot_is_master = $is_master;

        $this->state_view = $view_id;
        $this->state_history_count = 0;

        // CLEAR HISTORY
        // if entry view has table field "refresh_history" se to true it will purge history records from memory
        $this->refreshHistory($view_id);

        // LOAD HISTORY
        $this->loadHistory();

        // LOAD DEFAULTS FOR MISSING HISTORY
        // default are loaded after history because history controls which view is used.
        $this->loadDefaults($this->state_view);

        // CREATE OUTPUT
        $this->output = $this->getOutput();

        // CREATE CURRENT STEP IN HISTORY RECORDS
        $this->createStep(); 

        // HISTORY SNAPSHOT
        // snapshot is used for master breadcrumbs
        $this->historySnapshot();   

        if(JDEBUG || $this->DEBUG){
            echo "<h1>MODULE HISTORY (".$this->module_id.")</h1>";
            echo "<pre>";
            var_export($this->state_history);
            echo "</pre><hr />";
        }

    }

    protected function createStep(){
        if($this->state_history_count==0){
            $this->state_history_count=1;
        }

        $this->state_history["module".$this->module_id]["step".$this->state_history_count] = array();
        $this->state_history["module".$this->module_id]["step".$this->state_history_count]["view"] = $this->state_view;
        if(!empty($this->state_view_name)){
            $this->state_history["module".$this->module_id]["step".$this->state_history_count]["view_name"] = $this->state_view_name;
        }
        if(!empty($this->state_order_by)){
            $this->state_history["module".$this->module_id]["step".$this->state_history_count]["order_by"] = $this->state_order_by;
        }
        if(!empty($this->state_limit)){
            $this->state_history["module".$this->module_id]["step".$this->state_history_count]["limit"] = $this->state_limit;
        }
        if(!empty($this->state_where)){
            $this->state_history["module".$this->module_id]["step".$this->state_history_count]["where"] = $this->state_where;
        }
        if(!empty($this->state_having)){
            $this->state_history["module".$this->module_id]["step".$this->state_history_count]["having"] = $this->state_having;
        }
        if(!empty($this->state_left_join)){
            $this->state_history["module".$this->module_id]["step".$this->state_history_count]["left_join"] = $this->state_left_join;
        }

        $this->mainframe->setUserState("AVC_LAYOUT_STATE_HISTORY", json_encode($this->state_history));
    }

    protected function historySnapshot(){

        ////////////////////////////////////////////////////
        //
        // MAKE A HISTORY SNAPSHOT AND UPDATE MAIN CONTENT BREADCRUMBS
        //
        // - hist.snaps. has num. of steps equal to steps of master module
        // - only single module can be master
        // - compare steps cound in hist.snaps. with state history count
        // - if smaller add snapshot, if larger remove snapshot
        // - after update loop hist.snaps. to create main content breadcrumbs
        //

        if ($this->state_history_snapshot_is_master) {

            // GET HIST.SNAPS
            $history_snaps = json_decode( $this->mainframe->getUserState( "AVC_LAYOUT_STATE_HISTORY_SNAPSHOT", null ), true );
            $this->checkJSON("Get History Snapshots");

            // TEST
            $emptySnaps = false;
            if($emptySnaps){
                $history_snaps = null;
            }

            // COUNT STEPS
            $history_snaps_count = count($history_snaps);
            // COMPARE STEPS, ADD OR REMOVE STEPS
            if($this->state_history_count!=0){
                if($history_snaps_count < $this->state_history_count){
                    $history_snaps[ "snapshot".$this->state_history_count ] = $this->state_history;
                }else if($history_snaps_count > $this->state_history_count){
                    while ( count($history_snaps) > $this->state_history_count ) {                    
                        unset( $history_snaps[ "snapshot".count($history_snaps) ] );
                    }
                }else if($history_snaps_count == $this->state_history_count){
                    $history_snaps[ "snapshot".$history_snaps_count ] = $this->state_history;
                }
            }

            $history_snaps_count = count($history_snaps);

            // UPDATE HIST.SNAPS. STATE
            $this->mainframe->setUserState( "AVC_LAYOUT_STATE_HISTORY_SNAPSHOT", json_encode($history_snaps) );
            $this->checkJSON("Set History Snapshots");

            if(JDEBUG || $this->DEBUG){
                echo "<h1>HISTORY SNAPSHOTS (".$this->module_id.")</h1>";
                echo "<pre>";
                var_dump($history_snaps);
                echo "</pre>";
            }

            // SET STATE VAR
            $this->state_history_snapshot = $history_snaps;

            // MAKE BREADCRUMPS
            if(!empty($history_snaps)){
                $app    = JFactory::getApplication();
                $items = $app->getPathway();
                $new_items = (array)$items;
                $new_items = reset($new_items);
                $last_item = end($new_items);
                $last_item->name=$this->state_history["module".$this->module_id]["step1"]["view_name"];
                $last_item->link='javascript:AVC_LAYOUT_SNAPSHOT(\''.$this->module_id.'\', \'snapshot1\');';
                array_pop($new_items);
                $new_items[] = $last_item;
                for ($i=1; $i < $history_snaps_count; $i++) { 
                    $new_items[] = (object) array( 'name'=>$this->state_history["module".$this->module_id]["step".($i+1)]["view_name"], 'link'=>'javascript:AVC_LAYOUT_SNAPSHOT(\''.$this->module_id.'\', \'snapshot'.($i+1).'\');' );
                }
                $items->setPathWay(null);
                foreach ($new_items as $item) {
                    $items->addItem($item->name, $item->link);
                }
            }

            if(JDEBUG || $this->DEBUG){
                echo "<h1>BREADCRUMPS ITEMS (".$this->module_id.")</h1>";
                echo "<pre>";
                var_dump($items);
                echo "</pre>";
            }

        }

        //
        // SNAPSHOT
        //
        ///////////////////////////////////////////////////////////////
    }

    protected function loadHistory(){
        // UPDATE SCROLL TO VAR
        $this->state_scrollTo = $this->mainframe->getUserStateFromRequest( "AVC_LAYOUT_STATE_SCROLLTO", "AVC_LAYOUT_STATE_SCROLLTO", $this->mainframe->getCfg("AVC_LAYOUT_STATE_SCROLLTO") );

        // UPDATE STATES VARS FOR CURRENT MODULE FROM HISTORY
        $history = $this->mainframe->getUserStateFromRequest( "AVC_LAYOUT_STATE_HISTORY", "AVC_LAYOUT_STATE_HISTORY", $this->mainframe->getCfg("AVC_LAYOUT_STATE_HISTORY") );

        if(!empty($history)){

            $this->state_history = json_decode($history, true);
            $this->checkJSON("Get History State");

            if(!empty($this->state_history["module".$this->module_id])){

                $this->state_history_count = count($this->state_history["module".$this->module_id]);

                $history_curr = end($this->state_history["module".$this->module_id]);
                $this->state_view = $this->checkVar($history_curr["view"], $this->state_view);

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

    protected function loadDefaults($view_id){

        // GET DEFAULTS FOR CURRENT VIEW
        $myQueryList = $this->getViewData($view_id);

        $this->default_query = json_decode($myQueryList["query"], true);
        $this->checkJSON("Parse View Query");
        
        // SET DEFAULTS FOR EMPTY STATES
        if(!empty($myQueryList["name"])){
            $this->state_view_name = $this->checkVar( $this->state_view_name, $myQueryList["name"] );
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
            if(!empty($myQueryList["tmpl"])){
                $this->state_tmpl = json_decode($myQueryList["tmpl"], true);
                $this->checkJSON("Parse View Template");
            }
        }
        if(empty( $this->state_tmpl["name"] )){
            $this->state_tmpl["name"] = "default";
        }
        if(empty( $this->state_tmpl["vars"] )){
            $this->state_tmpl["vars"] = array();
        }
        if(empty( $this->state_tmpl["open"] )){
            $this->state_tmpl["open"] = array();
        }

    }

    protected function refreshHistory($view_id){
        $viewDefaults = $this->getViewData($view_id);
        $this->refresh_history = $viewDefaults["refresh_history"];

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
       switch (json_last_error()) {
            case JSON_ERROR_NONE:
            break;
            case JSON_ERROR_DEPTH:
                JFactory::getApplication()->enqueueMessage('Maximum stack depth exceeded: '.$itemName.' ( Module: '.$this->module_id.' )', 'error');
            break;
            case JSON_ERROR_STATE_MISMATCH:
                JFactory::getApplication()->enqueueMessage('Underflow or the modes mismatch: '.$itemName.' ( Module: '.$this->module_id.' )', 'error');
            break;
            case JSON_ERROR_CTRL_CHAR:
                JFactory::getApplication()->enqueueMessage('Unexpected control character found: '.$itemName.' ( Module: '.$this->module_id.' )', 'error');
            break;
            case JSON_ERROR_SYNTAX:
                JFactory::getApplication()->enqueueMessage('Syntax error, malformed JSON: '.$itemName.' ( Module: '.$this->module_id.' )', 'error');
            break;
            case JSON_ERROR_UTF8:
                JFactory::getApplication()->enqueueMessage('Malformed UTF-8 characters, possibly incorrectly encoded: '.$itemName.' ( Module: '.$this->module_id.' )', 'error');
            break;
            default:
                JFactory::getApplication()->enqueueMessage(' Unknown error: '.$itemName.' ( Module: '.$this->module_id.' )', 'error');
            break;
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