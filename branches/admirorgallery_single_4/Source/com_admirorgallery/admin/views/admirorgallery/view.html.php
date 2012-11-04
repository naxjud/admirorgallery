<?php

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

class AdmirorgalleryViewAdmirorgallery extends JView
{
    protected $item;
    protected $form;
    protected $state;

    function display($tpl = null)
    {
            $this->state	= $this->get('State');
            $this->item		= $this->get('Item');
            $this->form		= $this->get('Form');
            JToolBarHelper::title( JText::_( 'COM_ADMIRORGALLERY_CONTROL_PANEL'), 'controlpanel' );
            $this->form = $this->get('Form');
            parent::display($tpl);
    }
}
