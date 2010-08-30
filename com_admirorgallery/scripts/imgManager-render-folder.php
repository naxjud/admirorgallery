<?php

// GET POST VALUES
$ag_phpPath = urldecode($_POST["ag_phpPath"]);
$ag_htmlPath = $_POST["ag_htmlPath"];
$ag_phpRoot = $_POST["ag_phpRoot"];
$ag_siteRoot = $_POST["ag_siteRoot"];
// SET VALID IMAGE EXTENSION
$ag_ext_valid = array ("jpg","jpeg","gif","png");

$imagesFolder_files = Array ();
$imagesFolder_folders = Array ();

// GET ALL FOLDERS AND FILES
if ($handle = opendir($ag_phpPath)) {
     while (false !== ($file = readdir($handle))) {
	  if ($file != "." && $file != "..") {
	       if(is_dir($ag_phpPath.'/'.$file)){
		    $imagesFolder_folders[] = $file;
	       }else{
		    $imagesFolder_files[] = $file;
	       }            
	  }
     }
     closedir($handle);
}

// RENDER ALL CONTAINING FOLDERS
foreach($imagesFolder_folders as $key => $value){
     echo '
    <div class="ag_preview_itemWrap">

	<a rel="'.$ag_phpPath.'/'.$value.'" href="'.$ag_htmlPath.'/'.$value.'" class="ag_preview_itemLink ag_preview_folderLink">
	    <div align="center" class="ag_preview_imgWrap">
		<img src="components/com_media/images/folder.png" />		
	    </div>
	</a>

	<div class="ag_preview_controlsWrap">
		<input type="checkbox" value="stories" name="ag_preview_CBOX">'.$value.'
	</div>

    </div>
     ';
}

// RENDER ALL CONTAINING IMAGES
foreach($imagesFolder_files as $key => $value){

     // GET EXTENSION
     $filename = strtolower(basename($value)) ; 
     $ag_file_ext = explode(".", $filename) ;
     $n = count($ag_file_ext)-1;
     $descName = $ag_file_ext[0].'.desc';
     $ag_file_ext = $ag_file_ext[$n];  

     // FILTER IMAGES
     $ag_ext_check = array_search($ag_file_ext,$ag_ext_valid);
     if(is_numeric($ag_ext_check)){
	  echo '
	  <div class="ag_preview_itemWrap">

	      <a rel="'.$ag_phpPath.'/'.$value.'" href="'.$ag_htmlPath.'/'.$value.'" alt="'.$ag_phpPath.'/'.$descName.'"  class="ag_preview_itemLink ag_preview_fileLink">
		  <div align="center" class="ag_preview_imgWrap">
		    ';

	  // USE THUMB IF EXISTS ELSE USE REAL IMAGE
	  if(file_exists($ag_phpRoot.'plugins/content/AdmirorGallery/thumbs/'.basename($ag_phpPath).'/'.$value)){
	       echo '<img src="'.$ag_siteRoot.'plugins/content/AdmirorGallery/thumbs/'.basename($ag_phpPath).'/'.$value.'" />';
	  }else{
	       echo '<img src="'.$ag_htmlPath.'/'.$value.'" />';
	  }

	  echo '
		  </div>
	      </a>

		<div class="ag_preview_controlsWrap">
			<input type="checkbox" value="stories" name="ag_preview_CBOX[]">'.$value.'
		</div>

	  </div>
	  ';
     }
}


echo '
<script type="text/javascript">
     // Binding event to folder links
    jQuery(".ag_preview_folderLink").click(function(e) {
	e.preventDefault();
	ag_folderSelected(jQuery(this),"folder");
    });


    // Binding event to file links
    jQuery(".ag_preview_fileLink").click(function(e) {
	e.preventDefault();
	ag_itemSelected(jQuery(this),"file",jQuery(this).attr("alt"));
    });
</script>
';

?>