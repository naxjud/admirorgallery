<?php
 
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );


foreach($CCS->output as $row){
	if($row['published']=="1"){
	echo '
	
	<table cellspacing="0" cellpadding="0" border="0" width="100%" class="ccs_companies_item">
	<tbody>
	<tr>
	<td width="1%"><img src="'.JURI::base().DS.$row['image'].'" /></td>
	<td>
	<h3>'.$row['name'].'</h3>	
	';

	if($row['contact_info']){
		echo '<p>'.$row['contact_info'].'</p><p>&nbsp;</p>';
	}
	
	if($row['url']){
		echo '
		<p>
		<a href="'.$row['url'].'" target="_blank">
		'.$row['url'].'
		</a>
		</p>
		';
	}

	echo '
	</td>
	</tr>
	</tbody>
	</table>
	</a>

	<hr style="clear:both;" />
	';
	}
}


echo '<br style="clear:both;" />';
