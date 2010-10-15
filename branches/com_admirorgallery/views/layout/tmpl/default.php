<?php
 
// No direct access
defined('_JEXEC') or die('Restricted access');

$ag_inlineParams='';
$ag_inlineParams.=' template="'.$this->template.'"';
$ag_inlineParams.=' thumbWidth="'.$this->thumbWidth.'"';
$ag_inlineParams.=' thumbHeight="'.$this->thumbHeight.'"';
$ag_inlineParams.=' thumbAutoSize="'.$this->thumbAutoSize.'"';
$ag_inlineParams.=' arrange="'.$this->arrange.'"';
$ag_inlineParams.=' newImageTag="'.$this->newImageTag.'"';
$ag_inlineParams.=' newImageDays="'.$this->newImageDays.'"';
$ag_inlineParams.=' frameWidth="'.$this->frameWidth.'"';
$ag_inlineParams.=' showSignature="'.$this->showSignature.'"';
$ag_inlineParams.=' popup="'.$this->popup.'"';
$ag_inlineParams.=' foregroundColor="'.$this->foregroundColor.'"';
$ag_inlineParams.=' highliteColor="'.$this->highliteColor.'"';

JPluginHelper::importPlugin( 'content','AdmirorGallery' );
global $mainframe;
$article = new JObject();
$article->text = '{AG '.$ag_inlineParams.' }'.$this->galleryName.'{/AG}';
$article->id = 0;
$limitstart = 0;
$dispatcher =& JDispatcher::getInstance();
$results = $dispatcher->trigger('onPrepareContent', array ( &$article, & $params, $limitstart));
echo $article->text;

?>