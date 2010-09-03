<?php

// GET POST VALUES
$ag_itemURL = $_POST["ag_itemURL"];
$ag_phpRoot = urldecode($_POST["ag_phpRoot"]);
$ag_htmlRoot = urldecode($_POST["ag_htmlRoot"]);

// SET VALID IMAGE EXTENSION
$ag_ext_valid = array ("jpg","jpeg","gif","png");

$imagesFolder_folders = Array ();
$imagesFolder_files = Array ();

// GET ALL FOLDERS AND FILES
if(is_dir($ag_phpRoot.$ag_itemURL)){
     if ($handle = opendir($ag_phpRoot.$ag_itemURL)) {
	  while (false !== ($file = readdir($handle))) {
	       if ($file != "." && $file != "..") {
		    if(is_dir($ag_phpRoot.$ag_itemURL.'/'.$file)){
			 $imagesFolder_folders[] = $file;
		    }else{
			 $imagesFolder_files[] = $file;
		    }            
	       }
	  }
	  closedir($handle);
     }
}
$returnArray[0]= $ag_itemURL;

// RENDER ALL CONTAINING FOLDERS
$output = "";
foreach($imagesFolder_folders as $key => $value){
     $output.=$ag_itemURL.$value.'/'.'[split]'.$value.'[split]';
}
$returnArray[1] = $output;


// RENDER ALL CONTAINING IMAGES
$output = "";
foreach($imagesFolder_files as $key => $value){

     // GET EXTENSION 
     $ag_file_ext = substr(strrchr(basename($value),'.'),1);


     // FILTER IMAGES
     $ag_ext_check = array_search($ag_file_ext,$ag_ext_valid);
     if(is_numeric($ag_ext_check)){
	  $output.=$ag_itemURL.$value.'[split]'.$value.'[split]';

	  $ag_url_desc = $value;
	  $ag_url_desc = substr($ag_url_desc,0,strlen($ag_url_desc)-strlen($ag_file_ext));
	  $ag_url_desc = $ag_phpRoot.$ag_itemURL.$ag_url_desc.'desc';
	  if(file_exists($ag_url_desc)){
	       $output.='hasDesc[split]';
	  }else{
	       $output.='noDesc[split]';
	  }
     }
}
$returnArray[2] = $output;

echo implode("[ArraySplit]",$returnArray);

?>