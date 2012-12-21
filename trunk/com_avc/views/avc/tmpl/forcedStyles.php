<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

$JS_forcedStyles='
function css_addButtonStyles(item){
    item.addClass("avc_button")
        .addEvents({                    
            mouseover: function(){
                $(this).addClass("avc_buttonOver");
            },
            mouseleave: function(){
                $(this).removeClass("avc_buttonOver");
            }
        });
}

window.addEvent("domready", function(){ 

    $$("input[type=text]").addClass("avc_input");
    $$("textarea").addClass("avc_input");

    $$("select").each(function(item){

        var selectWrap = new Element("div.avc_selectWrap");

        css_addButtonStyles(selectWrap);

        selectWrap.wraps(item)
                  .setStyles({
                        "display":"block",
                        "overflow":"hidden",
                        "width":item.getComputedSize().totalWidth+"px",
                        "height":item.getComputedSize().totalHeight+"px",
                        "padding":"0px !important"
                    });

        item.setStyles({
                        "width":item.getComputedSize().totalWidth+28+"px"
                    });

    });

    css_addButtonStyles($$("input[type=button]"));
    css_addButtonStyles($$("button"));
    css_addButtonStyles($$(".toggle-editor a"));
    css_addButtonStyles($$("a.toolbar"));
    css_addButtonStyles($$(".quickIcon a"));
    css_addButtonStyles($$("#AVC_menu a"));

    css_addButtonStyles($$(".button2-right a"));
    css_addButtonStyles($$(".button2-left a"));


});
';
$this->doc->addScriptDeclaration($JS_forcedStyles);