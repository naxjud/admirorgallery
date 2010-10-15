<?php

if(!empty($_POST["ag_itemURL"]) && !is_dir(JPATH_SITE.$_POST["ag_itemURL"])){

    $ag_itemURL = $_POST["ag_itemURL"];
    $ag_folderName = dirname($ag_itemURL);

    // Set Possible Description File Apsolute Path // Instant patch for upper and lower case...
    $ag_pathWithStripExt=JPATH_SITE.$ag_folderName.'/'.JFile::stripExt(basename($ag_itemURL));
    $ag_imgXML_path=$ag_pathWithStripExt.".XML";
    if(JFIle::exists($ag_pathWithStripExt.".xml")){
	$ag_imgXML_path=$ag_pathWithStripExt.".xml";
    }

     if(file_exists($ag_imgXML_path)){
	  $file=fopen($ag_imgXML_path,"r");
	  while (!feof($file))
	  {
	       $ag_imgXML_content.=fgetc($file);
	  }
	  fclose($file);
	  $ag_captions_new = "";
	  $ag_captions_new.="<captions>"."\n";
	  $ag_captions_new.="</captions>";
	  $ag_imgXML_content = preg_replace("#<captions[^}]*>(.*?)</captions>#s", $ag_captions_new,$ag_imgXML_content);

	  if(!empty($ag_imgXML_content)){
		$handle = fopen($ag_imgXML_path,"w") or die("");
		if(fwrite($handle,$ag_imgXML_content)){
		    $ag_notice[] = Array ("Captions removed from Image Details file.", basename($ag_itemURL));
		}else{
		    $ag_error[] = Array ("Cannot remove captions from Image Details file.", basename($ag_itemURL));
		}
		fclose($handle);
	  }

     }else{
	  $ag_error[] = Array ("No Image Details file founded:",basename($ag_itemURL));
     }

}else{
     $ag_error[] = Array ("No image selected.");
}

?>

/home/igor/Radni/www/joomlaTest/photo_16550_20100517.XML