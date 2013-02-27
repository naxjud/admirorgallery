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

        $this->state_history = json_decode($history, true);
        $history_curr = end($this->state_history["module".$this->module_id]);
        $this->state_view = $history_curr["view"];
        $this->state_view_name = $history_curr["view_name"];  
        $this->state_order_by = $history_curr["order_by"];
        $this->state_limit = $history_curr["limit"]; 
        $this->state_where = $history_curr["where"];
        $this->state_having = $history_curr["having"];
        $this->state_left_join = $history_curr["left_join"];
        $this->state_tmpl = $history_curr["tmpl"]; 

        // GET VIEW_ID
        if($this->state_view==""){
            $this->state_view = $view_id;
        }

        // CREATE OUTPUT
        $this->output = $this->getOutput();

        $this->state_history_count = count($this->state_history["module".$this->module_id]);

        if($this->state_history_count==0){
            $this->state_history_count=1;
        }

        $this->state_history["module".$this->module_id]["step".$this->state_history_count] = array();
        $this->state_history["module".$this->module_id]["step".$this->state_history_count]["view"] = $this->state_view;
        $this->state_history["module".$this->module_id]["step".$this->state_history_count]["view_name"] = $this->state_view_name;
        $this->state_history["module".$this->module_id]["step".$this->state_history_count]["tmpl"] = $this->state_tmpl;
        $this->state_history["module".$this->module_id]["step".$this->state_history_count]["order_by"] = $this->state_order_by;
        $this->state_history["module".$this->module_id]["step".$this->state_history_count]["limit"] = $this->state_limit;
        $this->state_history["module".$this->module_id]["step".$this->state_history_count]["where"] = $this->state_where;
        $this->state_history["module".$this->module_id]["step".$this->state_history_count]["having"] = $this->state_having;
        $this->state_history["module".$this->module_id]["step".$this->state_history_count]["left_join"] = $this->state_left_join;
    

        if(JDEBUG){
            echo "HISTORY:<br />";
            var_export($this->state_history["module".$this->module_id]);
            echo "<hr />";
        }

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
        $this->state_view_name = $this->checkVar($this->state_limit, $myQueryList[0]["name"]);
        $this->state_order_by = $this->checkVar($this->state_order_by, $this->default_query["order_by"]);
        $this->state_limit = $this->checkVar($this->state_limit, $this->default_query["limit"]);
        $this->state_having = $this->checkVar($this->state_having, $this->default_query["having"] );
        $this->state_where = $this->checkVar($this->state_where, $this->default_query["where"] );
        $this->state_left_join = $this->checkVar( $this->state_left_join, $this->default_query["left_join"] );


        // GET TEMPLATE
        if(empty($this->state_tmpl)){
            if(!empty($myQueryList[0]["tmpl"])){
                $this->state_tmpl = json_decode($myQueryList[0]["tmpl"], true);
            }else{
                $this->state_tmpl["name"] = "default";
                $this->state_tmpl["vars"] = array();
                $this->state_tmpl["open"] = array();
            }
        }


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

            if(JDEBUG){
                echo "QUERY:<br />";
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
        }

        return $output;
        
    }

}