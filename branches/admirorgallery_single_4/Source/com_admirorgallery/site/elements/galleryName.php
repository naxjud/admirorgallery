<?php
 
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class JFormFieldGalleryName extends JFormField
{
   //var   $_name = 'galleryName'; 
   public $type = 'galleryName';
   protected function getInput()
   {
      JHTML::_('behavior.modal');

      $value = htmlspecialchars(html_entity_decode($this->element['value'], ENT_QUOTES), ENT_QUOTES);
      $name = $this->element['name'];
      $control_name = $this->element['control_name'];

      $content= '
      <input
	  name="'.$control_name.'['.$name.']" type="text"
	  class="inputbox" id="'.$name.'"
	  value="'.$value.'" size="20" />
      ';
      $link = JRoute::_('index.php?option=com_admirorgallery&amp;view=galleryname&amp;tmpl=component&amp;e_name='.$name);
      $content.= '
	  <a href="'.$link.'" rel="{handler: \'iframe\', size: {x: 500, y: 400}}" class="modal" style="text-decoration:none;">
		<button type="button" style="cursor:pointer; cursor:hand">'.JText::_('AG_SELECT_GALLERY').'</button>
	  </a>
      ';

      return $content;
   }
   protected function getOptions()
   {

   }
}

?>