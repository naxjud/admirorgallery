<?php
function fileRoundSize($size) {
$bytes = array('B','KB','MB','GB','TB');
  foreach($bytes as $val) {
   if($size > 1024){
    $size = $size / 1024;
   }else{
    break;
   }
  }
  return round($size, 2)." ".$val;
}
?>
