<?php


/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

$ag_breadcrumb='';
$ag_breadcrumb_link='';
if($ag_rootFolder!=$ag_itemURL){
     $ag_breadcrumb.='<a href="'.$ag_rootFolder.'" class="ag_folderLink ag_breadcrumb_button"><span>'.substr($ag_rootFolder,0,-1).'</span></a>/';
     $ag_breadcrumb_link.=$ag_rootFolder;
     $ag_breadcrumb_cut=substr($ag_folderName,strlen($ag_rootFolder));
     $ag_breadcrumb_cut_array=split("/",$ag_breadcrumb_cut);
     if(!empty($ag_breadcrumb_cut_array[0])){
	  foreach($ag_breadcrumb_cut_array as $cut_key => $cut_value){
	       $ag_breadcrumb_link.=$cut_value.'/';
	       $ag_breadcrumb.='<a href="'.$ag_breadcrumb_link.'" class="ag_folderLink ag_breadcrumb_button"><span>'.$cut_value.'</span></a>/';
	  }
     }
     $ag_breadcrumb.=$ag_fileName;
}else{
     $ag_breadcrumb.=$ag_rootFolder;
}

?>