<?php

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

// Preloading joomla tools
jimport( 'joomla.installer.helper' );
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.archive');
jimport('joomla.html.pagination');
jimport('joomla.filesystem.folder');
JHTML::_('behavior.tooltip');

// PHP capture for form submition
if(isset($_POST['pressbutton'])){
	if($_POST['pressbutton']=="install" || $_POST['pressbutton']=="remove"){// JPagination patch		
		require_once (JPATH_BASE.DS.'components/com_admirorgallery/scripts/resourceManager-'.$_POST['pressbutton'].'.php');// N U
	}
}

// Loading globals
global $mainframe, $option;



// Loading JPagination vars
$limitstart	= $mainframe->getUserStateFromRequest( $option.'.limitstart', 'limitstart', 0, 'int' );
$limit		= $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );

// Loading CSSs and JavaScripts
$doc = &JFactory::getDocument();
$doc->addStyleSheet(JURI::base().'components/com_admirorgallery/css/template.css');
$doc->addScript(JURI::base().'components/com_admirorgallery/scripts/jquery.js');
$doc->addScript(JURI::base().'components/com_admirorgallery/scripts/jquery.js');
require_once (JPATH_BASE.DS.'components/com_admirorgallery/scripts/growl.php');

$doc->addScriptDeclaration('

function submitbutton(pressbutton) {
		jQuery("#adminForm #pressbutton").val(pressbutton);
		document.forms["adminForm"].submit();
}

jQuery(function(){

	jQuery(".ag_title_link").click(function(e) {
		e.preventDefault();
		if(jQuery(this).closest("tr").find(\'input:checkbox\').attr("checked") == true){
			jQuery(this).closest("tr").find(\'input:checkbox\').attr("checked", false);
		}else{
			jQuery(this).closest("tr").find(\'input:checkbox\').attr("checked", true);
		}        		
	});

	jQuery("#checkAll").click(function(e) {

		var numOfRows = jQuery(".adminlist tbody tr").length;

		if(jQuery(this).attr("checked") == true){
			for(i='.$limitstart.';i<('.$limitstart.'+numOfRows);i++){
				jQuery("#cb"+i).attr("checked", true);
			}
		}else{
			for(i='.$limitstart.';i<('.$limitstart.'+numOfRows);i++){
				jQuery("#cb"+i).attr("checked", false);
			}
		}     

	});

	jQuery(".ag_grid_uninstalled").click(function(e) {
		e.preventDefault();
		var numOfRows = jQuery(".adminlist tbody tr").length;
		for(i='.$limitstart.';i<('.$limitstart.'+numOfRows);i++){
			jQuery("#cb"+i).attr("checked", false);
		}
		jQuery(this).closest("tr").find(\'input:checkbox\').attr("checked", true);
		submitbutton("remove");
	});

});//jQuery(function()

jQuery.noConflict();

');

// Read folder with gallery templates
$ag_resourceManager_installed = JFolder::folders(JPATH_SITE.'/plugins/content/AdmirorGallery/'.$ag_resourceManager_screen);// N U
sort($ag_resourceManager_installed);

// Rendering the form and table grid
echo '<script type="text/javascript">jQuery("#ag_screenWrapper").remove();</script>
<div id="ag_screenWrapper">
<form action="index.php?option=com_admirorgallery&task='.$ag_resourceManager_screen.'" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
'.JText::_('Select ZIP package to install').': <input type="file" name="file_upload" size="50" />
<br /><br />
';

echo '
<table class="adminlist" cellspacing="1">
<thead>
	<tr>
	      <th width="20px" align="center" nowrap="nowrap">#</th>
	      <th width="20px" align="center"><input type="checkbox" value="" id="checkAll" /></th>
	      <th width="200" class="title" align="left" nowrap="nowrap">'.JText::_( "Title").'</th>
	      <th width="20px" class="title" align="left" nowrap="nowrap">'.JText::_( "ID").'</th>
	      <th align="left" nowrap="nowrap">'.JText::_( "Description").'</th>
	      <th width="20px" align="center" nowrap="nowrap">'.JText::_( "Version").'</th>
	      <th width="20px" align="center" nowrap="nowrap">'.JText::_( "Date").'</th>
	      <th width="100px" align="left" nowrap="nowrap">'.JText::_( "Author").'</th>
	      <th width="20px" align="center" nowrap="nowrap">'.JText::_( "Remove").'</th>
	</tr>
</thead>
<tbody>
';

$total = count($ag_resourceManager_installed);
$pageNav = new JPagination( $total, $limitstart, $limit );
if($limit=="all"){$limit=$total;}

if(!empty($ag_resourceManager_installed)){
foreach ($ag_resourceManager_installed as $ag_resourceManager_Key => $ag_resourceManager_Value) {
	if($ag_resourceManager_Key >= $limitstart && $ag_resourceManager_Key < ($limitstart+$limit)){

		// TEMPLATE DETAILS PARSING
		$ag_resourceManager_id = $ag_resourceManager_Value;
		$ag_resourceManager_name = $ag_resourceManager_id;
		$ag_resourceManager_creationDate = JText::_( "Undated");
		$ag_resourceManager_author = JText::_( "Unknown author");
		$ag_resourceManager_version = JText::_( "Unknown version");
		$ag_resourceManager_description = JText::_( "No descrition");
		if(JFIle::exists(JPATH_SITE.'/plugins/content/AdmirorGallery/'.$ag_resourceManager_screen.'/'.$ag_resourceManager_id.'/details.xml')){// N U
			$ag_resourceManager_xml =& JFactory::getXMLParser( 'simple' );
			$ag_resourceManager_xml->loadFile( JPATH_SITE.'/plugins/content/AdmirorGallery/'.$ag_resourceManager_screen.'/'.$ag_resourceManager_id.'/details.xml' );// N U
			$ag_resourceManager_name = $ag_resourceManager_xml->document->name[0]->data();
			$ag_resourceManager_creationDate = $ag_resourceManager_xml->document->creationDate[0]->data();
			$ag_resourceManager_author = $ag_resourceManager_xml->document->author[0]->data();
			$ag_resourceManager_version = $ag_resourceManager_xml->document->version[0]->data();
			$ag_resourceManager_description = $ag_resourceManager_xml->document->description[0]->data();			
		}

		echo '     
		<tr>
		<td align="right">
		'.($ag_resourceManager_Key+1).'.
		</td> 
		<td align="center">
		';

		//if ($row->checked_out && $row->checked_out != $user->id) {
		//echo '&nbsp;';
		//} else {
		echo '
		<input type="checkbox" id="cb'.$ag_resourceManager_Key.'" name="cid[]" value="'.$ag_resourceManager_id.'" />';
		//}

		echo '
		</td>
		<td style="white-space:nowrap;">

		<span class="editlinktip hasTip" title="'.$ag_resourceManager_name.'::<img border=&quot;1&quot; src=&quot;'.JURI::root().'plugins/content/AdmirorGallery/templates/'.$ag_resourceManager_id.'/preview.jpg'.'&quot; name=&quot;imagelib&quot; alt=&quot;&quot; width=&quot;206&quot; height=&quot;145&quot; />">
		<a href="#" class="ag_title_link">
		'.$ag_resourceManager_name.'
		</a>
		</span>
	
		</td>     
		<td style="white-space:nowrap;">
			'.$ag_resourceManager_id.'
		</td>  
		<td>
			'.$ag_resourceManager_description.'
		</td> 
		<td align="center" style="white-space:nowrap;">
			'.$ag_resourceManager_version.'
		</td>    
		<td style="white-space:nowrap;">
			'.$ag_resourceManager_creationDate.'
		</td>  
		<td>
			'.$ag_resourceManager_author.'
		</td>  
		<td align="center">
			<a href="#" class="ag_grid_uninstalled">&nbsp;</a>
		</td>
		</tr>
		';
	}
}//foreach ($ag_resourceManager_installed as $ag_resourceManager_Key => $ag_resourceManager_Value)
}//if(!empty($ag_resourceManager_installed))

       
echo '
<tfoot>
<tr>
    <td align="center" colspan="9">
	    '.$pageNav->getListFooter().'
    </td>
</tr>
</tfoot>
</table>
';    


echo '   
	<input type="hidden" name="task" value="'.$ag_resourceManager_screen.'" />      
	<input type="hidden" name="option" value="com_admirorgallery" />
	<input type="hidden" name="pressbutton" value="" id="pressbutton" />  
</form>
</div>
';



?>
