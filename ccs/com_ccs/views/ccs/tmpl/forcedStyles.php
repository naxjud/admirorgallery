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

    $$("input[type=text]").addClass("ccs_input");
    $$("textarea").addClass("ccs_input");

    $$("select").each(function(item){

        var selectWrap = new Element("div.ccs_selectWrap");

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
        item.set("onChange","this.blur()");

    });

    css_addButtonStyles($$("input[type=button]"));
    css_addButtonStyles($$("button"));
    css_addButtonStyles($$(".toggle-editor a"));
    css_addButtonStyles($$("a.toolbar"));
    css_addButtonStyles($$(".quickIcon a"));

});
';
$this->doc->addScriptDeclaration($JS_forcedStyles);