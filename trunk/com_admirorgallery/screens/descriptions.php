
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

require_once (JPATH_BASE.DS.'components/com_admirorgallery/scripts/growl.php');

$ag_lang_available = JLanguage::getKnownLanguages(JPATH_SITE);

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
				url: "'.JURI::root().'administrator/components/com_admirorgallery/scripts/descriptions-create.php",
				data: "ag_url_desc="+jQuery("#ag_form_url_desc").val()+"&ag_desc_content="+ag_desc_content,
				timeout: 3000,
				async: false,
				success: function(msg){
                                        if(msg=="created"){
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
		url: "'.JURI::root().'administrator/components/com_admirorgallery/scripts/descriptions-remove.php",
		data: "ag_url_desc="+jQuery("#ag_form_url_desc").val(),
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

';





function ag_renderFiles($imagesFolder_path,$imagesFolder_absPath){

  $ag_ext_valid = array ("jpg","jpeg","gif","png");
	$imagesFolder_files = JFolder::files($imagesFolder_path);
	$imagesFolder_folders = JFolder::folders($imagesFolder_path);

	echo '<ul>'."\n";

	foreach($imagesFolder_folders as $key => $value){
		echo '<li><img src="'.JURI::base().'components/com_admirorgallery/jquery-treeview/images/folder.gif" />&nbsp;'.$value;
			ag_renderFiles($imagesFolder_path.'/'.$value, $imagesFolder_absPath.'/'.$value);
		echo '</li>'."\n";
	}
	
	foreach($imagesFolder_files as $imagesFolder_files_key => $imagesFolder_files_value){
	$ag_file_ext = strtolower(JFile::getExt($imagesFolder_files_value));
	$ag_ext_check = array_search($ag_file_ext,$ag_ext_valid);
	$ag_file_descName = substr($imagesFolder_files_value, 0, -(strlen($ag_file_ext))).'desc';

	  if(is_numeric($ag_ext_check)){
	    if(file_exists($imagesFolder_path.'/'.$ag_file_descName)){
	      echo '<li><img src="'.JURI::base().'components/com_admirorgallery/jquery-treeview/images/file.gif" /><a href="'.$imagesFolder_absPath.'/'.$imagesFolder_files_value.'" alt="'.$imagesFolder_path.'/'.$ag_file_descName.'" rel="descTrue" class="ag_files">'.$imagesFolder_files_value.'</a></li>'."\n";
	    }else{
	      echo '<li><a href="'.$imagesFolder_absPath.'/'.$imagesFolder_files_value.'" alt="'.$imagesFolder_path.'/'.$ag_file_descName.'" rel="descFalse" class="ag_files">'.$imagesFolder_files_value.'</a></li>'."\n";
	    }
	  }
		 
	}

	echo '</ul>'."\n";

}//function ag_renderFiles($imagesFolder_path,$imagesFolder_absPath)




$doc->addScriptDeclaration('
	jQuery(function(){

		jQuery(".icon-32-description").attr("class","icon-32-description ag_toolbar_off");
		jQuery(".icon-32-removeDesc").attr("class","icon-32-removeDesc ag_toolbar_off");

		jQuery("#ag_nav > ul").treeview({
			animated: "slow",
			collapsed: true,
			persist: "cookie"
		});

		function ag_extractFilename(fileName){
			var temp = fileName.split("/");
			return temp.pop();
		}

		function ag_imgSelected(ag_url_img,ag_url_desc){
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
			  url: "components/com_admirorgallery/scripts/descriptions-getThumb.php",
			  data: "ag_url_img="+ag_url_img+"&ag_url_html='.urlencode(JURI::root()).'&ag_url_php='.urlencode(JPATH_SITE).'",
			  async: true,
			  success: function(msg){
			    jQuery("#ag_preview img").attr("src",msg);
			  }
			});
			
			jQuery("#ag_imgDesc_info").html("<h2>"+ag_extractFilename(ag_url_img)+"</h2>");

			jQuery.ajax({
				type: "POST",
				url: "components/com_admirorgallery/scripts/descriptions-imageInfo.php",
				data: "ag_url_img="+ag_url_img+"&ag_url_html='.urlencode(JURI::root()).'&ag_url_php='.urlencode(JPATH_SITE).'",
				async: true,
				success: function(msg){
					var msgArray=msg.split(",");					
					jQuery("#ag_imgDesc_info").append("'.JText::_( "Width").': "+msgArray[0]+"<br />");
					jQuery("#ag_imgDesc_info").append("'.JText::_( "Height").': "+msgArray[1]+"<br />");
					jQuery("#ag_imgDesc_info").append("'.JText::_( "Type").': "+msgArray[2]+"<br />");
					jQuery("#ag_imgDesc_info").append("'.JText::_( "Size").': "+msgArray[3]+"<br />");
				}
			});

               jQuery(".icon-32-description").attr("class","icon-32-description");
               
               jQuery("#ag_form_url_desc").val(ag_url_desc);

	       jQuery.ajax({
		    type: "POST",
		    url: "components/com_admirorgallery/scripts/descriptions-read.php",
		    data: "ag_url_desc="+ag_url_desc+"&ag_lang_available="+ag_lang_available+"&ag_url_php='.urlencode(JPATH_SITE).'",
		    timeout: 3000,
		    async: false,
		    success: function(msg){
			      jQuery("#ag_descData").html("");// Delete previous data
			      var msgArray=msg.split("[split]");
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
			

		}//function ag_imgSelected(ag_url_img,ag_url_desc)

		jQuery(".ag_files").click(function(e) {
			e.preventDefault();        
			ag_imgSelected(jQuery(this).attr("href"),jQuery(this).attr("alt"));
		});

	});//jQuery(function()

	jQuery.noConflict();
');

echo '<table border="0" cellspacing="0" cellpadding="0" width="100%"><tbody><tr><td id="ag_nav"><h3>'.JText::_( "Folder").': images /</h3>'."\n";

ag_renderFiles(JPATH_SITE.'/images',JURI::root().'images');

echo '
</td><td id="ag_params">
<h3>'.JText::_( "Image Description").'</h3>
	<table border="0" cellspacing="0" cellpadding="0"><tbody><tr>
	<td id="ag_preview">
		<img src="'.JURI::base().'components/com_admirorgallery/images/ag_emptyImage.jpg" />
	</td>
	<td id="ag_imgDesc_wrap">
		<div id="ag_imgDesc_info"></div>
	</td></tr></tbody></table>
     <form method="post" id="ag_form"><input type="hidden" id="ag_form_url_desc" value="" /><div id="ag_descData"><div></form>
</td></tr></tbody></table>
'."\n";



?>
