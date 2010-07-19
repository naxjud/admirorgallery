<?php

jimport('joomla.filesystem.file');
jimport('joomla.language.language');

$doc = &JFactory::getDocument();
$doc->addStyleSheet(JURI::base().'components/com_admirorgallery/css/template.css');

$doc->addScript(JURI::base().'components/com_admirorgallery/scripts/jquery.js');
$doc->addScript(JURI::base().'components/com_admirorgallery/scripts/jquery.timers.js');
$doc->addScript(JURI::base().'components/com_admirorgallery/jquery-treeview/jquery.treeview.js');
$doc->addScript(JURI::base().'components/com_admirorgallery/jquery-treeview/lib/jquery.cookie.js');
$doc->addStyleSheet(JURI::base().'components/com_admirorgallery/jquery-treeview/jquery.treeview.css');

$ag_lang_available = JLanguage::getKnownLanguages(JPATH_SITE);

echo '

<script language="javascript" type="text/javascript">


function ag_showMessage(message) {
     jQuery("#ag_params").prepend("<div id=\'ag_message\'>"+message+"</div>");
     jQuery("#ag_message").fadeIn("slow");
     jQuery(this).oneTime(5000, function() {
          jQuery("#ag_message").fadeOut("slow",function(){jQuery("#ag_message").remove();});					
	});
}



function submitbutton(pressbutton) {
	
     switch(pressbutton)
     {
     case "description":
          if(jQuery(".icon-32-description").attr("class") == "icon-32-description ag_toolbar_off"){
               ag_showMessage("'.JText::_( "Cannot create Description file if no image selected." ).'");
               return;
          }else{
               var ag_desc_contentArray=new Array();
			jQuery("#ag_form textarea").each(function(index) {
				ag_desc_contentArray[index]=new Array();
				ag_desc_contentArray[index][0]=jQuery(this).attr("id");
				ag_desc_contentArray[index][1]=jQuery(this).val();               
			});

			jQuery.ajax({
				type: "POST",
				url: "'.JURI::root().'administrator/components/com_admirorgallery/scripts/descriptions-create.php",
				data: "ag_url_desc="+jQuery("#ag_form_url_desc").val()+"&ag_desc_contentArray="+ag_desc_contentArray,
				timeout: 3000,
				async: false,
				success: function(msg){
					if(msg=="created"){
						ag_showMessage("'.JText::_( "Image Description created.").'");
						if(jQuery("a[alt="+jQuery("#ag_form_url_desc").val()+"]").attr("rel")=="descFalse"){
							jQuery("a[alt="+jQuery("#ag_form_url_desc").val()+"]").parent().prepend(\'<img src="'.JURI::base().'components/com_admirorgallery/jquery-treeview/images/file.gif" />\');
							jQuery("a[alt="+jQuery("#ag_form_url_desc").val()+"]").attr("rel","descTrue");
						}
					}else{
						ag_showMessage("'.JText::_( "Error occurred. Image Description not created.").'");
					}
				

				}
			});
          }
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


		jQuery("#ag_nav > ul").treeview({
			animated: "slow",
			collapsed: true,
			persist: "cookie"
		});

		function ag_extractFilename(fileName){
			var temp = fileName.split("/");
			return temp.pop();
		}

		function ag_imgSelected(ag_url_img,ag_url_desc,ag_descStatus){
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
			jQuery("#ag_preview img").attr("src",ag_url_img);
			jQuery("#ag_imgDesc_info").html("<h2>"+ag_extractFilename(ag_url_img)+"</h2>");

			jQuery.ajax({
				type: "POST",
				url: "components/com_admirorgallery/scripts/descriptions-imageInfo.php",
				data: "ag_url_img="+ag_url_img+"&ag_url_html='.urlencode(JURI::root()).'&ag_url_php='.urlencode(JPATH_SITE).'",
				async: true,
				success: function(msg){
				    jQuery("#ag_imgDesc_info").append(msg);
				}
			});

               jQuery(".icon-32-description").attr("class","icon-32-description");
               
               jQuery("#ag_form_url_desc").val(ag_url_desc);
			if(ag_descStatus == "descTrue"){
				jQuery.ajax({
					type: "POST",
					url: "components/com_admirorgallery/scripts/descriptions-read.php",
					data: "ag_url_desc="+ag_url_desc+"&ag_lang_available="+ag_lang_available+"&ag_url_php='.urlencode(JPATH_SITE).'",
					timeout: 3000,
					async: false,
					success: function(msg){
					    jQuery("#ag_descData").html(msg);
					}
				});
			}else{
				jQuery.ajax({
					type: "POST",
					url: "components/com_admirorgallery/scripts/descriptions-new.php",
					data: "ag_url_desc="+ag_url_desc+"&ag_lang_available="+ag_lang_available+"&ag_url_php='.urlencode(JPATH_SITE).'",
					timeout: 3000,
					async: false,
					success: function(msg){
					    jQuery("#ag_descData").html(msg);
					}
				});
			}

		}//function ag_imgSelected(ag_url_img,ag_url_desc,ag_descStatus)

		jQuery(".ag_files").click(function(e) {
			e.preventDefault();        
			ag_imgSelected(jQuery(this).attr("href"),jQuery(this).attr("alt"),jQuery(this).attr("rel"));
		});

	});//jQuery(function()

	jQuery.noConflict();
');

echo '<table border="0" cellspacing="0" cellpadding="0"><tbody><tr><td id="ag_nav"><h3>'.JText::_( "Folder").': images /</h3>'."\n";

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
