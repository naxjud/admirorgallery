<?php

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
jimport('joomla.language.language');
jimport('joomla.filesystem.archive');

// GET ROOT FOLDER
global $mainframe;
$plugin =& JPluginHelper::getPlugin('content', 'AdmirorGallery');
if (isset ($plugin->params)){
     $pluginParams = new JParameter( $plugin->params );
}
else {
     $pluginParams= new JParameter(null);
}
$ag_rootFolder = $pluginParams->get('rootFolder','/images/stories/');
$ag_init_itemURL=$ag_rootFolder;
if (isset($_POST['ag_itemURL'])){
     $ag_init_itemURL=$_POST['ag_itemURL'];
}

// PHP capture for form submition
if(isset($_POST['pressbutton'])){
     $action = $_POST['pressbutton'];
     $webSafe=Array("/"," ",":",".","+");
 
     switch ($action){
	  case "bookmarkAdd" :{
		    require_once (JPATH_BASE.DS.'components/com_admirorgallery/scripts/imgManager-bookmarkAdd.php');
	       break;
	  } 
	  case "bookmarkRemove" :{
		    require_once (JPATH_BASE.DS.'components/com_admirorgallery/scripts/imgManager-bookmarkRemove.php');
	       break;
	  } 
	  case "folderNew" :{
		    require_once (JPATH_BASE.DS.'components/com_admirorgallery/scripts/imgManager-folderNew.php');
	       break;
	  } 
	  case "upload" :{
		    require_once (JPATH_BASE.DS.'components/com_admirorgallery/scripts/imgManager-upload.php');
	       break;
	  } 
	  case "rename" :{
		    require_once (JPATH_BASE.DS.'components/com_admirorgallery/scripts/imgManager-rename.php');
	       break;
	  } 
	  case "remove" :{
		    require_once (JPATH_BASE.DS.'components/com_admirorgallery/scripts/imgManager-remove.php');
	       break;
	  } 
	  case "description" :{
		    require_once (JPATH_BASE.DS.'components/com_admirorgallery/scripts/imgManager-description.php');
	       break;
	  } 
	  case "priority" :{
		    require_once (JPATH_BASE.DS.'components/com_admirorgallery/scripts/imgManager-priority.php');
	       break;
	  } 
     }
}

if (isset($_POST['ag_itemURL'])){
     $ag_init_itemURL=$_POST['ag_itemURL'];
}
     


$doc = &JFactory::getDocument();
$doc->addStyleSheet(JURI::base().'components/com_admirorgallery/css/template.css');
$doc->addStyleSheet(JURI::base().'components/com_admirorgallery/css/toolbar.css');
$doc->addScript(JURI::root().'plugins/content/AdmirorGallery/jquery.js');
$doc->addScript(JURI::base().'components/com_admirorgallery/jquery-treeview/jquery.treeview.pack.js');
$doc->addScript(JURI::base().'components/com_admirorgallery/jquery-treeview/lib/jquery.cookie.js');
$doc->addStyleSheet(JURI::base().'components/com_admirorgallery/jquery-treeview/jquery.treeview.css');

if(file_exists(JPATH_SITE.$ag_init_itemURL)){
     if(is_dir(JPATH_SITE.$ag_init_itemURL)){
	  $ag_init_itemType="folder";
     }else{
	  $ag_init_itemType="file";
     }
     require_once (JPATH_BASE.DS.'components'.DS.'com_admirorgallery'.DS.'scripts'.DS.'imgManager-render-'.$ag_init_itemType.'.php');
}else{
     $ag_error[] = Array ("Folder or image not found.", $ag_init_itemURL);
$ag_preview_content='
<div class="ag_screenSection_title">
     '.$ag_init_itemURL.'
</div>
';
}


// LOAD MESSAGES GROWL LIBRARY
require_once (JPATH_BASE.DS.'components/com_admirorgallery/scripts/growl.php');
$doc->addScriptDeclaration('

var ag_init_itemURL="'.$ag_init_itemURL.'";
var ag_init_itemType="'.$ag_init_itemType.'";

// < TOOLBAR HIGHLITE >
function ag_toolbar_highlite(itemType){

     var toolbarSet = new Array();
	  toolbarSet["all"]=new Array("bookmarkAdd","bookmarkRemove","folderNew","upload-file","rename","remove","description","priority");
	  toolbarSet["folder"]=new Array("bookmarkAdd","bookmarkRemove","folderNew","upload-file","rename","remove","priority");
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

    //Init Treview
    jQuery("#ag_treeView").treeview({
	animated: "fast",
	collapsed: true,
	unique: true,
	persist: "cookie"
    });

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

// FUNCTIONS
function ag_bookmarkParser_render($bookmarkPath){

     $ag_bookmarks_xml =& JFactory::getXMLParser( 'simple' );
     $ag_bookmarks_xml->loadFile( $bookmarkPath );
     if(isset($ag_bookmarks_xml->document->bookmark)){
         foreach($ag_bookmarks_xml->document->bookmark as $key => $value){
              echo '
              <div class="ag_bookmark">
                   <img src="'.JURI::base().'components/com_admirorgallery/jquery-treeview/images/folder.gif" />
                   &nbsp;
                   <a href="'.$ag_bookmarks_xml->document->bookmark[$key]->data().'" class="ag_folderLink" title="'.$ag_bookmarks_xml->document->bookmark[$key]->data().'">
                   '.basename($ag_bookmarks_xml->document->bookmark[$key]->data()).'
                   </a>
              </div>
              '."\n";
         }
     }

}

function ag_treeView_render($ag_folder_path){

    $ag_ext_valid = array ("jpg","jpeg","gif","png");
    $imagesFolder_files = JFolder::files(JPATH_SITE.'/'.$ag_folder_path);
    $imagesFolder_folders = JFolder::folders(JPATH_SITE.'/'.$ag_folder_path);

    echo '<ul>'."\n";

    foreach($imagesFolder_folders as $key => $value){
	echo '<li><img src="'.JURI::base().'components/com_admirorgallery/jquery-treeview/images/folder.gif" />&nbsp;<a href="'.$ag_folder_path.$value.'/" title="'.$ag_folder_path.$value.'/" class="ag_folderLink">'.$value.'</a>';
	ag_treeView_render($ag_folder_path.$value.'/');
	echo '</li>'."\n";
    }

    foreach($imagesFolder_files as $key => $value){
	  $ag_file_ext = strtolower(JFile::getExt(JPATH_SITE.'/'.$ag_folder_path.$value));
	  $ag_ext_check = array_search($ag_file_ext,$ag_ext_valid);
          $ag_file_descName = substr($value, 0, -(strlen($ag_file_ext))).'desc';
          if(is_numeric($ag_ext_check)){
	    if(file_exists(JPATH_SITE.'/'.$ag_folder_path.$ag_file_descName)){
	      echo '<li><img src="'.JURI::base().'components/com_admirorgallery/jquery-treeview/images/file.gif" /><a href="'.$ag_folder_path.$value.'" class="ag_fileLink">'.$value.'</a></li>'."\n";
	    }else{
	      echo '<li><a href="'.$ag_folder_path.$value.'" class="ag_fileLink">'.$value.'</a></li>'."\n";
	    }
	  }
     }

    echo '</ul>'."\n";

}//function ag_renderFiles($imagesFolder_phpPath,$imagesFolder_htmlPath)


// FORMAT FORM
echo '
<form action="index.php" method="post" name="adminForm" enctype="multipart/form-data" id="adminForm">
	<input type="hidden" name="option" value="com_admirorgallery" />
	<input type="hidden" name="task" value="image-manager" />
	<input type="hidden" name="ag_itemURL" value="'.$ag_init_itemURL.'" id="ag_itemURL" />
	<input type="hidden" name="pressbutton" value="" id="pressbutton" />
'."\n";

// FORMAT SCREEN
echo '
<table border="0" cellspacing="0" cellpadding="0">
     <tbody>
	  <tr>
	       <td id="ag_bookmarksView">
		    <div class="ag_screenSection_title">'.JText::_( "Galleries").'</div>
		    '."\n";

ag_bookmarkParser_render(JPATH_SITE.'/administrator/components/com_admirorgallery/assets/bookmarks.xml');

echo '
	       </td>
	       <td id="ag_treeView">
		    <div class="ag_screenSection_title">'.JText::_( "Images Root Folder").'</div>

			 <ul>
			      <li>
				   <img src="'.JURI::base().'components/com_admirorgallery/jquery-treeview/images/folder.gif" />
				   &nbsp;
				   <a href="'.$ag_rootFolder.'" class="ag_folderLink" title="'.$ag_rootFolder.'">
				   '.$ag_rootFolder.'
				   </a>
			 '."\n";

ag_treeView_render($ag_rootFolder);

echo '
			      </li>
			 </ul>

	       </td>
	       <td id="ag_preview">
		    <div class="ag_screenSection_title">'.$ag_preview_content.'</div>
	       </td>
	  </tr>
     </tbody>
</table>

</form>
'."\n";

?>
