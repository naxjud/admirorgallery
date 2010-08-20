<?php

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.filesystem.file');
jimport('joomla.language.language');

$doc = &JFactory::getDocument();
$doc->addStyleSheet(JURI::base().'components/com_admirorgallery/css/template.css');
$doc->addScript(JURI::base().'components/com_admirorgallery/scripts/jquery.js');
$doc->addScript(JURI::base().'components/com_admirorgallery/jquery-treeview/jquery.treeview.pack.js');
$doc->addScript(JURI::base().'components/com_admirorgallery/jquery-treeview/lib/jquery.cookie.js');
$doc->addStyleSheet(JURI::base().'components/com_admirorgallery/jquery-treeview/jquery.treeview.css');

// LOAD MESSAGES GROWL LIBRARY
require_once (JPATH_BASE.DS.'components/com_admirorgallery/scripts/growl.php');
$ag_script_path ='components/com_admirorgallery/scripts/descriptions-handler.php';

$ag_lang_available = JLanguage::getKnownLanguages(JPATH_SITE);
//
$doc->addScriptDeclaration('
//HELPER FUNCTION. RETURNS FILENAME FROM PATH
function ag_extractFilename(fileName){
    var temp = fileName.split("/");
    return temp.pop();
}
//FILE CLICK EVENT
function ag_itemSelected(item,itemType,itemDesc){

//CLEAR ALL HIGHLIGHT FOR SELECTED ITEM
jQuery(".ag_selectedItem").removeClass("ag_selectedItem");
jQuery("#ag_treeView a[href$="+jQuery(item).attr("href")+"]").parent().addClass("ag_selectedItem");

//CHECK IS THE #ag_imgDesc_info PRESENT
if (jQuery("#ag_imgDesc_info").length<1)
    jQuery("#ag_preview").html("<h3>'.JText::_("Image Description").'</h3><table border=\'0\' cellspacing=\'0\' cellpadding=\'0\'><tbody><tr><td id=\'ag_preview\'><img src=\''.JURI::base().'components/com_admirorgallery\images/ag_emptyImage.jpg\' /></td>	<td id=\'ag_imgDesc_wrap\'><div id=\'ag_imgDesc_info\'></div></td></tr></tbody></table><form method=\'post\' id=\'ag_form\'><input type=\'hidden\' id=\'ag_form_url_desc\' value=\'\' /><div id=\'ag_descData\'></div></form>");
jQuery("#ag_imgDesc_info").html("<h2>"+ag_extractFilename(jQuery(item).attr("href"))+"</h2>");

//FILL LANGUAGE ARRAY
var ag_lang_available=new Array();
');

$counter=0;
foreach($ag_lang_available as $ag_lang_availableKey => $ag_lang_availableValue){
        $doc->addScriptDeclaration('ag_lang_available['.$counter.']=new Array();');
        $doc->addScriptDeclaration('ag_lang_available['.$counter.'][0]="'.$ag_lang_availableValue["tag"].'";');
        $doc->addScriptDeclaration('ag_lang_available['.$counter.'][1]="'.$ag_lang_availableValue["name"].'";');
        $counter++;
}
$doc->addScriptDeclaration('
jQuery.ajax({
      type: "POST",
      url: "components/com_admirorgallery/scripts/imgManager-render-file.php",
      data: "itemDesc="+itemDesc+"&ag_lang_available="+ag_lang_available+"&ag_url_img="+jQuery(item).attr("href")+"&ag_url_php='.urlencode(JPATH_SITE).'&ag_url_html='.urlencode(JURI::root()).'",
      async: false,
      timeout: 3000,
      success: function(msg){
            //ag_showMessage(msg,"notice");
            var msgArray=msg.split(",");
            jQuery("#ag_preview img").attr("src",msgArray[0]);
            //desc
            jQuery("#ag_imgDesc_info").append("'.JText::_( "Width").': "+msgArray[1]+"<br />");
            jQuery("#ag_imgDesc_info").append("'.JText::_( "Height").': "+msgArray[2]+"<br />");
            jQuery("#ag_imgDesc_info").append("'.JText::_( "Type").': "+msgArray[3]+"<br />");
            jQuery("#ag_imgDesc_info").append("'.JText::_( "Size").': "+msgArray[4]+"<br />");


           jQuery(".icon-32-description").attr("class","icon-32-description");
           jQuery("#ag_form_url_desc").val(itemDesc);

            jQuery("#ag_descData").html("");// Delete previous data
            var msgArray=msgArray[5].split("[split]");
            var msgArrayLength=msgArray.length-1;
            for(i=0;i<msgArrayLength;i+=3){
                jQuery("#ag_descData").append("<div class=\"t\"><div class=\"t\"><div class=\"t\"></div></div></div>");
                jQuery("#ag_descData").append("<div class=\"m mID"+i+"\"></div>");
                jQuery("#ag_descData .mID"+i+"").append("<span class=\"ag_nameLabel\">"+msgArray[i]+"</span>");
                jQuery("#ag_descData .mID"+i+"").append("<span class=\"ag_tagLabel\">"+msgArray[i+1]+"</span>");
                jQuery("#ag_descData .mID"+i+"").append("<textarea class=\"ag_inputText\" id=\"ag_"+msgArray[i+1]+"\">"+msgArray[i+2]+"</textarea>");
                jQuery("#ag_descData").append("<div class=\"b\"><div class=\"b\"><div class=\"b\"></div></div></div><p></p>");
            }
            if(msgArray[msgArrayLength] == "descTrue"){
                      jQuery(".icon-32-removeDesc").attr("class","icon-32-removeDesc");
              }else{
                      jQuery(".icon-32-removeDesc").attr("class","icon-32-removeDesc ag_toolbar_off");
              }
      }
     });

}
// Folder click event
function ag_folderSelected(item,itemType)
{
     // CLEAR ALL HIGHLIGHT FOR SELECTED ITEM
     jQuery(".ag_selectedItem").removeClass("ag_selectedItem");

     // SET NEW HIGHLIGHT FOR ACTIVE ITEM
     jQuery("#ag_bookmarksView a[href$="+jQuery(item).attr("href")+"]").parent().addClass("ag_selectedItem");
     jQuery("#ag_treeView a[href$="+jQuery(item).attr("href")+"]").parent().addClass("ag_selectedItem");
jQuery("#ag_preview").html("<div class=\'ag_screenSection_title\'>"+jQuery(item).attr("href")+"</div>");
	  jQuery("#ag_preview").append("<fieldset></fieldset>");
	  jQuery("#ag_preview fieldset").append("'.JText::_( 'Upload File:' ).'&nbsp;");
	  jQuery("#ag_preview fieldset").append("<input type=\'file\' name=\'Filedata\' id=\'file-upload\' />");

	  jQuery.ajax({
	       type: "POST",
	       url: "components/com_admirorgallery/scripts/imgManager-render-folder.php",
	       data: "ag_phpPath="+jQuery(item).attr("rel")+"&ag_htmlPath="+jQuery(item).attr("href"),
	       async: true,
	       success: function(msg){
		    jQuery("#ag_preview").append(
			msg
		    );
	       }
	  });

}

//Page init
jQuery(function(){
    //Disables the buttons, because no image is selected
    jQuery(".icon-32-description").attr("class","icon-32-description ag_toolbar_off");
    jQuery(".icon-32-removeDesc").attr("class","icon-32-removeDesc ag_toolbar_off");
   
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
	ag_folderSelected(jQuery(this),"folder");
    });


    // Binding event to file links
    jQuery(".ag_fileLink").click(function(e) {
	e.preventDefault();        
	ag_itemSelected(jQuery(this),"file",jQuery(this).attr("alt"));
    });

});//jQuery(function()

jQuery.noConflict();
');


function ag_renderFiles($imagesFolder_phpPath,$imagesFolder_htmlPath){

    $ag_ext_valid = array ("jpg","jpeg","gif","png");
    $imagesFolder_files = JFolder::files($imagesFolder_phpPath);
    $imagesFolder_folders = JFolder::folders($imagesFolder_phpPath);

    echo '<ul>'."\n";

    foreach($imagesFolder_folders as $key => $value){
	echo '<li><img src="'.JURI::base().'components/com_admirorgallery/jquery-treeview/images/folder.gif" />&nbsp;<a href="'.$imagesFolder_htmlPath.'/'.$value.'" rel="'.$imagesFolder_phpPath.'/'.$value.'" class="ag_folderLink">'.$value.'</a>';
	ag_renderFiles($imagesFolder_phpPath.'/'.$value, $imagesFolder_htmlPath.'/'.$value);
	echo '</li>'."\n";
    }

     foreach($imagesFolder_files as $key => $value){
	  $ag_file_ext = strtolower(JFile::getExt($value));
	  $ag_ext_check = array_search($ag_file_ext,$ag_ext_valid);
          $ag_file_descName = substr($value, 0, -(strlen($ag_file_ext))).'desc';
          if(is_numeric($ag_ext_check)){
	    if(file_exists($imagesFolder_phpPath.'/'.$ag_file_descName)){
	      echo '<li><img src="'.JURI::base().'components/com_admirorgallery/jquery-treeview/images/file.gif" /><a href="'.$imagesFolder_htmlPath.'/'.$value.'" alt="'.$imagesFolder_phpPath.'/'.$ag_file_descName.'" rel="descTrue" class="ag_fileLink">'.$value.'</a></li>'."\n";
	    }else{
	      echo '<li><a href="'.$imagesFolder_htmlPath.'/'.$value.'" alt="'.$imagesFolder_phpPath.'/'.$ag_file_descName.'" rel="descFalse" class="ag_fileLink">'.$value.'</a></li>'."\n";
	    }
	  }
     }

    echo '</ul>'."\n";

}//function ag_renderFiles($imagesFolder_phpPath,$imagesFolder_htmlPath)


// RENDER SCREEN STRUCTURE
echo '
<script language="javascript" type="text/javascript">
function submitbutton(pressbutton) {

     switch(pressbutton)
     {
     case "description":
          if(jQuery(".icon-32-description").attr("class") == "icon-32-description ag_toolbar_off"){
               ag_showMessage("'.JText::_( "Cannot create Description file if no image selected." ).'","error");
               return;
          }else{
               var ag_desc_content="";
			jQuery("#ag_form textarea").each(function(index) {

				// Converts HTML to Plain
				var htmlStr = jQuery(this).val();
				//htmlStr = htmlStr.replace(/"/gi, "&quot;").replace(/&/gi, "&amp;").replace(/\'/gi, "&#39;").replace(/</gi, "&lt;").replace(/>/gi, "&gt;");

				ag_desc_content+=(jQuery(this).attr("id")+"[split]");
				ag_desc_content+=(htmlStr+"[split]");
			});

			jQuery.ajax({
				type: "POST",
				url: "'.$ag_script_path.'",
				data: "ag_desc_action=1&ag_url_desc="+jQuery("#ag_form_url_desc").val()+"&ag_desc_content="+ag_desc_content,
				timeout: 3000,
				async: false,
				success: function(msg){
                                        if(msg==="created"){
						ag_showMessage("'.JText::_( "Image Description created.").'","notice");
						if(jQuery("a[alt="+jQuery("#ag_form_url_desc").val()+"]").attr("rel")=="descFalse"){
							jQuery("a[alt="+jQuery("#ag_form_url_desc").val()+"]").parent().prepend(\'<img src="'.JURI::base().'components/com_admirorgallery/jquery-treeview/images/file.gif" />\');
							jQuery("a[alt="+jQuery("#ag_form_url_desc").val()+"]").attr("rel","descTrue");
						}
					}else{
						ag_showMessage("'.JText::_( "Error occurred. Image Description not created.").'","error");
					}


				}
			});
          }
     break;
      case "removeDesc":
	  jQuery.ajax({
		type: "POST",
		url: "'.$ag_script_path.'",
		data: "ag_desc_action=2&ag_url_desc="+jQuery("#ag_form_url_desc").val(),
		timeout: 3000,
		async: false,
		success: function(msg){
			switch(msg){
			      case "removed":
				  ag_showMessage("'.JText::_( "Image Description removed.").'","notice");
				  jQuery("a[alt="+jQuery("#ag_form_url_desc").val()+"]").parent().find("img").remove();
				  jQuery("a[alt="+jQuery("#ag_form_url_desc").val()+"]").attr("rel","descFalse");
				  jQuery(".ag_inputText").each(function(index) {
				    jQuery(this).val("");
				  });
				  jQuery(".icon-32-removeDesc").attr("class","icon-32-removeDesc ag_toolbar_off");
			      break;
			      case "noDesc":
			      break;
			      defaut:
				  ag_showMessage("'.JText::_( "Error occurred. Image Description not removed.").'","error");
			      }

		}
	});

      break;
     }

}
</script>

<form action="index.php" method="post" name="adminForm">
	<input type="hidden" name="task" value="" />
</form>

<table border="0" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td id="ag_bookmarksView">
<!-- BOOKMARK VIEW -->
<div class="ag_screenSection_title">'.JText::_( "Gallery Folders").'</div>
';

// RENDER BOOKMARKS
$ag_bookmarks_xml =& JFactory::getXMLParser( 'simple' );
$ag_bookmarks_xml->loadFile( JPATH_BASE.DS.'components/com_admirorgallery/assets/bookmarks.xml' );// N U
foreach($ag_bookmarks_xml->document->bookmark as $key => $value){
  echo '<div class="ag_bookmark"><img src="'.JURI::base().'components/com_admirorgallery/jquery-treeview/images/folder.gif" />&nbsp;<a href="'.JURI::root().$ag_bookmarks_xml->document->bookmark[$key]->attributes("href").'" rel="'.JPATH_SITE.'/'.$ag_bookmarks_xml->document->bookmark[$key]->attributes("href").'" class="ag_folderLink">'.$ag_bookmarks_xml->document->bookmark[$key]->data().'</a></div>';
}

echo '
</td>
<td id="ag_treeView">
<!-- TREE VIEW -->
<div class="ag_screenSection_title">'.JText::_( "All Folders").'</div>
<ul>
<li><img src="'.JURI::base().'components/com_admirorgallery/jquery-treeview/images/folder.gif" />&nbsp;<a href="'.JPATH_SITE.'/images" rel="'.JURI::root().'images" class="ag_folderLink">images/</a>
';
ag_renderFiles(JPATH_SITE.'/images',JURI::root().'images');
echo '
</li>
</ul>
</td>
<!-- PREVIEW -->
<td id="ag_preview">
<h3>'.JText::_( "Image Description").'</h3>
	<table border="0" cellspacing="0" cellpadding="0"><tbody><tr>
	<td id="ag_preview">
		<img src="'.JURI::base().'components/com_admirorgallery/images/ag_emptyImage.jpg" />
	</td>
	<td id="ag_imgDesc_wrap">
		<div id="ag_imgDesc_info"></div>
	</td></tr></tbody></table>
     <form method="post" id="ag_form"><input type="hidden" id="ag_form_url_desc" value="" /><div id="ag_descData"></div></form>

</td>
</tr>
</tbody>
</table>
';

?>