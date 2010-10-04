<?php

$ag_itemURL = $ag_init_itemURL;
$ag_preview_content='';

$ag_folderName = dirname($ag_itemURL);
$ag_fileName = basename($ag_itemURL);

$ag_preview_content='';

if($ag_itemURL != $ag_rootFolder){
$ag_preview_content.='
<div class="ag_screenSection_title">
     <a href="'.$ag_folderName.'/"  class="ag_folderLink">'.$ag_folderName.'/</a>'.$ag_fileName.'
</div>
';
}else{
$ag_preview_content.='
<div class="ag_screenSection_title">
     '.$ag_itemURL.'
</div>
';
}

$ag_preview_content.='
<fieldset>
<table cellspacing="0" cellpadding="3" border="0">
     <tbody>
     <tr>
	  <td id="fieldset1_row1_td1">'.JText::_( 'Set / Change Name to:' ).'&nbsp;</td><td id="fieldset1_row1_td2"><input type="text" name="setChangeNameTo" id="setChangeNameTo" size="50" /><br /></td>
     </tr>
     <tr>
	  <td id="fieldset1_row1_td1">'.JText::_( 'Upload File:' ).'&nbsp;</td><td id="fieldset1_row1_td2"><input type="file" name="file_upload" size="50" title="'.JText::_( 'Only jpg, jpeg, gif, png and zip are valid extensions.').'" /><br /></td>
     </tr>
     </tbody>
</table>
</fieldset>
';

$ag_preview_content.='<div id="ag_itemsWrapper">';

// RENDER FOLDERS
$ag_folders=JFolder::folders(JPATH_SITE.$ag_itemURL);
if(!empty($ag_folders)){
foreach($ag_folders as $key => $value){
     $ag_preview_content.='
<div class="ag_preview_itemWrap">
<a href="'.$ag_itemURL.$value.'/" class="ag_preview_itemLink ag_folderLink">
<div align="center" class="ag_preview_imgWrap">
     <img src="'.JURI::base().'components/com_media/images/folder.png" />
</div>
</a>
<div class="ag_preview_controlsWrap">
<input type="checkbox" value="'.$ag_itemURL.$value.'/" name="ag_preview_CBOX[]">
<br style="clear:both" />
<span class="ag_preview_itemTitle">'.$value.'</span>
</div>
</div>
';
}
}

// RENDER IMAGES

// CREATED SORTED ARRAY OF IMAGES
$ag_files=JFolder::files(JPATH_SITE.$ag_itemURL);
$ag_ext_valid = array ("jpg","jpeg","gif","png");// SET VALID IMAGE EXTENSION

if(!empty($ag_files)){

     $ag_images_priority=Array();
     $ag_images_noPriority=Array();
     $ag_images=Array();

     foreach($ag_files as $key => $value){
	  if(is_numeric(array_search(strtolower(JFile::getExt(basename($value))),$ag_ext_valid))){

	       $ag_folderName = $ag_itemURL;
	       $ag_fileName = basename($value);

	       // Set Possible Description File Apsolute Path // Instant patch for upper and lower case...
	       $ag_pathWithStripExt=JPATH_SITE.$ag_folderName.JFile::stripExt($ag_fileName);
	       $ag_imgXML_path=$ag_pathWithStripExt.".XML";
	       if(JFIle::exists($ag_pathWithStripExt.".xml")){
		    $ag_imgXML_path=$ag_pathWithStripExt.".xml";
	       }
	       if(file_exists($ag_imgXML_path)){
		    $ag_imgXML_xml = & JFactory::getXMLParser( 'simple' );
		    $ag_imgXML_xml->loadFile($ag_imgXML_path);
		    $ag_imgXML_priority =& $ag_imgXML_xml->document->priority[0]->data();
	       }

	       if(!empty($ag_imgXML_priority) && file_exists($ag_imgXML_path)){
		    $ag_images_priority[$value] = $ag_imgXML_priority;// PRIORITIES IMAGES
	       }else{
		    $ag_images_noPriority[] = $value;// NON PRIORITIES IMAGES
	       }

	  }
     }
}

if(!empty($ag_images_priority)){
asort($ag_images_priority);
     foreach($ag_images_priority as $key => $value){
	  $ag_images[]=$key;
     }
}

if(!empty($ag_images_noPriority)){
natcasesort($ag_images_noPriority);
     foreach($ag_images_noPriority as $key => $value){
	  $ag_images[]=$value;
     }
}

if(!empty($ag_images)){
foreach($ag_images as $key => $value){


     $ag_hasXML="";
     $ag_hasThumb="";	

     // Set Possible Description File Apsolute Path // Instant patch for upper and lower case...
     $ag_pathWithStripExt=JPATH_SITE.$ag_itemURL.JFile::stripExt(basename($value));
     $ag_imgXML_path=$ag_pathWithStripExt.".xml";
     if(JFIle::exists($ag_pathWithStripExt.".XML")){
	  $ag_imgXML_path=$ag_pathWithStripExt.".XML";
     }

     $ag_imgXML_priority="";
     if(file_exists($ag_imgXML_path)){
	  $ag_hasXML='<img src="'.JURI::base().'components/com_admirorgallery/images/icon-hasXML.png"  class="ag_hasXML" />';
	  $ag_imgXML_xml = & JFactory::getXMLParser( 'simple' );
	  $ag_imgXML_xml->loadFile($ag_imgXML_path);
	  $ag_imgXML_priority =& $ag_imgXML_xml->document->priority[0]->data();
     }

     if(file_exists(JPATH_SITE."/plugins/content/AdmirorGallery/thumbs/".basename($ag_folderName)."/".basename($value))){
	  $ag_hasThumb='<img src="'.JURI::base().'components/com_admirorgallery/images/icon-hasThumb.png"  class="ag_hasThumb" />';
     }

     $ag_preview_content.='
     <div class="ag_preview_itemWrap">
     <a href="'.$ag_itemURL.$value.'" class="ag_preview_itemLink ag_fileLink">
	  <div align="center" class="ag_preview_imgWrap">
	  <img src="'.dirname(JURI::base()).$ag_itemURL.$value.'" class="ag_imgThumb" />
	  </div>
     </a>
     <div class="ag_preview_controlsWrap">
	  <input type="checkbox" value="'.$ag_itemURL.$value.'" name="ag_preview_CBOX[]">
	  <span class="ag_preview_priorityLabel">'.JText::_( 'Priority' ).':&nbsp;</span>
	  <input type="textbox" size="3" value="'.$ag_imgXML_priority.'" name="ag_preview_PRIORITY['.$ag_itemURL.$value.']">
	  <br style="clear:both" />
	  <span class="ag_preview_itemTitle">
	  '.$ag_hasXML.$ag_hasThumb.'
	  '.$value.'</span>
     </div>
     </div>
     ';

     
}
}



$ag_preview_content.='</div>';


?>