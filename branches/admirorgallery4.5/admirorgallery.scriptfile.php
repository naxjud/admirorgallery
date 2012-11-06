d<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.utilities.simplexml' );
/**
 * Script file of Admiror Gallery component
 */
class com_admirorgalleryInstallerScript
{
	/**
	 * method to install the component
	 *
	 * @return void
	 */
	function install($parent)
	{
            $manifest = $parent->get("manifest");
            $parent = $parent->getParent();
            $source = $parent->getPath("source");
			
            $installer = new JInstaller();
			
            // Install plugins
            foreach($manifest->plugins->plugin as $plugin) {
                $attributes = $plugin->attributes();
                $plg = $source.DS.$attributes['folder'].DS.$attributes['plugin'];
                $installer->install($plg);
            }

            $db = JFactory::getDbo();
            $tableExtensions = $db->nameQuote("#__extensions");
            $columnElement   = $db->nameQuote("element");
            $columnType      = $db->nameQuote("type");
            $columnFolder    = $db->nameQuote("folder");
            $columnEnabled   = $db->nameQuote("enabled");

            // Enable plugins
            $db->setQuery(
                "UPDATE
                    $tableExtensions
                SET
                    $columnEnabled=1
                WHERE
                    $columnElement='admirorgallery'
                AND
                    $columnType='plugin'
                AND
                    $columnFolder='content'"
            );

            $db->query();
            // Enable plugins
            $db->setQuery(
                "UPDATE
                    $tableExtensions
                SET
                    $columnEnabled=1
                WHERE
                    $columnElement='admirorbutton'
                AND
                    $columnType='plugin'
                AND
                    $columnFolder='editors-xtd'"
            );

            $db->query();
            // $parent is the class calling this method
            $parent->setRedirectURL('index.php?option=com_admirorgallery');
	}

	/**
	 * method to uninstall the component
	 *
	 * @return void
	 */
	function uninstall($parent)
	{
            $manifest = $parent->get("manifest");
            $parent = $parent->getParent();
            $source = $parent->getPath("source");

            $installer = new JInstaller();

            // Install plugins
            foreach($manifest->plugins->plugin as $plugin) {
                $attributes = $plugin->attributes();
                $plg = $source.DS.$attributes['folder'].DS.$attributes['plugin'];
                $installer->uninstall('plugin',$attributes['plugin']);
            }
	}

	/**
	 * method to update the component
	 *
	 * @return void
	 */
	function update($parent)
	{
		$this->install($parent);
		// $parent is the class calling this method
		echo '<p>' . JText::_('COM_HELLOWORLD_UPDATE_TEXT') . '</p>';
	}

	/**
	 * method to run before an install/update/uninstall method
	 *
	 * @return void
	 */
	function preflight($type, $parent)
	{
		// $parent is the class calling this method
		// $type is the type of change (install, update or discover_install)
		echo '<p>' . JText::_('COM_HELLOWORLD_PREFLIGHT_' . $type . '_TEXT') . '</p>';
	}

	/**
	 * method to run after an install/update/uninstall method
	 *
	 * @return void
	 */
	function postflight($type, $parent)
	{
		// $parent is the class calling this method
		// $type is the type of change (install, update or discover_install)
		echo '<p>' . JText::_('COM_HELLOWORLD_POSTFLIGHT_' . $type . '_TEXT') . '</p>';
	}
}