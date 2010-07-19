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

// Creating field for messages
echo '<div id="ag_messageWrap"></div>';

// PHP capture for form submition
if(isset($_POST['pressbutton'])){
	if($_POST['pressbutton']=="install" || $_POST['pressbutton']=="remove"){// JPagination patch
		require_once (JPATH_BASE.DS.'components/com_admirorgallery/scripts/themes-'.$_POST['pressbutton'].'.php');
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
$doc->addScript(JURI::base().'components/com_admirorgallery/scripts/jquery.timers.js');

$doc->addScriptDeclaration('

function ag_showMessage(message) {
     jQuery("#ag_messageWrap").prepend("<div id=\'ag_message\'>"+message+"</div>");
     jQuery("#ag_message").fadeIn("slow");
     jQuery(this).oneTime(5000, function() {
          jQuery("#ag_message").fadeOut("slow",function(){jQuery("#ag_message").remove();});					
	});
}

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
$ag_galleryThemes_installed = JFolder::folders(JPATH_SITE.'/plugins/content/AdmirorGallery/templates');
sort($ag_galleryThemes_installed);

// Rendering the form and table grid
echo '<script type="text/javascript">jQuery("#ag_screenWrapper").remove();</script>
<div id="ag_screenWrapper">
<form action="index.php?option=com_admirorgallery&task=themes" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
'.JText::_('Select template to install:').' <input type="file" name="file_upload" size="50" />
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

$total = count($ag_galleryThemes_installed);
$pageNav = new JPagination( $total, $limitstart, $limit );
if($limit=="all"){$limit=$total;}

if(!empty($ag_galleryThemes_installed)){
foreach ($ag_galleryThemes_installed as $galleryThemesKey => $galleryThemesValue) {
	if($galleryThemesKey >= $limitstart && $galleryThemesKey < ($limitstart+$limit)){

		// TEMPLATE DETAILS PARSING
		$ag_template_id = $galleryThemesValue;
		$ag_template_name = $ag_template_id;
		$ag_template_creationDate = JText::_( "Undated");
		$ag_template_author = JText::_( "Unknown author");
		$ag_template_version = JText::_( "Unknown version");
		$ag_template_description = JText::_( "No descrition");
		if(JFIle::exists(JPATH_SITE.'/plugins/content/AdmirorGallery/templates/'.$ag_template_id.'/templateDetails.xml')){
			$ag_template_xml =& JFactory::getXMLParser( 'simple' );
			$ag_template_xml->loadFile( JPATH_SITE.'/plugins/content/AdmirorGallery/templates/'.$ag_template_id.'/templateDetails.xml' );
			$ag_template_name = $ag_template_xml->document->name[0]->data();
			$ag_template_creationDate = $ag_template_xml->document->creationDate[0]->data();
			$ag_template_author = $ag_template_xml->document->author[0]->data();
			$ag_template_version = $ag_template_xml->document->version[0]->data();
			$ag_template_description = $ag_template_xml->document->description[0]->data();			
		}

		echo '     
		<tr>
		<td align="right">
		'.($galleryThemesKey+1).'.
		</td> 
		<td align="center">
		';

		if ($row->checked_out && $row->checked_out != $user->id) {
		echo '&nbsp;';
		} else {
		echo '
		<input type="checkbox" id="cb'.$galleryThemesKey.'" name="cid[]" value="'.$ag_template_id.'" />';
		}

		echo '
		</td>
		<td>

		<span class="editlinktip hasTip" title="'.$ag_template_id.'::<img border=&quot;1&quot; src=&quot;'.JURI::root().'plugins/content/AdmirorGallery/templates/'.$ag_template_id.'/preview.jpg'.'&quot; name=&quot;imagelib&quot; alt=&quot;&quot; width=&quot;206&quot; height=&quot;145&quot; />">
		<a href="#" class="ag_title_link">
		'.$ag_template_name.'
		</a>
		</span>
	
		</td>     
		<td>
			'.$ag_template_id.'
		</td>  
		<td>
			'.$ag_template_description.'
		</td> 
		<td align="center">
			'.$ag_template_version.'
		</td>    
		<td>
			'.$ag_template_creationDate.'
		</td>  
		<td>
			'.$ag_template_author.'
		</td>  
		<td align="center">
			<a href="#" class="ag_grid_uninstalled">&nbsp;</a>
		</td>
		</tr>
		';
	}
}//foreach ($ag_galleryThemes_installed as $galleryThemesKey => $galleryThemesValue)
}//if(!empty($ag_galleryThemes_installed))

       
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
	<input type="hidden" name="task" value="themes" />      
	<input type="hidden" name="option" value="com_admirorgallery" />
	<input type="hidden" name="pressbutton" value="" id="pressbutton" />  
</form>
</div>
';



?>
