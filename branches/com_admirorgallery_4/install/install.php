<?php
//print_r($this->manifest);

/***********************************************************************************************
* ---------------------------------------------------------------------------------------------
* PLUGIN INSTALLATION SECTION
* ---------------------------------------------------------------------------------------------
***********************************************************************************************/

$plugins = $this->manifest->getElementByIdPath('plugins');
if (is_a($plugins, 'JSimpleXMLElement') && count($plugins->children())) {

	foreach ($plugins->children() as $plugin)
	{
		$pname		= $plugin->attributes('plugin');
		$pgroup		= $plugin->attributes('group');
		$porder		= $plugin->attributes('order');

		// Set the installation path
		if (!empty($pname) && !empty($pgroup)) {
			$this->parent->setPath('extension_root', JPATH_ROOT.DS.'plugins'.DS.$pgroup);
		} else {
			$this->parent->abort(JText::_('Plugin').' '.JText::_('Install').': '.JText::_('No plugin file specified'));
			return false;
		}

		/**
		 * ---------------------------------------------------------------------------------------------
		 * Filesystem Processing Section
		 * ---------------------------------------------------------------------------------------------
		 */

		// If the plugin directory does not exist, lets create it
		$created = false;
		if (!file_exists($this->parent->getPath('extension_root'))) {
			if (!$created = JFolder::create($this->parent->getPath('extension_root'))) {
				$this->parent->abort(JText::_('Plugin').' '.JText::_('Install').': '.JText::_('Failed to create directory').': "'.$this->parent->getPath('extension_root').'"');
				return false;
			}
		}

		/*
		* If we created the plugin directory and will want to remove it if we
		* have to roll back the installation, lets add it to the installation
		* step stack
		*/
		if ($created) {
			$this->parent->pushStep(array ('type' => 'folder', 'path' => $this->parent->getPath('extension_root')));
		}

		// Copy all necessary files
		$element = $plugin->getElementByPath('files');
		if ($this->parent->parseFiles($element, -1) === false) {
			// Install failed, roll back changes
			$this->parent->abort();
			return false;
		}

		// Copy all necessary files
		$element = $plugin->getElementByPath('languages');
		if ($this->parent->parseLanguages($element, 1) === false) {
			// Install failed, roll back changes
			$this->parent->abort();
			return false;
		}

		// Copy media files
		$element = $plugin->getElementByPath('media');
		if ($this->parent->parseMedia($element, 1) === false) {
			// Install failed, roll back changes
			$this->parent->abort();
			return false;
		}

		/**
		 * ---------------------------------------------------------------------------------------------
		 * Database Processing Section
		 * ---------------------------------------------------------------------------------------------
		 */
		$db = JFactory::getDBO();

		// Check to see if a plugin by the same name is already installed
		$query = 'SELECT `id`' .
		' FROM `#__plugins`' .
		' WHERE folder = '.$db->Quote($pgroup) .
		' AND element = '.$db->Quote($pname);
		$db->setQuery($query);
		if (!$db->Query()) {
			// Install failed, roll back changes
			$this->parent->abort(JText::_('Plugin').' '.JText::_('Install').': '.$db->stderr(true));
			return false;
		}
		$id = $db->loadResult();

		// Was there a plugin already installed with the same name?
		if ($id) {

			if (!$this->parent->getOverwrite())
			{
				// Install failed, roll back changes
				$this->parent->abort(JText::_('Plugin').' '.JText::_('Install').': '.JText::_('Plugin').' "'.$pname.'" '.JText::_('already exists!'));
				return false;
			}

		} else {
			$row = JTable::getInstance('plugin');
			$row->name = JText::_(ucfirst($pgroup)).' - '.JText::_(ucfirst($pname));
			$row->ordering = $porder;
			$row->folder = $pgroup;
			$row->iscore = 0;
			$row->access = 0;
			$row->client_id = 0;
			$row->element = $pname;
			$row->published = 1;
			$row->params = '';

			if (!$row->store()) {
				// Install failed, roll back changes
				$this->parent->abort(JText::_('Plugin').' '.JText::_('Install').': '.$db->stderr(true));
				return false;
			}
		}

		$status->plugins[] = array('name'=>$pname,'group'=>$pgroup);
	}
}

/***********************************************************************************************
* ---------------------------------------------------------------------------------------------
* SETUP DEFAULTS
* ---------------------------------------------------------------------------------------------
***********************************************************************************************/
// Check to see if a plugin by the same name is already installed
$query = 'SELECT `id`' .
' FROM `#__extensions`' .
' WHERE parent = 0 and name=' .$db->Quote('Admiror Gallery').
' AND parent = 0';
$db->setQuery($query);
//$componentID = $db->loadResult();

//if(!is_null($componentID) && $componentID > 0) {
//	$query = 'UPDATE #__extensions SET params = '
//		. $db->Quote("noTranslation=2\n"
//		. "defaultText=\n"
//		. "overwriteGlobalConfig=1\n"
//		. "storageOfOriginal=md5\n"
//		. "frontEndPublish=1\n"
//		. "frontEndPreview=1\n"
//		. "showDefaultLanguageAdmin=0\n"
//		. "copyparams=1\n"
//		. "transcaching=0\n"
//		. "cachelife=180\n"
//		. "qacaching=1\n"
//		. "qalogging=0\n")
//		. 'WHERE id = ' . $componentID;
//	$db->setQuery($query);
//		
//	if (!$db->Query()) {
//		// Install failed, roll back changes
//		$this->parent->abort(JText::_('Plugin').' '.JText::_('Install').': '.$db->stderr(true));
//		return false;
//	}
//}

// Insert the default lanauage if no language exist
$query = 'SELECT count(*) FROM #__languages';
$db->setQuery($query);
$count = $db->loadResult();

if($count==0) {
	$query = 'INSERT INTO #__languages VALUES (1, '
		.$db->quote('English (United Kingdom)')
		. ', 1, ' .$db->quote('en_GB.utf8, en_GB.UT'). ', ' . $db->quote('en-GB')
		. ', '. $db->quote('en') .', '. $db->quote('').', '. $db->quote('').', '. $db->quote(''). ', 1);';
	$db->execute($query);
}

/***********************************************************************************************
* ---------------------------------------------------------------------------------------------
* Execute specific system steps to ensure a consistent installtion
* ---------------------------------------------------------------------------------------------
***********************************************************************************************/

/***********************************************************************************************
* ---------------------------------------------------------------------------------------------
* OUTPUT TO SCREEN
* ---------------------------------------------------------------------------------------------
***********************************************************************************************/
$rows = 0;
?>
<h2>Admiror Gallery Installation</h2>
<table class="adminlist">
	<thead>
		<tr>
			<th class="title" colspan="2"><?php echo JText::_('Extension'); ?></th>
			<th width="30%"><?php echo JText::_('Status'); ?></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="3"></td>
		</tr>
	</tfoot>
	<tbody>
		<tr class="row0">
			<td class="key" colspan="2"><?php echo 'Admiror Gallery '.JText::_('Component'); ?></td>
			<td><strong><?php echo JText::_('Installed'); ?></strong></td>
		</tr>
<!-- Commented out because we do not have modules, jet ;)-->
<?php //if (count($status->modules)) : ?>
<!--		<tr>
			<th><?php //echo JText::_('Module'); ?></th>
			<th><?php //echo JText::_('Client'); ?></th>
			<th></th>
		</tr>
	<?php //foreach ($status->modules as $module) : ?>
		<tr class="row<?php //echo (++ $rows % 2); ?>">
			<td class="key"><?php //echo $module['name']; ?></td>
			<td class="key"><?php //echo ucfirst($module['client']); ?></td>
			<td><strong><?php //echo JText::_('Installed'); ?></strong></td>
		</tr> -->
	<?php //endforeach;
	//endif;
if (count($status->plugins)) : ?>
		<tr>
			<th><?php echo JText::_('Plugin'); ?></th>
			<th><?php echo JText::_('Group'); ?></th>
			<th></th>
		</tr>
	<?php foreach ($status->plugins as $plugin) : ?>
		<tr class="row<?php echo (++ $rows % 2); ?>">
			<td class="key"><?php echo ucfirst($plugin['name']); ?></td>
			<td class="key"><?php echo ucfirst($plugin['group']); ?></td>
			<td><strong><?php echo JText::_('Installed'); ?></strong></td>
		</tr>
	<?php endforeach;
endif; ?>
	</tbody>
</table>
