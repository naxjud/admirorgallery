<?php

// No direct access
defined('_JEXEC') or die('Restricted access');


// CREATE GROUPS
$view_groups = array();
foreach ($this->views as $key => $view) {

	if( empty( $view["group_alias"] ) ){
		$view["group_alias"] = "COM_AVC_UNCATEGORISED";
	}

	$view_groups[ $view["group_alias"] ][ $key ] = $view;
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

	echo '<li>'."\n";

	echo '<a href="#">'. JText::_( strtoupper( $group_name )) .'</a>'."\n";

	echo '<ul>'."\n";
	foreach ($group_items as $key => $view) {

		echo '<li>'."\n";

		echo '<a href="#" onclick="AVC_menu_exec(\''. $key .'\', \'table\'); return false;">'."\n";

		if(!empty($view["icon_path"])){
			echo '<img src="'. JURI::root() . $view["icon_path"] .'" />'."\n";
		}

		echo JText::_( strtoupper( $view["name"] )) ."\n";

		echo '</a>'."\n";

		echo '</li>'."\n";

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


$JS_AVC_menu.= '

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
	if($(\'filter_order_Dir\')){
		$(\'filter_order_Dir\').value=\'\';
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
	        $$(\'#AVC_CATS_MENU_WRAP\').addClass("hover");
	        $$(\'#AVC_CATS_MENU_WRAP a\').removeClass("hover");
	        this.addClass("hover");
	        $$(\'#AVC_CATS_SUBMENU\').set(\'html\', \'\').setStyles({\'display\': \'table-cell\'});
	        var submenu_header = document.createElement("h2");
	        var submenu_header_text = this.get(\'text\');
	        var submenu_image = "";
	        //submenu_image = this.getParent().getElement(\'img\').get(\'src\');
	        $("AVC_CATS_SUBMENU").setStyles({\'background-image\':\'url(\'+submenu_image+\')\'})
	                            .appendChild(submenu_header)
	                            .set(\'text\',submenu_header_text);
	        this.getParent().getElement(\'ul\').clone().inject(\'AVC_CATS_SUBMENU\');
	    }

	});

	$$(\'#AVC_CATS_MENU_WRAP\').addEvents({
	    mouseleave: function(){
	        this.setStyles({\'display\': \'none\'});
	    }
	});

});

';

$this->doc->addScriptDeclaration($JS_AVC_menu);