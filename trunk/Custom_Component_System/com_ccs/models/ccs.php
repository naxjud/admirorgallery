<?php
/**
 * Hello Model for Hello World Component
 * 
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://docs.joomla.org/Developing_a_Model-View-Controller_Component_-_Part_2
 * @license    GNU/GPL
 */
 
// No direct access
 
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.modellist' );
 
/**
 * Hello Model
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class CcsModelCcs extends JModelList
{

    public $current_alias;
    public $CCS_Database_Config;// List of Databases Identifier
    public $CCS_Fields_Config;// List of Databases Identifier
    public $db_prefix;
    public $fields;
    
    function __construct()
	{
	    parent::__construct();
		$conf = JFactory::getConfig();
	    $this->current_alias=JRequest::getVar( 'alias', 'com_ccs_home' );
	    $this->CCS_Database_Config="ccs_databases";
	    $this->CCS_Fields_Config="ccs_admin_fields";
	    $this->db_prefix=$conf->get('dbprefix');
		$this->fields = $this->loadVisibleFields();
	} 

	function getFields()
	{
	    return $this->fields;
	}   
		
	function getAlias()
	{
	    return $this->current_alias;
	}   

	protected function loadVisibleFields(){
				
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select($db->nameQuote('fld_alias'));
		$query->select($db->nameQuote('fld_type'));
		$query->select($db->nameQuote('fld_params'));
		$query->from($db->nameQuote($this->db_prefix.$this->CCS_Fields_Config));
		$query->where($db->nameQuote('db_alias').' = '.$db->Quote($this->current_alias));
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
   
	protected function getListQuery()
	{

		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		foreach ($this->fields as $field) {
			$query->select($db->nameQuote($field['fld_alias']));
		}

		$query->from($db->nameQuote($this->db_prefix.$this->current_alias));
			
		// Filter Order
		$order = $this->getState('filter_order');
		if(!empty($order))
		{
            $query->order($db->getEscaped($order.' '.$this->getState('filter_order_Dir', 'DESC')));
        }
        
      // Filter Search
      $search_column = $this->getState('filter_search_column');
		$search_value = $this->getState('filter_search_value');
		if (!empty($search_value))
		{
			$query->where($db->nameQuote($search_column)." LIKE ".$db->Quote('%'.$search_value.'%'));
		}
		
		return $query;
	}

	function fieldsExists($fields, $fieldsArray){// Compare 2 arrays is item from first array exists in second
        $check = 1;
        if(count($fields)>0){
            while(list($k,$v)=each($fields))
	        {
        		if(!in_array($v, $fieldsArray)) { $check = 0; }
	        }
	      }
	      else { RETURN 0; }
	    RETURN $check;
    }
	
	function getChildren()
	{	    
		$conf = JFactory::getConfig();
		
		$db = JFactory::getDBO();
		
		$query = $db->getQuery(true);
		$query->select($db->nameQuote('db_alias'));
		$query->from($db->nameQuote($this->db_prefix.$this->CCS_Database_Config));
		$query->where($db->nameQuote('parent_db_alias').' = '.$this->db_prefix.$this->current_alias);
		$db->setQuery($query);
			
      return $db->loadAssocList();
	}
	
	function getRow()
	{
	    $cids = JRequest::getVar( 'cid', array(), 'post', 'array' );
		$db = JFactory::getDBO();
	    $query = $db->getQuery(true);
	    $query->select('*');
		$query->from($db->nameQuote($this->db_prefix.$this->current_alias));
	    $query->where($db->nameQuote('id').'='.(int)$cids[0]); 
	    $db->setQuery($query);
	    $db->loadRow();
	    $db->query();
	    $row = $db->loadAssoc();
	    return $row;
	}
		
    function populateState() {		
    
		// Filter Order
        $filter_order = JRequest::getCmd('filter_order');
        $filter_order_Dir = JRequest::getCmd('filter_order_Dir');
        if(empty($filter_order)){
        	$filter_order='ordering';
        	$filter_order_Dir='asc';
        }        
        $this->setState('filter_order', $filter_order);
        $this->setState('filter_order_Dir', $filter_order_Dir);        
        
        // Filter Search
		$search_column = JRequest::getCmd('filter_search_column');
		$this->setState('filter_search_column', $search_column);
		$search_value = JRequest::getCmd('filter_search_value');
		$this->setState('filter_search_value', $search_value);

		
        $breadcrumbmenuState = JRequest::getCmd('breadcrumbmenuState');
        $this->setState('breadcrumbmenuState', $breadcrumbmenuState);
        
        parent::populateState();
    }
       
    function store()
    {    
            $db =& JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->insert($db->nameQuote($this->db_prefix.$this->current_alias));
            
            foreach ($this->fields as $field)
            {
                if($field["fld_alias"]!="id"){
	                $field_value = JRequest::getVar( $field["fld_alias"], null, 'post' , 'STRING', JREQUEST_ALLOWRAW );
	                $query->set($db->nameQuote($field["fld_alias"]).'='.$db->Quote($field_value));
                }
            }
            
            $db->setQuery( $query );
            if($db->query())
	        {
	            JFactory::getApplication()->enqueueMessage(JText::_('COM_CCS_OK_STORE'), 'message');
	        }else{
	            JFactory::getApplication()->enqueueMessage(JText::_('COM_CCS_ERROR_STORE'), 'error');
	        }
    }
    
    function replace()
    {    

		$rowID = JRequest::getVar( 'id', null );

        $db =& JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->update($db->nameQuote($this->db_prefix.$this->current_alias));
        $query->where($db->nameQuote('id').'='.(int)$rowID);

        $tblEdit_alias = JRequest::getVar( "tblEdit_alias", null, 'post' , 'STRING', JREQUEST_ALLOWRAW );
        $tblEdit_value = JRequest::getVar( "tblEdit_value", null, 'post' , 'STRING', JREQUEST_ALLOWRAW );
        if($tblEdit_alias!="" && $tblEdit_value!=""){// Phatch for Field editing form Table View
    		$query->set($db->nameQuote($tblEdit_alias).'='.$db->Quote($tblEdit_value));
        }else{
			foreach ($this->fields as $field){				
                if($field["fld_alias"]!="id"){
	                $field_value = JRequest::getVar( $field["fld_alias"], null, 'post' , 'STRING', JREQUEST_ALLOWRAW );
	                $query->set($db->nameQuote($field["fld_alias"]).'='.$db->Quote($field_value));
                }
            }
        }   
        $db->setQuery( $query );
        if($db->query())
        {
            JFactory::getApplication()->enqueueMessage(JText::_('COM_CCS_OK_REPLACE').": ".$rowID, 'message');
        }else{
            JFactory::getApplication()->enqueueMessage(JText::_('COM_CCS_ERROR_REPLACE').": ".$rowID, 'error');
        }
    }
    
    function delete()
    {
        $cids = JRequest::getVar( 'cid', array(), 'post', 'array' );
        $db = JFactory::getDBO();
        foreach($cids as $cid) {              
            $query = $db->getQuery(true);
            $query->delete(); 
            $query->from($db->nameQuote($this->db_prefix.$this->current_alias));     
            $query->where($db->nameQuote('id').'='.(int)$cid);  
            $db->setQuery($query); 
            if($db->query())
            {
                JFactory::getApplication()->enqueueMessage(JText::_('COM_CCS_OK_DELETE').": ".$cid, 'message');
            }else{
                JFactory::getApplication()->enqueueMessage(JText::_('COM_CCS_ERROR_DELETE').": ".$cid, 'error');
            }
        }
    }
    
    function getMenuItems(){
		// Public 1
		// Registered 2 
		// Manager 6
		// Super User 8
		$user = JFactory::getUser($userid);    
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select($db->nameQuote('id'));
		$query->select($db->nameQuote('ordering'));
		$query->select($db->nameQuote('db_alias'));
		$query->select($db->nameQuote('access'));
		$query->select($db->nameQuote('db_image'));
		$query->select($db->nameQuote('parent_db_alias'));
		$query->from($db->nameQuote($this->db_prefix.$this->CCS_Database_Config));
		$query->where($db->nameQuote('access').'<='.(int)(end($user->groups)));
		$db->setQuery($query);
		$result = $db->loadAssocList();
		return $result;

    }

	function adminListSorting(){

		$cids = JRequest::getVar( 'cid', array(), 'post', 'array' );
		$cid = (int)$cids[0];// <-------------------------------------- Get ROW ID
        $drop_target = JRequest::getVar( 'adminListSorting' );
        $drop_pos = substr($drop_target,0,3);// <---------------------- Get Target Position
        $drop_cid = (int)substr($drop_target,3,strlen($drop_target));// <---------------------- Get Target ROW ID

        if($cid==$drop_cid){
        	return;// <---------------------- RETURN if same row
        }
        $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select($db->nameQuote('id'));
		$query->select($db->nameQuote('ordering'));
		$query->from($db->nameQuote($this->db_prefix.$this->current_alias));
		$query->order($db->getEscaped('ordering'.' '.'ASC'));
		$db->setQuery($query);
		$ROWs = $db->loadAssocList();// <---------------------- Get ROWs

		$newROWs = Array();
		$cacheROW = Array();

		foreach ($ROWs as $i => $ROW) {
			if($ROW["id"]==$cid){
				$cacheROW = $ROW;
			}
		}

		foreach ($ROWs as $i => $ROW) {
			if($ROW["id"]!=$cid){
				if($ROW["id"]==$drop_cid && $drop_pos=="bef"){// After
					$newROWs[] = $cacheROW;
				}
				$newROWs[] = $ROW;
				if($ROW["id"]==$drop_cid && $drop_pos=="aft"){// After
					$newROWs[] = $cacheROW;
				}
			}
		}

		// Loop through rows and update orderings
		foreach ($newROWs as $i => $ROW) {
			$query = $db->getQuery(true);
            $query->update($db->nameQuote($this->db_prefix.$this->current_alias));
            $query->where($db->nameQuote('id').'='.$ROW["id"]);
            $query->set($db->nameQuote('ordering').'='.$i);
            $db->setQuery( $query );
        	$db->query();		
		}

    }

	function getQuickIcons(){

		$user = JFactory::getUser($userid);
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select($db->nameQuote('id'));
		$query->select($db->nameQuote('db_alias'));
		$query->select($db->nameQuote('db_image'));
		$query->from($db->nameQuote($this->db_prefix.$this->CCS_Database_Config));
		$query->where($db->nameQuote('quick_icon').'='.(int)1);
		$query->where($db->nameQuote('access').'<='.(int)(end($user->groups)));
		$db->setQuery($query);
		return $db->loadAssocList();
		
	}
    
}

