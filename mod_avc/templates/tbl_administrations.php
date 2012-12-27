<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

?>

<table cellspacing="0" cellpadding="0" border="0" width="100%" class="myTable">
<thead>
	<tr>
		<th><?php echo JText::_('Surname');?></th>
		<th><?php echo JText::_('Name');;?></th>
	</tr>
</thead>
<tbody>

<?php
foreach ($AVC->output as $row) {
	echo '<tr>'."\n";
    echo '<td>' . $row['prezime'] . '</td>'."\n";
    echo '<td>' . $row['ime'] . '</td>'."\n";
	echo '</tr>'."\n";
}
?>

</tbody>
</table>