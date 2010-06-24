<?php
defined('_JEXEC') or die('Restricted access');
isset($customTag) ? true : $customTag='';
isset($rel) ? true : $rel='';
isset($cssClass) ? true : $cssClass='';
isset($imgWrapS) ? true : $imgWrapS='';
isset($imgWrapE) ? true : $imgWrapE='';
$html.='
<a href="'.$joomla_site_path.$rootFolder.$imagesFolder_name.'/'.$imagesValue.'" title="'.htmlspecialchars(strip_tags($imagesDescritions[$imagesValue])).'" class="'.$cssClass.'" rel="'.$rel.'" '.$customTag.' target="_blank">'.$imgWrapS;
		$fileStat=stat($imagesFolder.$imagesValue);
		$fileAge=time()-$fileStat['ctime']; 
		if((int)$fileAge < (int)($_newImageTag_days_*24*60*60) && $_newImageTag_==1){
			$html .= '<span class="ag_newTag"><img src="'.$joomla_site_path.'/plugins/content/AdmirorGallery/newTag.gif" class="ag_newImageTag" /></span>';		
		}		
$html.='<img src="'.$joomla_site_path.'/plugins/content/AdmirorGallery/thumbs/'.$imagesFolder_name.'/'.$imagesValue.'" alt="'.htmlspecialchars(strip_tags($imagesDescritions[$imagesValue])).'" class="ag_imageThumb">'.$imgWrapE.'</a>';
?>
