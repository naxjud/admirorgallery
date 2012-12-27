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

    private $db_view = "#__avc_view";
    private $db_field = "#__avc_field";
    private $db_view_fields = "#__avc_view_fields";
    private $dbObject; // Container for DB class
    public $tableID; // Current view id
    private $views; // Array of DB rows of all views (needed for menu)
    private $curr_row_id; // Current row id, used for editing
    private $viewFields;
    public $moduleID;
    public $rowID;
    public $output;
    public $layout;
    public $template;


    function __construct($moduleID, $tableID, $tmpTable, $tmpRow) {

        $this->dbObject = JFactory::getDBO();
        $this->tableID = $tableID;
        $this->views = $this->views();
        $this->curr_view_row = $this->currViewRow();        
        $this->viewFields = $this->viewFields();

        $this->moduleID = $moduleID;

        $this->rowID = JRequest::getVar('AVC_rowID_' . $moduleID, '0');

        $this->output = $this->getListQuery();

        $this->template = JPATH_ROOT . DS . 'modules' . DS . 'mod_avc' . DS . 'templates' . DS;
        if (JRequest::getVar('AVC_layout_' . $moduleID, 'table') == "table") {
            $this->layout = "table";
            $this->template = $tmpTable;
        } else {
            $this->layout = "row";
            $this->template = $tmpRow;
        }

    }

    function getListQuery() {
        $query = $this->dbObject->getQuery(true);

        $query->select( $this->dbObject->nameQuote($this->curr_view_row["key_field_name"]) );
        foreach ($this->viewFields as $viewField) {
            if($viewField["name"]!=$this->curr_view_row["key_field_name"]){
                $query->select( $this->dbObject->nameQuote($viewField["name"]) );
            }
        }

        //$query->order( $this->dbObject->getEscaped( 'ordering ASC' ) );
        $query->from($this->dbObject->nameQuote($this->curr_view_row["name"]));
        if($this->rowID > 0){
            $query->where($this->dbObject->nameQuote($this->curr_view_row["key_field_name"])."=".$this->rowID);
        }
        $this->dbObject->setQuery($query);
        $rows = $this->dbObject->loadAssocList();
        return $rows;
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

    function currViewRow() {
        foreach ($this->views as $view) {
            if($view["id"]==$this->tableID){                
                return $view;
            }
        }
    }

    function viewFields() {
        $query = $this->dbObject->getQuery(true);
        $query->select( $this->db_field.'.*' );
        $query->from( $this->dbObject->nameQuote($this->db_view_fields) );
        $query->where( "view_id=".$this->tableID );
        $query->join( "INNER" , $this->dbObject->nameQuote($this->db_field).' ON '.$this->db_view_fields.'.field_id='.$this->db_field.'.id' );
        $query->order($this->dbObject->getEscaped($this->db_field.'.ordering ASC'));
        $this->dbObject->setQuery($query);
        $rows = $this->dbObject->loadAssocList();
        return $rows;
    }

}