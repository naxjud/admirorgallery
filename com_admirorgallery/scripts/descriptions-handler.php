<?php
$case = $_POST["ag_desc_action"];
switch ($case){
    case 1:{
        $ag_desc_content=stripslashes(htmlspecialchars($_POST["ag_desc_content"]));
        $ag_url_desc=$_POST["ag_url_desc"];

        $ag_desc_contentArray = explode("[split]",$ag_desc_content);

        $ag_content = " ";

        if(!empty($ag_desc_contentArray)){
          for($a = 0; $a < count($ag_desc_contentArray)-1 ; $a+=2) {
              if(strlen($ag_desc_contentArray[$a+1]) > 0){
                    $ag_content .= '{'.substr($ag_desc_contentArray[$a],3,strlen($ag_desc_contentArray[$a])).'}'.$ag_desc_contentArray[$a+1].'{/'.substr($ag_desc_contentArray[$a],3,strlen($ag_desc_contentArray[$a])).'}'."\n";
              }
          }
        }

        if(!empty($ag_content)){
          $handle = fopen($ag_url_desc,"w") or die("");
          if(fwrite($handle,$ag_content)){
                  echo "created";
          };
          fclose($handle);
        }
        break;
    }
    case 2 :{
        $ag_url_desc = $_POST["ag_url_desc"];
        if(file_exists($ag_url_desc)){
                if(unlink($ag_url_desc)){
                    echo "removed";
                };
        }else{
            echo "noDesc";
        }
        break;
    }
}



?>
