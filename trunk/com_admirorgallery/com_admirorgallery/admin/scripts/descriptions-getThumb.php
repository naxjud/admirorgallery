<?php

$ag_url_img = urldecode($_POST['ag_url_img']);
$ag_url_html = urldecode($_POST['ag_url_html']);
$ag_url_php = urldecode($_POST['ag_url_php']);

$ag_possibleFolder = basename(dirname($ag_url_img));
$ag_possibleFile = basename($ag_url_img);

if(file_exists($ag_url_php."/plugins/content/AdmirorGallery/thumbs/".$ag_possibleFolder."/".$ag_possibleFile)){
  echo $ag_url_html."plugins/content/AdmirorGallery/thumbs/".$ag_possibleFolder."/".$ag_possibleFile;
}else{
  echo $ag_url_img;
}

?>