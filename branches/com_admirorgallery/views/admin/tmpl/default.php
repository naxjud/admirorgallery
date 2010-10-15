<?php
 
// No direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
jimport('joomla.language.language');
jimport('joomla.filesystem.archive');

// ************************************************* ADMIN TOOLBAR
//
// Require the base controller
require_once (JPATH_COMPONENT.DS.'controller.php');

// Require specific controller if requested
if($controller = JRequest::getVar('controller')) {
      require_once (JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php');
}

// Component Helper
jimport('joomla.application.component.helper');


// Load the toolbar helper
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'toolbar.php' );

//
// *** ADMIN TOOLBAR


// GET ROOT FOLDER
global $mainframe;
$plugin =& JPluginHelper::getPlugin('content', 'AdmirorGallery');
if (isset ($plugin->params))
{$pluginParams = new JParameter( $plugin->params );}
else {$pluginParams= new JParameter(null);
}
$ag_rootFolder = $pluginParams->get('rootFolder','/images/stories/').$this->galleryName.'/';
$ag_init_itemURL=$ag_rootFolder;
if (isset($_POST['ag_itemURL'])){
     $ag_init_itemURL=$_POST['ag_itemURL'];
}

// PHP capture for form submition
if(isset($_POST['pressbutton'])){

    $action = $_POST['pressbutton'];
    $webSafe=Array("/"," ",":",".","+");

    switch ($action){
	  case "folderNew" :{
		    require_once (JPATH_ROOT.DS.'administrator'.DS.'components/com_admirorgallery'.DS.'scripts'.DS.'imgManager-folderNew.php');
	       break;
	  } 
	  case "upload" :{
		    require_once (JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_admirorgallery'.DS.'scripts'.DS.'imgManager-upload.php');
	       break;
	  } 
	  case "rename" :{
		    require_once (JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_admirorgallery'.DS.'scripts'.DS.'imgManager-rename.php');
	       break;
	  } 
	  case "remove" :{
		    require_once (JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_admirorgallery'.DS.'scripts'.DS.'imgManager-remove.php');
	       break;
	  } 
	  case "description" :{
		    require_once (JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_admirorgallery'.DS.'scripts'.DS.'imgManager-description.php');
	       break;
	  } 
	  case "priority" :{
		    require_once (JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_admirorgallery'.DS.'scripts'.DS.'imgManager-priority.php');
	       break;
	  } 
    }

}

if (isset($_POST['ag_itemURL'])){
     $ag_init_itemURL=$_POST['ag_itemURL'];
}

if(file_exists(JPATH_SITE.$ag_init_itemURL)){
     if(is_dir(JPATH_SITE.$ag_init_itemURL)){
	  $ag_init_itemType="folder";
     }else{
	  $ag_init_itemType="file";
     }
     require_once (JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_admirorgallery'.DS.'scripts'.DS.'imgManager-render-'.$ag_init_itemType.'.php');
}else{
     $ag_error[] = Array ("Folder or image not found.", $ag_init_itemURL);
}

$doc = &JFactory::getDocument();

$doc->addScript (JURI::root().'includes/js/joomla.javascript.js');
$doc->addScript(JURI::root().'plugins/content/AdmirorGallery/jquery.js');
// LOAD MESSAGES GROWL LIBRARY
require_once (JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_admirorgallery'.DS.'scripts'.DS.'growl.php');

$doc->addStyleSheet(JURI::root().'administrator/components/com_admirorgallery/css/template.css');
$doc->addStyleSheet(JURI::root().'components/com_admirorgallery/css/toolbar.css');

$doc->addScriptDeclaration('

var ag_init_itemURL="'.$ag_init_itemURL.'";
var ag_init_itemType="'.$ag_init_itemType.'";

// < TOOLBAR HIGHLITE >
function ag_toolbar_highlite(itemType){

     var toolbarSet = new Array();
	  toolbarSet["all"]=new Array("folderNew","upload-file","rename","remove","description","priority");
	  toolbarSet["folder"]=new Array("folderNew","upload-file","rename","remove","priority");
	  toolbarSet["file"]=new Array("rename","description");

     // TURN OFF ALL
     for ( var i=0; i<toolbarSet["all"].length; i++ )
     {
	  jQuery(".icon-32-"+toolbarSet["all"][i]).attr("class","icon-32-"+toolbarSet["all"][i]+" ag_toolbar_off");
     }
     // TURN ON NEEDED
     if(itemType){
	  for ( var i=0; i<toolbarSet[itemType].length; i++ )
	  {
	       jQuery(".icon-32-"+toolbarSet[itemType][i]).attr("class","icon-32-"+toolbarSet[itemType][i]);
	  }
     }

}
// < / TOOLBAR HIGHLITE >

function basename(path) {
     return path.replace(/'.chr(92).chr(92).'/g,"/").replace( /.*\//, "" );
}

function dirname(path) {
     return path.replace(/'.chr(92).chr(92).'/g,"/").replace(/\/[^\/]*$/, "")+"/";
}

function submitbutton(pressbutton) {
		jQuery("#pressbutton").val(pressbutton);
		document.forms["adminForm"].submit();
}

function ag_item_highlite(itemURL){

     jQuery("#ag_itemURL").val(itemURL);

     // CLEAR ALL HIGHLIGHT FOR SELECTED ITEM
     jQuery(".ag_selectedItem").removeClass("ag_selectedItem");

     // SET NEW HIGHLIGHT FOR ACTIVE ITEM
     jQuery("#ag_bookmarksView a[href$="+itemURL+"]").parent().addClass("ag_selectedItem");
     jQuery("#ag_treeView a[href$="+itemURL+"]").parent().addClass("ag_selectedItem");
   
}

function ag_folder_selected(itemURL){
    jQuery("#ag_itemURL").val(itemURL);
    jQuery("#pressbutton").val("renderFolder");
    jQuery("#adminForm").submit();
}

function ag_file_selected(itemURL){
    jQuery("#ag_itemURL").val(itemURL);
    jQuery("#pressbutton").val("renderFile");
    jQuery("#adminForm").submit();
}

jQuery(function(){
');
// RENDER ALL NOTICES
if(!empty($ag_notice)){
     foreach($ag_notice as $key => $value){
         if(isset ($value[1])){
	  $doc->addScriptDeclaration('ag_showMessage("'.JText::_( $value[0]).'<br />'.$value[1].'","notice");');
         }else{
          $doc->addScriptDeclaration('ag_showMessage("'.JText::_( $value[0]).'","notice");');
         }
     }
}

// RENDER ALL ERRORS
if(!empty($ag_error)){
     foreach($ag_error as $key => $value){
         if(isset ($value[1])){
	  $doc->addScriptDeclaration('ag_showMessage("'.JText::_( $value[0]).'<br />'.$value[1].'","error");');
         }else{
          $doc->addScriptDeclaration('ag_showMessage("'.JText::_( $value[0]).'","error");');
         }
     }
}

$doc->addScriptDeclaration('

     ag_toolbar_highlite();

     // Binding event to folder links
    jQuery(".ag_folderLink").click(function(e) {
	e.preventDefault();        
	ag_folder_selected(jQuery(this).attr("href"));
    });

    // Binding event to file links
    jQuery(".ag_fileLink").click(function(e) {
	e.preventDefault();
	ag_file_selected(jQuery(this).attr("href"));
    });

     if(ag_init_itemURL != "" && ag_init_itemType != ""){  
	  ag_item_highlite(ag_init_itemURL);
	  ag_toolbar_highlite(ag_init_itemType);
     }

      // Binding event to folder links
      jQuery("#ag_preview .ag_folderLink").click(function(e) {
	  e.preventDefault();
	  ag_folder_selected(jQuery(this).attr("href"));
      });

      // Binding event to file links
      jQuery("#ag_preview .ag_fileLink").click(function(e) {
	  e.preventDefault();
	  ag_file_selected(jQuery(this).attr("href"));
      });

});//jQuery(function()

jQuery.noConflict();

');
?>


<form action="<?php echo JURI::current();?>" method="POST" name="adminForm" enctype="multipart/form-data" id="adminForm">
	<input type="hidden" name="task" value="" id="task" />
	<input type="hidden" name="ag_itemURL" value="<?php $ag_init_itemURL;?>" id="ag_itemURL" />
	<input type="hidden" name="pressbutton" value="" id="pressbutton" />

<div id="ag_preview">
<?php
echo AdmirorgalleryHelperToolbar::getToolbar();
echo $ag_preview_content;
?>
</div>

</form>
<br style="clear:both;" />
<br />