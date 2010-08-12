<?php

function ag_desc_lineRender($ag_lang_tag,$ag_lang_name,$ag_lang_content){

          echo $ag_lang_name.'[split]'.$ag_lang_tag.'[split]'; 
          
          $from='{'.$ag_lang_tag.'}';
          $to='{/'.$ag_lang_tag.'}';
          $extract=stristr($ag_lang_content, $from);
          $extract=substr($extract,strlen($from),strpos($extract, $to)-(strlen($to)-1)); 

          echo $extract.'[split]';  
   
          return $extract;        
}

?>
