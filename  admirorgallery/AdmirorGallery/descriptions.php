<?php

// require_once ( JPATH_BASE .DS.'/plugins/content/AdmirorGallery/descriptions.php');
// $imagesDescritions[imageName]
defined('_JEXEC') or die('Restricted access');
    
// Gallery Apsolute Path
$galleryApsolutePath=JPATH_SITE.$rootFolder.$imagesFolder_name.'/';

// Create Images Array
unset($imagesDescritions);

if (file_exists($galleryApsolutePath))
{	
	if ($dh = opendir($galleryApsolutePath)) {
	  while (($f = readdir($dh)) !== false) {
		  if((substr(strtolower($f),-3) == 'jpg') || (substr(strtolower($f),-4) == 'jpeg') || (substr(strtolower($f),-3) == 'gif') || (substr(strtolower($f),-3) == 'png')) {
			  
			  // Set image name as imageDescription value, as predifined value
			  $imagesDescritions[$f] = $f;
			  
			  // Set Possible Description File Apsolute Path // Instant patch for upper and lower case...
			  if(file_exists($galleryApsolutePath.(substr($f, 0, -3))."desc")){
			    $descriptionFileApsolutePath=$galleryApsolutePath.(substr($f, 0, -3))."desc";
		    }else{
		      $descriptionFileApsolutePath=$galleryApsolutePath.(substr($f, 0, -3))."DESC";
		    }
			  
		  if(file_exists($descriptionFileApsolutePath)){// Check is descriptions file exists

			// Read Description File Content
			$descriptionFileContent='';
			$file=fopen($descriptionFileApsolutePath,"r");
			while (!feof($file)) 
			  { 
			  $descriptionFileContent.=fgetc($file);
			  }
			fclose($file);
			
			$descriptionFileContent=str_replace("\n","<br>",$descriptionFileContent);
			
			
			$langTag="default";
			  if(stripos($descriptionFileContent, "{".$langTag."}") !== false && stripos($descriptionFileContent, "{/".$langTag."}") !== false){// If none lang encoding match found extract default tag and place it as imageDescription value
				$from="{".$langTag."}";
				$to="{/".$langTag."}";
			  $content=$descriptionFileContent;
			  $content=stristr($content, $from);
			  $content=substr($content,strlen($from),strpos($content, $to)-(strlen($to)-1));  
			  $imagesDescritions[$f]=$content;
			}        
				
				$lang =& JFactory::getLanguage();
				$langTag=strtolower($lang->getTag());
				
				if(stripos($descriptionFileContent, "{".$langTag."}") !== false && stripos($descriptionFileContent, "{/".$langTag."}") !== false){// Extract part of text which suits to tag for language and place it as imageDescription value
				  $from="{".$langTag."}";
				  $to="{/".$langTag."}";
			  $content=$descriptionFileContent;
			  $content=stristr($content, $from);
			  $content=substr($content,strlen($from),strpos($content, $to)-(strlen($to)-1));  
			  $imagesDescritions[$f]=$content;
			}
	  
		  }// if(file_exists($descriptionFileApsolutePath))
			  
			  
		  }// if((substr(strtolower($f),-3) == 'jpg') || (substr(strtolower($f),-3) == 'gif') || (substr(strtolower($f),-3) == 'png'))
	  }// while (($f = readdir($dh)) !== false)
	  
	  closedir($dh);
	  
	}// if ($dh = opendir($galleryApsolutePath))
}
else
$imagesDescritions=null;


?>
