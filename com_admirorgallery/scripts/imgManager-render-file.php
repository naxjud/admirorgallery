<?php
    //$returnArray;


     $ag_url_img = urldecode($_POST['ag_itemURL']);
     $ag_url_html = urldecode($_POST['ag_htmlRoot']);
     $ag_url_php = urldecode($_POST['ag_phpRoot']);

     $ag_file_ext = substr(strrchr(basename($ag_url_img),'.'),1);

     $ag_url_desc = substr($ag_url_img,0,strlen($ag_url_img)-strlen($ag_file_ext));
     $ag_url_desc = $ag_url_php.$ag_url_desc.'desc';
     $ag_lang_available = urldecode($_POST['ag_lang_available']);
     $ag_lang_availableArray = explode("[split]",$ag_lang_available);
     $ag_url_img_php = $ag_url_php.$ag_url_img;

    include_once(dirname(__FILE__).'/agHelper.php');

    $ag_possibleFolder = basename(dirname($ag_url_img));
    $ag_possibleFile = basename($ag_url_img);
    $imagePath = $ag_url_img;
    if(file_exists($ag_url_php."/plugins/content/AdmirorGallery/thumbs/".$ag_possibleFolder."/".$ag_possibleFile)){
      $imagePath=$ag_url_html."plugins/content/AdmirorGallery/thumbs/".$ag_possibleFolder."/".$ag_possibleFile;
    }
    $returnArray[0]= $ag_url_img;

    $tempInfo = agHelper::ag_imageInfo($ag_url_img_php);

    $returnArray[1] = $tempInfo["width"].'px';
    $returnArray[2] = $tempInfo["height"].'px';
    $returnArray[3] = $tempInfo["type"];
    $returnArray[4] = $tempInfo["size"];
    $returnArray[5] = "noDesc";

    $langArray = Array();
    $ag_matchCheck = Array();
    $ag_content='';
    $output='';

    if(file_exists($ag_url_desc)){
	  $returnArray[5] = "hasDesc";
	  $file=fopen($ag_url_desc,"r");
	  while (!feof($file))
	  {
	       $ag_content.=fgetc($file);
	  }
	  fclose($file);
    }
    //Load default description fields
    $output = 'default[split]default[split]';
    $from='{default}';
    $to='{/default}';
    $extract=stristr($ag_content, $from);
    $extract=substr($extract,strlen($from),strpos($extract, $to)-(strlen($to)-1));
    $output.= $extract.'[split]';
    $ag_matchCheck[]='default';

    //Load all available lang fields
    $langCount = count($ag_lang_availableArray);
    for($a = 0; $a < $langCount ; $a+=2) {// List descriptions for installed languages
        $output .= $ag_lang_availableArray[$a+1].'[split]'.strtolower($ag_lang_availableArray[$a]).'[split]';
        $from='{'.strtolower($ag_lang_availableArray[$a]).'}';
        $to='{/'.strtolower($ag_lang_availableArray[$a]).'}';
        $extract=stristr($ag_content, $from);
        $extract=substr($extract,strlen($from),strpos($extract,$to)-(strlen($to)-1));
        $output.=$extract.'[split]';
        if(strlen($extract)>0){
              $ag_matchCheck[]=strtolower($ag_lang_availableArray[$a]);
         }
    }
    // Create other langTag fields
    if(count($ag_matchCheck)>0){

         if (preg_match_all("#{(.*?)}#i", $ag_content, $ag_matches, PREG_PATTERN_ORDER) > 0) {
              for ($i = 0; $i < count($ag_matches[0]) ; $i+=2) {
                   if(!is_numeric(array_search(strtolower($ag_matches[1][$i]),$ag_matchCheck))){
                    $output .= $ag_matches[1][$i].'[split]'.strtolower($ag_matches[1][$i]).'[split]';
                                $from='{'.strtolower($ag_matches[1][$i]).'}';
                                $to='{/'.strtolower($ag_matches[1][$i]).'}';
                                $extract=stristr($ag_content, $from);
                                $extract=substr($extract,strlen($from),strpos($extract,$to)-(strlen($to)-1));
                                $output.=$extract.'[split]';
                   }
              }
         }

    }

    $output=substr($output,0,strlen($output)-7);

    $returnArray[6]=$output;

    echo implode("[ArraySplit]",$returnArray);
?>