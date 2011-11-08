<?php
$original_file = $_GET['img'];
$mime = "";
if (preg_match("/jpg|jpeg/i", $original_file)) {
    $mime= "image/jpg";
} else if (preg_match("/png/i", $original_file)) {
    $mime= "image/png";
} else if (preg_match("/gif/i", $original_file)) {
    $mime= "image/gif";
}
header('Content-Type: '.$mime);
header('Content-Disposition: attachment; filename="'.basename($original_file).'"');
readfile($original_file);
?>