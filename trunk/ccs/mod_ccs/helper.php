<?php

/**
 * @package		Joomla.Site
 * @subpackage	mod_breadcrumbs
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
// no direct access
defined('_JEXEC') or die;

class CCS {

    private $ccs_fields_config; // List of Databases Identifier
    private $dbConfig;
    private $dbObject;
    private $tableAlias;
    private $templatesFolder;
    private $fields;
    public $moduleID;
    public $tableID;
    public $tmpTable;
    public $tmpRow;
    public $rowID;
    public $templatePath;
    public $output;
    public $layout;

    function __construct($moduleID, $tableID, $tmpTable, $tmpRow) {
        $this->CCS_Fields_Config = "ccs_admin_fields";
        $this->templatesFolder = JPATH_ROOT . DS . 'modules' . DS . 'mod_ccs' . DS . 'templates' . DS;
        $this->dbConfig = 'ccs_databases';
        $this->dbObject = JFactory::getDBO();
        $this->moduleID = $moduleID;
        $this->tableID = $tableID;
        $this->tmpTable = $tmpTable;
        $this->tmpRow = $tmpRow;
        $this->layout = JRequest::getVar('ccs_layout_' . $moduleID, 'table');
        $this->rowID = JRequest::getVar('ccs_rowID_' . $moduleID, '0');
        $this->tableAlias = $this->getTableAlias();
        $this->fields = $this->loadVisibleFields();
        if ($this->layout == "table") {
            $this->getTable();
        } else {
            $this->getRow();
        }
    }

    protected function loadVisibleFields() {

        $query = $this->dbObject->getQuery(true);
        $query->select($this->dbObject->nameQuote('fld_alias'));
        $query->select($this->dbObject->nameQuote('fld_type'));
        $query->select($this->dbObject->nameQuote('fld_params'));
        $query->from($this->dbObject->nameQuote('#__' . $this->ccs_fields_config));
        $query->where($this->dbObject->nameQuote('db_alias') . ' = ' . $this->dbObject->Quote($this->tableAlias));
        $query->order($this->dbObject->getEscaped('ordering' . ' ' . 'ASC'));
        $this->dbObject->setQuery($query);
        $fieldsArray = $this->dbObject->loadAssocList();
        $idArray = Array();
        $idArray['fld_alias'] = "id";
        $idArray['fld_type'] = "number";
        $idArray['fld_params'] = "";
        array_unshift($fieldsArray, $idArray); // <-------------- Always insert id
        return $fieldsArray;
    }

    protected function getTableAlias() {
        $query = $this->dbObject->getQuery(true);
        $query->select($this->dbObject->nameQuote('db_alias'));
        $query->from($this->dbObject->nameQuote('#__' . $this->dbConfig));
        $query->where($this->dbObject->nameQuote('id') . '=' . (int) $this->tableID);
        $this->dbObject->setQuery($query);
        return $this->dbObject->loadResult();
    }

    protected function getTable() {
        $query = $this->dbObject->getQuery(true);
        foreach ($this->fields as $field) {
            $query->select($this->dbObject->nameQuote($field['fld_alias']));
        }
        $query->from($this->dbObject->nameQuote('#__' . $this->tableAlias));
        $query->order($this->dbObject->getEscaped('ordering' . ' ' . 'ASC'));
        $this->dbObject->setQuery($query);
        $this->output = $this->dbObject->loadAssocList();
        $this->getTemplatePath();
    }

    protected function getRow() {
        $query = $this->dbObject->getQuery(true);
        foreach ($this->fields as $field) {
            $query->select($this->dbObject->nameQuote($field['fld_alias']));
        }
        $query->from($this->dbObject->nameQuote('#__' . $this->tableAlias));
        $query->where($this->dbObject->nameQuote('id') . '=' . (int) $this->rowID);
        $query->order($this->dbObject->getEscaped('ordering' . ' ' . 'ASC'));
        $this->dbObject->setQuery($query);
        $this->output = $this->dbObject->loadAssocList();
        $this->getTemplatePath();
    }

    protected function getTemplatePath() {
        if ($this->layout == "table") {
            $this->templatePath = $this->templatesFolder . $this->tmpTable;
        } else {
            $this->templatePath = $this->templatesFolder . $this->tmpRow;
        }
    }

}
