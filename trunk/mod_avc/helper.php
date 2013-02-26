<?php

/**
 * @package		Joomla.Site
 * @subpackage	mod_breadcrumbs
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
// no direct access
defined('_JEXEC') or die;

class AVC {

    private $dbObject;
    private $dbView;
    private $default_query;

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
    public $state_scrollTo;

    function __construct($module_id, $view_id) {

        $this->dbView = "#__avc_view";
        $this->dbObject = JFactory::getDBO();
        $this->module_id = $module_id;  

        // UPDATE STATES
        $mainframe =& JFactory::getApplication();

        // UPDATE SCROLL TO VAR
        $this->state_scrollTo = $mainframe->getUserStateFromRequest( "AVC_LAYOUT_STATE_SCROLLTO", "AVC_LAYOUT_STATE_SCROLLTO", $mainframe->getCfg("AVC_LAYOUT_STATE_SCROLLTO") );

        // UPDATE STATES VARS FOR CURRENT MODULE FROM HISTORY
        $history = $mainframe->getUserStateFromRequest( "AVC_LAYOUT_STATE_HISTORY", "AVC_LAYOUT_STATE_HISTORY", $mainframe->getCfg("AVC_LAYOUT_STATE_HISTORY") );

        $this->state_history = array(); 
        $this->state_history = json_decode($history, true);
        $history_curr = end($this->state_history["module".$this->module_id]);
        $this->state_view = $history_curr["view"];
        $this->state_view_name = $history_curr["view_name"];  
        $this->state_order_by = $history_curr["order_by"];
        $this->state_limit = $history_curr["limit"]; 
        $this->state_where = $history_curr["where"];
        $this->state_having = $history_curr["having"];

        $this->state_tmpl = array(); 
        $this->state_tmpl = json_decode($history_curr["tmpl"], true);

        // GET VIEW_ID
        if($this->state_view==""){
            $this->state_view = $view_id;
        }

        // CREATE OUTPUT
        $this->output = $this->getOutput();

        // IF STATE HISTORY FOR CURRENT MODULE IS EMPTY CREATE STATE RECORD USING DEFAULT STATES VARS
        if(count($this->state_history["module".$this->module_id])==0){

            $this->state_history["module".$this->module_id]["step1"] = array();
            $this->state_history["module".$this->module_id]["step1"]["view"] = $this->state_view;
            $this->state_history["module".$this->module_id]["step1"]["view_name"] = $this->state_view_name;
            $this->state_history["module".$this->module_id]["step1"]["tmpl"] = $this->state_tmpl;
            $this->state_history["module".$this->module_id]["step1"]["order_by"] = $this->state_order_by;
            $this->state_history["module".$this->module_id]["step1"]["limit"] = $this->state_limit;
            $this->state_history["module".$this->module_id]["step1"]["where"] = $this->state_where;
            $this->state_history["module".$this->module_id]["step1"]["having"] = $this->state_having;

        }

        // UPDATE HISTORY COUNT FOR CURRENT MODULE (NUMBER OF STEPS)
        $this->state_history_count = count($this->state_history["module".$this->module_id]);

    }

    protected function checkVar($check, $default){
        $value = $check;
        if(empty($value)){
            $value = $default;
        }
        return $value;
    }

    protected function getOutput()
    {

        // GET CURRENT VIEW SETTINGS
        $query = $this->dbObject->getQuery(true);
        $query->select("*");
        $query->from($this->dbObject->nameQuote($this->dbView));
        $query->where($this->dbObject->nameQuote('id') . '=' . (int) $this->state_view);
        $this->dbObject->setQuery($query);
        $myQueryList = $this->dbObject->loadAssocList();

        $this->default_query = json_decode($myQueryList[0]["query"], true);
        
        // SET DEFAULTS FOR EMPTY STATES
        $this->state_having = $this->checkVar($this->state_having, $this->default_query["having"]);
        $this->state_where = $this->checkVar($this->state_where, $this->default_query["where"]);
        $this->state_order_by = $this->checkVar($this->state_order_by, $this->default_query["order_by"]);
        $this->state_limit = $this->checkVar($this->state_limit, $this->default_query["limit"]);

        // GET VIEW NAME
        $this->state_view_name = $myQueryList[0]["name"];

        // GET TEMPLATE
        if(count($this->state_tmpl["module".$this->module_id])==0){
            if(!empty($myQueryList[0]["tmpl"])){
                $this->state_tmpl["module".$this->module_id] = json_decode($myQueryList[0]["tmpl"], true);
            }else{
                $this->state_tmpl["module".$this->module_id]["name"] = "default";
                $this->state_tmpl["module".$this->module_id]["vars"] = array();
                $this->state_tmpl["module".$this->module_id]["open"] = array();
            }
        }

        // CREATE QUERY
        $query = $this->dbObject->getQuery(true);
        if(!empty($this->default_query["select"]) && !empty($this->default_query["from"])){

            $query->select(array($this->default_query["select"])); 

            $query->from($this->default_query["from"]);

            // HAVING
            if($this->state_having != ""){
                $query->having($this->state_having);
            } 

            // WHERE
            if($this->state_where != ""){
                $query->where($this->state_where);
            }

            // LEFT JOIN
            if(!empty($this->default_query["left_join"])){
                $query->leftJoin($this->default_query["left_join"]);
            }

            // ORDER
            if($this->state_order_by != ""){
                $query->order($this->state_order_by);
            }

            // GET TOTAL
            // must be on the end, but before limit
            $this->dbObject->setQuery($query);
            $this->dbObject->query();
            $this->rows_total = $this->dbObject->getNumRows();

            // GET FINAL WITH LIMIT

            // PATCH FOR LIMIT RANGE GREATER THER NUM OF RESULTS
            $limit_split = explode(',', $this->state_limit);
            if($limit_split[1] > $this->rows_total){
                $limit_split[1]="0";
            }

            if($this->state_limit && $limit_split[1]!="0"){
                $query.= "\nLIMIT ".$this->state_limit;
            }

            $this->dbObject->setQuery($query);
            $this->dbObject->query();
            $output = $this->dbObject->loadAssocList();
        }

        return $output;
        
    }

}