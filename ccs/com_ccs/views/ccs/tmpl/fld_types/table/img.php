<?php
if(is_file(JPATH_ROOT.DS.$field_value)){
	echo '<img 
	class="ccs_admin_tbl_field_img" 
	src="'.JURI::root().$field_value.'" 
	/ >';
}

