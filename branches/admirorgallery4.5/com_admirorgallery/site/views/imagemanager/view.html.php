<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class AdmirorgalleryViewImagemanager extends JView {

    function display($tpl = null) {

        // Make sure you are logged in and have the necessary access
        $validUsers = Array("Super User", "Super Administrator", "Administrator", "Manager", "Publisher");
        $user = JFactory::getUser();
        //J1.5
        //if (!in_array($user->usertype, $validUsers)) {
        //if (!in_array($user->name, $validUsers)) {
        //    JResponse::setHeader('HTTP/1.0 403',true);
        //    JError::raiseWarning( 403, JText::_('JERROR_ALERTNOAUTHOR') );
        //    return;
        //}

        $mainframe = JFactory::getApplication();
        $params = $mainframe->getParams();

        $this->assign('galleryName', $params->get('galleryName'));

        parent::display($tpl);
    }

    function _renderBreadcrumb($AG_itemURL, $ag_rootFolder, $ag_folderName, $ag_fileName) {
        $ag_breadcrumb = '';
        $ag_breadcrumb_link = '';
        if ($ag_rootFolder != $AG_itemURL && !empty($AG_itemURL)) {
            $ag_breadcrumb.='<a href="' . $ag_rootFolder . '" class="AG_folderLink AG_common_button"><span><span>' . substr($ag_rootFolder, 0, -1) . '</span></span></a>/';
            $ag_breadcrumb_link.=$ag_rootFolder;
            $ag_breadcrumb_cut = substr($ag_folderName, strlen($ag_rootFolder));
            $ag_breadcrumb_cut_array = explode("/", $ag_breadcrumb_cut);
            if (!empty($ag_breadcrumb_cut_array[0])) {
                foreach ($ag_breadcrumb_cut_array as $cut_key => $cut_value) {
                    $ag_breadcrumb_link.=$cut_value . '/';
                    $ag_breadcrumb.='<a href="' . $ag_breadcrumb_link . '" class="AG_folderLink AG_common_button"><span><span>' . $cut_value . '</span></span></a>/';
                }
            }
            $ag_breadcrumb.=$ag_fileName;
        } else {
            $ag_breadcrumb.=$ag_rootFolder;
        }
        return $ag_breadcrumb;
    }

}
