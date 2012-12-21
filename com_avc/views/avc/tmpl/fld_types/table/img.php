<?php
if(is_file(JPATH_ROOT.DS.$FIELD_VALUE)){
	echo '<img 
	class="avc_admin_tbl_field_img" 
	src="'.JURI::root().$FIELD_VALUE.'" 
	/ >';
}

