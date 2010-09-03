<?php

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
jimport('joomla.language.language');
jimport('joomla.filesystem.archive');

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
	  case "removeDesc" :{
		    require_once (JPATH_BASE.DS.'components/com_admirorgallery/scripts/imgManager-removeDesc.php');
	       break;
	  } 
     }
}

if (isset ($_POST['ag_itemURL'])){
    $ag_init_itemURL=$_POST['ag_itemURL'];
    if(is_dir(JPATH_SITE.'/'.$_POST['ag_itemURL'])){
         $ag_init_itemType="folder";
    }else{
         $ag_init_itemType="file";
    }
}else
{
    $ag_init_itemURL='';
    $ag_init_itemType='';
}

$ag_lang_available = JLanguage::getKnownLanguages(JPATH_SITE);
$ag_lang_available_string="";
foreach($ag_lang_available as $ag_lang_availableKey => $ag_lang_availableValue){
     $ag_lang_available_string.=$ag_lang_availableValue["tag"].'[split]'.$ag_lang_availableValue["name"].'[split]';
}
$ag_lang_available_string=substr($ag_lang_available_string,0,strlen($ag_lang_available_string)-7);

$doc = &JFactory::getDocument();
$doc->addStyleSheet(JURI::base().'components/com_admirorgallery/css/template.css');
$doc->addScript(JURI::base().'components/com_admirorgallery/scripts/jquery.js');
$doc->addScript(JURI::base().'components/com_admirorgallery/jquery-treeview/jquery.treeview.pack.js');
$doc->addScript(JURI::base().'components/com_admirorgallery/jquery-treeview/lib/jquery.cookie.js');
$doc->addStyleSheet(JURI::base().'components/com_admirorgallery/jquery-treeview/jquery.treeview.css');
$doc->addScriptDeclaration('

var ag_init_itemURL="'.$ag_init_itemURL.'";
var ag_init_itemType="'.$ag_init_itemType.'";

// < TOOLBAR HIGHLITE >
function ag_toolbar_highlite(itemType){

     var toolbarSet = new Array();
	  toolbarSet["all"]=new Array("bookmarkAdd","bookmarkRemove","folderNew","upload","rename","remove","description","removeDesc");
	  toolbarSet["folder"]=new Array("bookmarkAdd","bookmarkRemove","folderNew","upload","rename","remove");
	  toolbarSet["file"]=new Array("rename","description","removeDesc");

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
     ag_item_highlite(itemURL);
     ag_toolbar_highlite("folder");
     jQuery.ajax({
	  type: "POST",
	  url: "components/com_admirorgallery/scripts/imgManager-render-folder.php",
	  data: "ag_itemURL="+itemURL+"&ag_phpRoot='.urlencode(JPATH_SITE).'&ag_htmlRoot='.urlencode(dirname(JURI::base())).'",
	  async: false,
	  success: function(msg){

	       jQuery("#ag_preview").html("<div class=\'ag_screenSection_title\'></div><fieldset></fieldset><div id=\'ag_itemsWrapper\'></div>");

	       var msgArray=msg.split("[ArraySplit]");

	       jQuery("#ag_preview .ag_screenSection_title").html(msgArray[0]);

	       var msgArrayFolders=msgArray[1].split("[split]");
	       var msgArrayLength=msgArrayFolders.length-1;
	       for(i=0;i<msgArrayLength;i+=2){
		    jQuery("#ag_preview #ag_itemsWrapper").append("<div class=\'ag_preview_itemWrap\'><a href=\'"+msgArrayFolders[i]+"\' class=\'ag_preview_itemLink ag_folderLink\'><div align=\'center\' class=\'ag_preview_imgWrap\'><img src=\''.JURI::base().'components/com_media/images/folder.png\' /></div></a><div class=\'ag_preview_controlsWrap\'><input type=\'checkbox\' value=\'"+msgArrayFolders[i]+"\' name=\'ag_preview_CBOX[]\'>"+msgArrayFolders[i+1]+"</div></div>");
	       }
	       var msgArrayFiles=msgArray[2].split("[split]");
	       var msgArrayLength=msgArrayFiles.length-1;
	       for(i=0;i<msgArrayLength;i+=3){
		    if(msgArrayFiles[i+2]=="hasDesc"){
			 jQuery("#ag_preview #ag_itemsWrapper").append("<div class=\'ag_preview_itemWrap\'><a href=\'"+msgArrayFiles[i]+"\' class=\'ag_preview_itemLink ag_fileLink\'><div align=\'center\' class=\'ag_preview_imgWrap\'><span class=\'ag_imgTag_wrap\'><img src=\''.JURI::base().'components/com_admirorgallery/images/imgTag_desc.gif\' class=\'ag_imgTag_img\' /></span><img src=\''.dirname(JURI::base()).'"+msgArrayFiles[i]+"\' class=\'ag_imgThumb\' /></div></a><div class=\'ag_preview_controlsWrap\'><input type=\'checkbox\' value=\'"+msgArrayFiles[i]+"\' name=\'ag_preview_CBOX[]\'>"+msgArrayFiles[i+1]+"</div></div>");       
		    }else{
			 jQuery("#ag_preview #ag_itemsWrapper").append("<div class=\'ag_preview_itemWrap\'><a href=\'"+msgArrayFiles[i]+"\' class=\'ag_preview_itemLink ag_fileLink\'><div align=\'center\' class=\'ag_preview_imgWrap\'><img src=\''.dirname(JURI::base()).'"+msgArrayFiles[i]+"\' class=\'ag_imgThumb\' /></div></a><div class=\'ag_preview_controlsWrap\'><input type=\'checkbox\' value=\'"+msgArrayFiles[i]+"\' name=\'ag_preview_CBOX[]\'>"+msgArrayFiles[i+1]+"</div></div>");       
		    }
	       }

	       jQuery("#ag_preview fieldset").append("<table cellspacing=\'0\' cellpadding=\'3\' border=\'0\'><tbody><tr><td id=\'fieldset1_row1_td1\'></td><td id=\'fieldset1_row1_td2\'></td></tr><tr><td id=\'fieldset1_row2_td1\'></td><td id=\'fieldset1_row2_td2\'></td></tr></tbody></table>");
	       // SET CHANGE NAME INPUT
	       jQuery("#ag_preview fieldset #fieldset1_row1_td1").append("'.JText::_( 'Set / Change Name to:' ).'&nbsp;");
	       jQuery("#ag_preview fieldset #fieldset1_row1_td2").append("<input type=\'text\' name=\'setChangeNameTo\' id=\'setChangeNameTo\' size=\'50\' /><br />");
	       // UPLOAD INPUT
	       jQuery("#ag_preview fieldset #fieldset1_row2_td1").append("'.JText::_( 'Upload File:' ).'&nbsp;");
	       jQuery("#ag_preview fieldset #fieldset1_row2_td2").append("<input type=\'file\' name=\'file_upload\' size=\'50\' title=\''.JText::_( 'Only jpg, jpeg, gif, png and zip are valid extensions.').'\' />");

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

	  }
     });
}

function ag_file_selected(itemURL){
     ag_item_highlite(itemURL);
     ag_toolbar_highlite("file");
     jQuery.ajax({
	  type: "POST",
	  url: "components/com_admirorgallery/scripts/imgManager-render-file.php",
	  data: "ag_itemURL="+itemURL+"&ag_phpRoot='.urlencode(JPATH_SITE).'&ag_htmlRoot='.urlencode(dirname(JURI::base())).'&ag_lang_available='.urlencode($ag_lang_available_string).'",
	  async: false,
	  success: function(msg){

	       jQuery("#ag_preview").html("<div class=\'ag_screenSection_title\'></div><fieldset></fieldset><div class=\'filePreview\'></div><div id=\'ag_imgDesc_info\'></div><div id=\'ag_descData\'></div>");

	       var msgArray=msg.split("[ArraySplit]");

	       jQuery("#ag_preview .ag_screenSection_title").html("<a href=\'"+dirname(msgArray[0])+"\' title=\'"+dirname(msgArray[0])+"\' onClick=\'ag_folder_selected(jQuery(this).attr(\"href\")); return false;\'>"+dirname(msgArray[0])+"</a>"+basename(msgArray[0]));
	       if(msgArray[5]=="hasDesc"){
		    jQuery("#ag_preview .filePreview").html("<span class=\'ag_imgTag_wrap\'><img src=\''.JURI::base().'components/com_admirorgallery/images/imgTag_desc.gif\' class=\'ag_imgTag_img\' /></span><img src=\''.dirname(JURI::base()).'"+msgArray[0]+"\' class=\'ag_imgThumb\' />");
	       }else{
		    jQuery("#ag_preview .filePreview").html("<img src=\''.dirname(JURI::base()).'"+msgArray[0]+"\' class=\'ag_imgThumb\' />");
	       }

	  jQuery("#ag_imgDesc_info").append("<div class=\"t\"><div class=\"t\"><div class=\"t\"></div></div></div>");	       
	  jQuery("#ag_imgDesc_info").append("<div class=\"m m_imgInfo\"></div>");
	       jQuery("#ag_imgDesc_info .m_imgInfo").append("'.JText::_( "Width").': <b>"+msgArray[1]+"</b>&nbsp;|&nbsp;");
	       jQuery("#ag_imgDesc_info .m_imgInfo").append("'.JText::_( "Height").': <b>"+msgArray[2]+"</b>&nbsp;|&nbsp;");
	       jQuery("#ag_imgDesc_info .m_imgInfo").append("'.JText::_( "Type").': <b>"+msgArray[3]+"</b>&nbsp;|&nbsp;");
	       jQuery("#ag_imgDesc_info .m_imgInfo").append("'.JText::_( "Size").': <b>"+msgArray[4]+"</b>");
	  jQuery("#ag_imgDesc_info").append("<div class=\"b\"><div class=\"b\"><div class=\"b\"></div></div></div><p></p>");
 
	       var msgArray=msgArray[6].split("[split]");
	       var msgArrayLength=msgArray.length-1;
	       for(i=0;i<msgArrayLength;i+=3){
		    jQuery("#ag_descData").append("<div class=\"t\"><div class=\"t\"><div class=\"t\"></div></div></div>");
		    jQuery("#ag_descData").append("<div class=\"m mID"+i+"\"></div>");
		    jQuery("#ag_descData .mID"+i+"").append("<span class=\"ag_nameLabel\">"+msgArray[i]+"</span>");
		    jQuery("#ag_descData .mID"+i+"").append("<span class=\"ag_tagLabel\">"+msgArray[i+1]+"</span>");
		    jQuery("#ag_descData .mID"+i+"").append("<textarea class=\"ag_inputText\" name=\'ag_desc_content[]\'>"+msgArray[i+2]+"</textarea><input type=\'hidden\' name=\'ag_desc_tags[]\' value=\'"+msgArray[i+1]+"\' />");
		    jQuery("#ag_descData").append("<div class=\"b\"><div class=\"b\"><div class=\"b\"></div></div></div><p></p>");
	       }

	       jQuery("#ag_preview fieldset").append("<table cellspacing=\'0\' cellpadding=\'3\' border=\'0\'><tbody><tr><td id=\'fieldset1_row1_td1\'></td><td id=\'fieldset1_row1_td2\'></td></tr><tr><td id=\'fieldset1_row2_td1\'></td><td id=\'fieldset1_row2_td2\'></td></tr></tbody></table>");
	       // SET CHANGE NAME INPUT
	       jQuery("#ag_preview fieldset #fieldset1_row1_td1").append("'.JText::_( 'Set / Change Name to:' ).'&nbsp;");
	       jQuery("#ag_preview fieldset #fieldset1_row1_td2").append("<input type=\'text\' name=\'setChangeNameTo\' id=\'setChangeNameTo\' size=\'50\' /><br />");
	       

	  }
     });
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

     if(ag_init_itemURL != ""){
	  if(ag_init_itemType == "folder"){	  
	       ag_folder_selected(ag_init_itemURL);
	  }else{
	       ag_file_selected(ag_init_itemURL);
	  }
     }

});//jQuery(function()

jQuery.noConflict();

');


// LOAD MESSAGES GROWL LIBRARY
require_once (JPATH_BASE.DS.'components/com_admirorgallery/scripts/growl.php');


// GET ROOT FOLDER
global $mainframe;
$plugin =& JPluginHelper::getPlugin('content', 'AdmirorGallery');
$pluginParams = new JParameter( $plugin->params );
$ag_rootFolder = $pluginParams->get('rootFolder','images/stories/');


// FUNCTIONS
function ag_bookmarkParser_render($bookmarkPath){

     $ag_bookmarks_xml =& JFactory::getXMLParser( 'simple' );
     $ag_bookmarks_xml->loadFile( $bookmarkPath );

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
<form action="index.php" method="post" name="adminForm" enctype="multipart/form-data">
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
		    <div class="ag_screenSection_title">'.JText::_( "Root Folder").'</div>

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
		    <div class="ag_screenSection_title">'.JText::_( "Selected Item Details").'</div>
	       </td>
	  </tr>
     </tbody>
</table>

</form>
'."\n";

?>