<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_breadcrumbs
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

class CCS
{

    protected $CCS_Fields_Config;// List of Databases Identifier
	protected $dbConfig;
    protected $tablePrefix;
    protected $tableAlias;
    protected $templatesFolder;
    protected $fields; 

	public $moduleID;
	public $tableID;
	public $tmpTable;
	public $tmpRow;
	public $rowID;
    public $templatePath;  
    public $output;
	public $layout;

    function __construct($moduleID,$tableID,$tmpTable,$tmpRow)
	{
		$conf = JFactory::getConfig();
		$this->CCS_Fields_Config="ccs_admin_fields";
		$this->templatesFolder = JPATH_ROOT.DS.'modules'.DS.'mod_ccs'.DS.'templates'.DS;
		$this->dbConfig    = 'ccs_databases';		
		$this->moduleID    = $moduleID;			
		$this->tableID     = $tableID;		
		$this->tmpTable    = $tmpTable;		
		$this->tmpRow      = $tmpRow;
	    $this->layout      = JRequest::getVar( 'ccs_layout_'.$moduleID,'table' );
		$this->rowID       = JRequest::getVar( 'ccs_rowID_'.$moduleID,'0' );
	    $this->tablePrefix = $conf->get('dbprefix');
	    $this->tableAlias  = $this->getTableAlias();	
	    $this->fields      = $this->loadVisibleFields();	
	    if($this->layout=="table"){
	    	$this->getTable();
	    }else{
	    	$this->getRow();
	    }
	}

	protected function loadVisibleFields(){
				
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select($db->nameQuote('fld_alias'));
		$query->select($db->nameQuote('fld_type'));
		$query->select($db->nameQuote('fld_params'));
		$query->from($db->nameQuote($this->tablePrefix.$this->CCS_Fields_Config));
		$query->where($db->nameQuote('db_alias').' = '.$db->Quote($this->tableAlias));
		$query->order($db->getEscaped('ordering'.' '.'ASC'));
		$db->setQuery($query);
		$fieldsArray = $db->loadAssocList();
		$idArray = Array();
		$idArray['fld_alias']="id";
		$idArray['fld_type']="number";
		$idArray['fld_params']="";	
		array_unshift($fieldsArray, $idArray);// <-------------- Always insert id
		return $fieldsArray;

	}

	protected function getTableAlias(){
		$db = JFactory::getDBO();
	    $query = $db->getQuery(true);
	    $query->select($db->nameQuote('db_alias'));
		$query->from($db->nameQuote($this->tablePrefix.$this->dbConfig));
	    $query->where($db->nameQuote('id').'='.(int)$this->tableID); 
	    $db->setQuery($query);
	    return $db->loadResult();
	}

	protected function getTable()
	{
		$db = JFactory::getDBO();
	    $query = $db->getQuery(true);
		foreach ($this->fields as $field) {
			$query->select($db->nameQuote($field['fld_alias']));
		}
		$query->from($db->nameQuote($this->tablePrefix.$this->tableAlias));
		$query->order($db->getEscaped('ordering'.' '.'ASC'));
	    $db->setQuery($query);
	    $this->output = $db->loadAssocList();
	    $this->getTemplatePath();
	}

	protected function getRow()
	{
		$db = JFactory::getDBO();
	    $query = $db->getQuery(true);
		foreach ($this->fields as $field) {
			$query->select($db->nameQuote($field['fld_alias']));
		}
		$query->from($db->nameQuote($this->tablePrefix.$this->tableAlias));
	    $query->where($db->nameQuote('id').'='.(int)$this->rowID); 
		$query->order($db->getEscaped('ordering'.' '.'ASC'));
	    $db->setQuery($query);
	    $this->output = $db->loadAssocList();
	    $this->getTemplatePath();
	}

	protected function getTemplatePath()
	{
	    if($this->layout=="table"){
	    	$this->templatePath = $this->templatesFolder.$this->tmpTable;
	    }else{
	    	$this->templatePath = $this->templatesFolder.$this->tmpRow;
	    }
	}
}
