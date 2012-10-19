<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

$JS_forcedStyles='
function css_addButtonStyles(item){
    item.addClass("ccs_button")
        .addEvents({                    
            mouseover: function(){
                $(this).addClass("ccs_buttonOver");
            },
            mouseleave: function(){
                $(this).removeClass("ccs_buttonOver");
            }
        });
}

window.addEvent("domready", function(){ 

    $$("input[type=text]").addClass("ccs_insetShadow");

    $$("input[type=text]").addClass("ccs_border2");
    $$("select").addClass("ccs_border2");

    css_addButtonStyles($$("input[type=button]"));
    css_addButtonStyles($$("button"));
    css_addButtonStyles($$(".toggle-editor a"));
    css_addButtonStyles($$("a.toolbar"));
    css_addButtonStyles($$("select"));
    css_addButtonStyles($$(".quickIcon a"));

});
';
$this->doc->addScriptDeclaration($JS_forcedStyles);