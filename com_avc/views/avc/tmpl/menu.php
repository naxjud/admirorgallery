<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

// CREATE GROUPS
$view_groups = array();
foreach ($this->views as $key => $view) {

	if( empty( $view["group_alias"] ) ){
		$view["group_alias"] = "COM_AVC_UNCATEGORISED";
	}

    // FILTER PUBLISHED        
    $groupsUserIsIn = JAccess::getGroupsByUser(JFactory::getUser()->id);
    if(in_array(8,$groupsUserIsIn))
    {
        // is superadmin
        $view_groups[ $view["group_alias"] ][ $key ] = $view;
    }else{
        // not superadmin
        if( (int)$view["admin_only"] == 0 ){
            $view_groups[ $view["group_alias"] ][ $key ] = $view;
        }
    }
    
}

echo '
	<button type="button" onclick="AVC_menu_exec(\'0\', \'default\'); return false;">'. JText::_( strtoupper( 'Home' ) ) .'</button>
    <button type="button" id="AVC_CATS_MENU_OPEN">'. JText::_( strtoupper( 'Views' ) ) .' &#9660;</button>
';

echo '
<div id="AVC_CATS_MENU_WRAP">
    <table width="100%" border="0" cellpadding="0" cellspacing="0"><tbody><tr>
        <td id="AVC_CATS_MENU">
';

echo '<ul class="menu">'."\n";

foreach ($view_groups as $group_name => $group_items) {

	echo '<li id="AVC_CATS_MENU_LI_'. $group_name. '">'."\n";

	echo '<a href="#">'. JText::_( strtoupper( $group_name )) .'</a>'."\n";

	echo '<ul id="'. $group_name. '">'."\n";
	foreach ($group_items as $key => $view) {

		if($view["published"] == "1"){

			echo '<li>'."\n";

			$tooltip = '';
			if(!empty($view["description"])){
				$tooltip = 'title="'. $view["description"] .'"';
			}

			echo '<a href="#" onclick="AVC_menu_exec(\''. $key .'\', \'table\'); return false;" '.$tooltip.'>'."\n";

			if(!empty($view["icon_path"])){
				echo '<img src="'. JURI::root() . $view["icon_path"] .'" />'."\n";
			}

			echo JText::_( strtoupper( $view["name"] )) ."\n";

			echo '</a>'."\n";

			echo '</li>'."\n";

		}

	}
	echo '</ul>'."\n";

	echo '</li>'."\n";

}

echo '</ul>'."\n";

echo '
	</td>
    <td id="AVC_CATS_SUBMENU" style="display: none;"></td>
</tr></tbody></table>
</div>
';


$JS_AVC_menu='

// ======================================================
// MENU - DECLARE FUNCTIONS
// ======================================================

function AVC_menu_exec(curr_view_id,layout){

	$(\'curr_view_id\').value=curr_view_id;

	if($(\'filter_order\')){
		$(\'filter_order\').value=\'\';
	}
	if($(\'filter_order_Dir\')){
		$(\'filter_order_Dir\').value=\'\';
	}
	if($(\'filter_search_value\')){
		$(\'filter_search_value\').value=\'\';
	}
	if($(\'filter_filter_value\')){
		$(\'filter_filter_value\').value=\'\';
	}

	$(\'layout\').value=layout;

	$(\'adminForm\').submit();
}

window.addEvent(\'domready\', function() {

	$$(\'#AVC_CATS_MENU_OPEN\').addEvents({

		mouseenter: function(){

			$$(\'#AVC_CATS_MENU_WRAP\').setStyles({\'display\': \'block\'});

	    }

	});

	// MENU
	$$(\'#AVC_CATS_MENU .menu ul\').getParent().getElement(\'a\').addEvents({
	    mouseenter: function(){
			if($$(\'#AVC_CATS_SUBMENU\').getElement(\'ul\')[0] != null){
				var group_name = $$(\'#AVC_CATS_SUBMENU\').getElement(\'ul\').get(\'id\');
				$(\'\'+group_name).adopt().inject(\'AVC_CATS_MENU_LI_\'+group_name);
			}

	        $$(\'#AVC_CATS_MENU_WRAP\').addClass("hover");
	        $$(\'#AVC_CATS_MENU_WRAP a\').removeClass("hover");
	        this.addClass("hover");
	        $$(\'#AVC_CATS_SUBMENU\').set(\'html\', \'\').setStyles({\'display\': \'table-cell\'});
	        var submenu_header = document.createElement("h2");
	        var submenu_header_text = this.get(\'text\');
	        $("AVC_CATS_SUBMENU").appendChild(submenu_header)
	                            .set(\'text\',submenu_header_text);
	        this.getParent().getElement(\'ul\').adopt().inject(\'AVC_CATS_SUBMENU\');

	    }

	});

	$$(\'#AVC_CATS_MENU_WRAP\').addEvents({
	    mouseleave: function(){
	    	if($$(\'#AVC_CATS_SUBMENU\').getElement(\'ul\')[0] != null){
				var group_name = $$(\'#AVC_CATS_SUBMENU\').getElement(\'ul\').get(\'id\');
				$(\'\'+group_name).adopt().inject(\'AVC_CATS_MENU_LI_\'+group_name);
			}
	        this.setStyles({\'display\': \'none\'});

	    }
	});

});

';

$this->doc->addScriptDeclaration($JS_AVC_menu);