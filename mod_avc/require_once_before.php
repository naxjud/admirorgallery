    <?php

    /**
     * @package		Joomla.Site
     * @subpackage	mod_breadcrumbs
     * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
     * @license		GNU General Public License version 2 or later; see LICENSE.txt
     */
    // no direct access
    defined('_JEXEC') or die;

    JHTML::_('behavior.mootools', 'more');

    $JS_AVC_layout = '
    ///////////////////////////////////////
    //
    // AVC LAYOUT REQUIRE ONCE
    //
    ///////////////////////////////////////

    var AVC_LAYOUT_HISTORY = {};
    var AVC_TEMPLATE = {};
    var AVC_LAYOUT_VISUALS = {};
    var AVC_LAYOUT_FIELDLIST = {};
    var AVC_COLLECTION = {};
    var AVC_LAYOUT_ALLOWCHANGES = true;

    function countObjAttr(obj) {
        var count = 0;
        for(var prop in obj) {
            if(obj.hasOwnProperty(prop))
                count = count + 1;
        }
        return count;
    }

    function AVC_LAYOUT_ORDER(group, module, colName){

        // PREVENT MULTIPLE CLICKING
        if(AVC_LAYOUT_ALLOWCHANGES==false){return;}
        AVC_LAYOUT_ALLOWCHANGES=false;

        var value = AVC_LAYOUT_HISTORY[group][countObjAttr(AVC_LAYOUT_HISTORY[group])]["modules"][module];

        var order_dir = "ASC";
        if(value["order_by"]){
            var splited = value["order_by"].split(" ");
            if(splited[1] == "ASC" && splited[0] == colName){
                order_dir = "DESC";
            }
        } 
        AVC_LAYOUT_HISTORY_UPDATE(group, module, "order_by", colName+" "+order_dir);
        
        AVC_LAYOUT_SUBMIT();

    }

    function AVC_LAYOUT_OPEN(group, module, options){

        // PREVENT MULTIPLE CLICKING
        if(AVC_LAYOUT_ALLOWCHANGES==false){return;}
        AVC_LAYOUT_ALLOWCHANGES=false;

        var data, openModule;

        AVC_LAYOUT_HISTORY[ group ][ (countObjAttr(AVC_LAYOUT_HISTORY[ group ])+1) ] = {};
        AVC_LAYOUT_HISTORY[ group ][ countObjAttr(AVC_LAYOUT_HISTORY[ group ]) ]["modules"] = {};
        AVC_LAYOUT_HISTORY[ group ][ countObjAttr(AVC_LAYOUT_HISTORY[ group ]) ]["opened"] = module;

        Object.each(AVC_TEMPLATE[module]["open"], function(value, key){
            data = value;
            data = JSON.stringify( data );
            for (var i = 0; i < options.length; i++) {
                data = data.replace("AVCVAR"+i,options[i]);
            }
            if(value["module"]==null){
                openModule = module;
            }else{
                openModule = value["module"];
            }
            if(AVC_LAYOUT_HISTORY[ group ]!=null){                
                AVC_LAYOUT_HISTORY[ group ][ countObjAttr(AVC_LAYOUT_HISTORY[ group ]) ]["modules"][ openModule ]=JSON.parse(data);
            }
        });



        AVC_LAYOUT_SUBMIT();

    }

    function AVC_LAYOUT_GOTO(group, goto){

        // PREVENT MULTIPLE CLICKING
        if(AVC_LAYOUT_ALLOWCHANGES==false){return;}
        AVC_LAYOUT_ALLOWCHANGES=false;

        while ( countObjAttr(AVC_LAYOUT_HISTORY[group]) > goto ) {
            delete AVC_LAYOUT_HISTORY[group][countObjAttr(AVC_LAYOUT_HISTORY[group])];
        }

        AVC_LAYOUT_SUBMIT();

    }

    function AVC_LAYOUT_HISTORY_UPDATE(group, module, key, value){
        AVC_LAYOUT_HISTORY[group][countObjAttr(AVC_LAYOUT_HISTORY[group])]["modules"][module][key]=value;
    }

    function AVC_LAYOUT_SUBMIT(){

        var scroll =  $(document.body).getScroll();
        AVC_LAYOUT_VISUALS["scroll_to"]["x"] = ""+scroll.x;
        AVC_LAYOUT_VISUALS["scroll_to"]["y"] = ""+scroll.y;

        $("AVC_LAYOUT_STATE_VISUALS").value = JSON.stringify(AVC_LAYOUT_VISUALS);
        $("AVC_LAYOUT_STATE_HISTORY").value = JSON.stringify(AVC_LAYOUT_HISTORY);
        $("AVC_COLLECTION").value = JSON.stringify(AVC_COLLECTION);
        
        document.AVC_LAYOUT_FORM.submit();

    }

    ////////////////////////////////////////////////
    //
    // COLLECTION SUPPORT
    //
    function AVC_LAYOUT_COLLECTION_ADD(item_code,price_per_item,num_of_items,description){
        if( AVC_COLLECTION["order"] == undefined ){
            AVC_COLLECTION["order"] = {};
        }
        if( AVC_COLLECTION["order"][item_code] == undefined ){
            AVC_COLLECTION["order"][item_code] = {};
            AVC_COLLECTION["order"][item_code]["price_per_item"]=price_per_item;
            AVC_COLLECTION["order"][item_code]["num_of_items"]=num_of_items;
            AVC_COLLECTION["order"][item_code]["description"]=description;
        }else{
            AVC_COLLECTION["order"][item_code]["num_of_items"]+=num_of_items;
        }        
        
        AVC_LAYOUT_SUBMIT();
    }
    //
    ////////////////////////////////////////////////

    function AVC_LAYOUT_FORMATING(group, module){

        if(AVC_LAYOUT_HISTORY[group][countObjAttr(AVC_LAYOUT_HISTORY[group])]["modules"][module]["order_by"]){
            var value = AVC_LAYOUT_HISTORY[group][countObjAttr(AVC_LAYOUT_HISTORY[group])]["modules"][module]["order_by"];  
            var splited = value.split(" ");
            // ADD ARROW
            $$("#AVC_LAYOUT_"+module+" thead th").each(function(item,index){

                if(item.get("title") == splited[0]){
                    if(splited[1] == "DESC"){
                        item.set("text",item.get("text")+" ▲");
                    }else{
                        item.set("text",item.get("text")+" ▼");
                    }
                }
          
            });
        } 

        // SCROLL TO
        if(AVC_LAYOUT_VISUALS["scroll_to"]){
            $(document.body).scrollTo( AVC_LAYOUT_VISUALS["scroll_to"]["x"] , AVC_LAYOUT_VISUALS["scroll_to"]["y"] );
        }


    }


    function AVC_LAYOUT_SEARCH(module, searchValue){

        having = "{}";

        var group = $("AVC_SEARCH_GROUP_"+module).value;

        if(searchValue){
            var havingArray = [];
            for (var i=0; i < AVC_LAYOUT_FIELDLIST[module].length; i++) { 
                havingArray.push(AVC_LAYOUT_FIELDLIST[module][i]+\' LIKE \\\'%\'+searchValue+\'%\\\'\');
            }
            having = \'{"search":"\'+havingArray.join(\' OR \')+\'"}\';
        }

        AVC_LAYOUT_HISTORY_UPDATE(group, module, \'having\', JSON.parse(having));
        AVC_LAYOUT_SUBMIT();

    }


    window.addEvent("domready", function(){

        $$(".AVC_LAYOUT_INPUT_SEARCH").addEvents({
            "keydown":function(event){
                if (event.key == "enter"){
                    var module=this.id.replace("AVC_SEARCH_INPUT","");
                    var group = $("AVC_SEARCH_GROUP_"+module).value;
                    var searchValue=this.value;
                    AVC_LAYOUT_SEARCH(module, searchValue);
                } 
            }
        });

        $$(".AVC_LAYOUT_HOVER").addEvents({
            "mouseover": function(e){
                this.addClass("HOVER");
            },
            "mouseout": function(e){
                this.removeClass("HOVER");
            }
        });

        // new Fx.Accordion($(\'AVC_LAYOUT_ACCORDION\'), \'#AVC_LAYOUT_ACCORDION h1\', \'#AVC_LAYOUT_ACCORDION .AVC_LAYOUT_CONTENT\');

    });

    ';

    JFactory::getDocument()->addScriptDeclaration($JS_AVC_layout);

    echo '
    <form action=\'' . JRoute::_('index.php') . '\' method=\'post\' name=\'AVC_LAYOUT_FORM\'>
    <input type=\'hidden\' id=\'AVC_LAYOUT_STATE_HISTORY\' name=\'AVC_LAYOUT_STATE_HISTORY\' value=\'\' />
    <input type=\'hidden\' id=\'AVC_LAYOUT_STATE_VISUALS\' name=\'AVC_LAYOUT_STATE_VISUALS\' value=\'\' />
    <input type=\'hidden\' id=\'AVC_COLLECTION\' name=\'AVC_COLLECTION\' value=\'\' />
    </form>
    ';
