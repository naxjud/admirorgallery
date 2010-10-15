<?php

$ag_itemURL = $ag_init_itemURL;

$ag_folderName = dirname($ag_itemURL);
$ag_fileName = basename($ag_itemURL);

$ag_hasXML="";
$ag_hasThumb="";

// Set Possible Description File Apsolute Path // Instant patch for upper and lower case...
$ag_pathWithStripExt=JPATH_SITE.$ag_folderName.'/'.JFile::stripExt(basename($ag_itemURL));
$ag_imgXML_path=$ag_pathWithStripExt.".XML";
if(JFIle::exists($ag_pathWithStripExt.".xml")){
    $ag_imgXML_path=$ag_pathWithStripExt.".xml";
}

include_once(dirname(__FILE__).'/agHelper.php');
$tempInfo = agHelper::ag_imageInfo(JPATH_SITE.$ag_itemURL);
$ag_file_width = $tempInfo["width"].'px';
$ag_file_height = $tempInfo["height"].'px';
$ag_file_type = $tempInfo["type"];
$ag_file_size = $tempInfo["size"];

if(file_exists(JPATH_SITE."/plugins/content/AdmirorGallery/thumbs/".basename($ag_folderName)."/".basename($ag_fileName))){
     $ag_hasThumb='<img src="'.JURI::root().'administrator/components/com_admirorgallery/images/icon-hasThumb.png" class="ag_hasThumb" />';
}

if(file_exists($ag_imgXML_path)){
     $ag_hasXML='<img src="'.JURI::root().'administrator/components/com_admirorgallery/images/icon-hasXML.png" class="ag_hasXML" />';
     $ag_imgXML_xml = & JFactory::getXMLParser( 'simple' );
     $ag_imgXML_xml->loadFile($ag_imgXML_path);
     $ag_imgXML_captions =& $ag_imgXML_xml->document->captions[0];
}

$ag_preview_content='';
$ag_preview_content.='
<div class="ag_screenSection_title">
    <a href="'.$ag_folderName.'/" class="ag_folderLink">'.$ag_folderName.'/</a>'.$ag_fileName.'
</div>
<div class="fieldset">
    <div class="t"><div class="t"><div class="t"></div></div></div>	       
	<div class="m m_imgInfo">
    <table cellspacing="0" cellpadding="3" border="0">
      <tbody>
	<tr>
	  <td id="fieldset1_row1_td1">'.JText::_( 'Set / Change Name to:' ).'&nbsp;</td><td id="fieldset1_row1_td2"><input type="text" name="setChangeNameTo" id="setChangeNameTo" size="50" /><br /></td>
	</tr>
      </tbody>
    </table>
	</div>
    <div class="b"><div class="b"><div class="b"></div></div></div>
</div>
<div class="filePreview">
    <img src="'.substr(JURI::root(),0,-1).$ag_itemURL.'" class="ag_imgThumb" />
</div>
<div id="ag_imgDesc_info">
    <div class="t"><div class="t"><div class="t"></div></div></div>	       
	<div class="m m_imgInfo">
	  '.$ag_hasXML.$ag_hasThumb.'
	  '.JText::_( "Width").': <b>'.$ag_file_width.'</b>&nbsp;|&nbsp;
	  '.JText::_( "Height").': <b>'.$ag_file_height.'</b>&nbsp;|&nbsp;
	  '.JText::_( "Type").': <b>'.$ag_file_type.'</b>&nbsp;|&nbsp;
	  '.JText::_( "Size").': <b>'.$ag_file_size.'</b>
	</div>
    <div class="b"><div class="b"><div class="b"></div></div></div>
</div>
<p> </p>
<div id="ag_descData">
';

function ag_render_caption($ag_lang_name, $ag_lang_tag, $ag_lang_content){
    return '
	<div id="ag_imgDesc_info">
	    <div class="t"><div class="t"><div class="t"></div></div></div>	       
		<div class="m m_imgInfo">
		    <span class="ag_nameLabel">'.$ag_lang_name.'</span>
		    <span class="ag_tagLabel">'.$ag_lang_tag.'</span>
		    <br style="clear:both" />
		    <textarea class="ag_inputText" name="ag_desc_content[]">'.$ag_lang_content.'</textarea><input type="hidden" name="ag_desc_tags[]" value="'.$ag_lang_tag.'" />
		</div>
	    <div class="b"><div class="b"><div class="b"></div></div></div>
	</div>
	<p> </p>
    ';
}

$ag_matchCheck = Array("default");

// GET DEFAULT LABEL
$ag_imgXML_caption_content="";
if(!empty($ag_imgXML_captions->caption)){
  foreach($ag_imgXML_captions->caption as $ag_imgXML_caption){
      if(strtolower($ag_imgXML_caption->attributes('lang')) == "default"){
	  $ag_imgXML_caption_content = $ag_imgXML_caption->data();
      }
  }
}
$ag_preview_content.= ag_render_caption("Default", "default", $ag_imgXML_caption_content);


// GET LABELS ON SITE LANGUAGES
$ag_lang_available = JLanguage::getKnownLanguages(JPATH_SITE);
if(!empty($ag_lang_available)){
    foreach($ag_lang_available as $ag_lang){
	$ag_imgXML_caption_content="";
	if(!empty($ag_imgXML_captions->caption)){
	  foreach($ag_imgXML_captions->caption as $ag_imgXML_caption){
	      if(strtolower($ag_imgXML_caption->attributes('lang')) == strtolower($ag_lang["tag"])){
		  $ag_imgXML_caption_content = $ag_imgXML_caption->data();
		  $ag_matchCheck[]=strtolower($ag_lang["tag"]);
	      }
	  }
	}
	$ag_preview_content.= ag_render_caption($ag_lang["name"], $ag_lang["tag"], $ag_imgXML_caption_content);
    }
}

if(!empty($ag_imgXML_captions->caption)){
    foreach($ag_imgXML_captions->caption as $ag_imgXML_caption){
	$ag_imgXML_caption_attr = $ag_imgXML_caption->attributes('lang');
	if(!is_numeric(array_search(strtolower($ag_imgXML_caption_attr),$ag_matchCheck))){
	      $ag_preview_content.= ag_render_caption($ag_imgXML_caption_attr, $ag_imgXML_caption_attr, $ag_imgXML_caption->data());
	}
    }
}

$ag_preview_content.='
</div>
';

?>