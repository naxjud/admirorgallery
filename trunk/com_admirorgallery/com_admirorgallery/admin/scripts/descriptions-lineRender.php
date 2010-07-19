<?php

function ag_desc_lineRender($ag_lang_tag,$ag_lang_name,$ag_lang_content){

          echo '<span class="ag_label_wrap">Name: <span class="ag_label">'.$ag_lang_name.'</span><br />Tag: <span class="ag_label">'.$ag_lang_tag.'</span></span>'; 
          
          $from='{'.$ag_lang_tag.'}';
          $to='{/'.$ag_lang_tag.'}';
          $extract=stristr($ag_lang_content, $from);
          $extract=substr($extract,strlen($from),strpos($extract, $to)-(strlen($to)-1)); 

          echo '<textarea class="ag_inputText" id="ag_'.$ag_lang_tag.'">'.$extract.'</textarea>';  
   
          return $extract;           
}

?>
