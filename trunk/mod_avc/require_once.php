    <?php

    /**
     * @package		Joomla.Site
     * @subpackage	mod_breadcrumbs
     * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
     * @license		GNU General Public License version 2 or later; see LICENSE.txt
     */
    // no direct access
    defined('_JEXEC') or die;

    $JS_AVC_layout = '
    ///////////////////////////////////////
    //
    // AVC LAYOUT REQUIRE ONCE
    //
    ///////////////////////////////////////

    var AVC_LAYOUT_HISTORY = {};
    var AVC_LAYOUT_HISTORY_SNAPSHOT = {};
    var AVC_TEMPLATE = {};
    var AVC_LAYOUT_SCROLLTO;

    function countObjAttr(obj) {
        var count = 0;
        for(var prop in obj) {
            if(obj.hasOwnProperty(prop))
                count = count + 1;
        }
        return count;
    }

    function AVC_LAYOUT_SNAPSHOT(module, index){

        console.log(AVC_LAYOUT_HISTORY_SNAPSHOT);

        AVC_LAYOUT_HISTORY = AVC_LAYOUT_HISTORY_SNAPSHOT[index];
        
        AVC_LAYOUT_SUBMIT(module);

    }

    function AVC_LAYOUT_ORDER(module,colName){

        if(AVC_LAYOUT_HISTORY["module"+module]["step"+countObjAttr(AVC_LAYOUT_HISTORY["module"+module])]["order_by"]){

            var splited = AVC_LAYOUT_HISTORY["module"+module]["step"+countObjAttr(AVC_LAYOUT_HISTORY["module"+module])]["order_by"].split(" ");

            if(colName == splited[0]){
                if(splited[1] == "ASC"){
                    AVC_LAYOUT_HISTORY_UPDATE(module, "order_by", colName+" DESC");
                }else{
                    AVC_LAYOUT_HISTORY_UPDATE(module, "order_by", colName+" ASC");
                }
            }else{
                AVC_LAYOUT_HISTORY_UPDATE(module, "order_by", colName+" ASC");
            }

        }  

        AVC_LAYOUT_SUBMIT(module);

    }

    function AVC_LAYOUT_OPEN(module,options){

        var data,moduleID;

        Object.each(AVC_TEMPLATE["module"+module]["open"], function(value, key){
            data = value;
            if(value["module"]==null){
                moduleID = "module"+module;
            }else{
                moduleID = "module"+value["module"];
            }
            data = JSON.stringify( data );
            for (var i = 0; i < options.length; i++) {
                data = data.replace("AVCVAR"+i,options[i]);
            }
            if(AVC_LAYOUT_HISTORY[ moduleID ]!=null){
                AVC_LAYOUT_HISTORY[ moduleID ][ "step"+(countObjAttr(AVC_LAYOUT_HISTORY[ moduleID ])+1) ]=JSON.parse(data);
            }
        });

        AVC_LAYOUT_SUBMIT(module);

    }

    function AVC_LAYOUT_GOTO(module,goto){

        while ( countObjAttr(AVC_LAYOUT_HISTORY["module"+module]) > goto ) {
            delete AVC_LAYOUT_HISTORY["module"+module]["step"+countObjAttr(AVC_LAYOUT_HISTORY["module"+module])];
        }

        AVC_LAYOUT_SUBMIT(module);

    }

    function AVC_LAYOUT_HISTORY_UPDATE(module, key, value){
        AVC_LAYOUT_HISTORY["module"+module]["step"+countObjAttr(AVC_LAYOUT_HISTORY["module"+module])][key]=value;
    }

    function AVC_LAYOUT_SUBMIT(module){

        var scroll =  $(document.body).getScroll();
        $("AVC_LAYOUT_STATE_SCROLLTO").value = scroll.x+","+scroll.y;
        $("AVC_LAYOUT_STATE_HISTORY").value = JSON.stringify(AVC_LAYOUT_HISTORY);

        document.AVC_LAYOUT_FORM.submit();

    }

    function AVC_LAYOUT_FORMATING(module){

        // ADD ARROW
        $$("#AVC_LAYOUT_"+module+" thead th").each(function(item,index){

            if(AVC_LAYOUT_HISTORY["module"+module]["step"+countObjAttr(AVC_LAYOUT_HISTORY["module"+module])]["order_by"]){

                var splited = AVC_LAYOUT_HISTORY["module"+module]["step"+countObjAttr(AVC_LAYOUT_HISTORY["module"+module])]["order_by"].split(" ");
                    
                if(item.get("title") == splited[0]){
                    if(splited[1] == "DESC"){
                        item.set("text",item.get("text")+" ▲");
                    }else{
                        item.set("text",item.get("text")+" ▼");
                    }
                }

            }        

        });

        // SCROLL TO
        splited = AVC_LAYOUT_SCROLLTO.split(",");
        $(document.body).scrollTo(parseInt(splited[0]),parseInt(splited[1]));


    }

    window.addEvent("domready", function(){

        $$(".AVC_LAYOUT_HOVER").addEvents({
            "mouseover": function(e){
                this.addClass("HOVER");
            },
            "mouseout": function(e){
                this.removeClass("HOVER");
            }
        });

    });

    ';

    JFactory::getDocument()->addScriptDeclaration($JS_AVC_layout);

    echo '
    <form action=\'' . JRoute::_('index.php') . '\' method=\'post\' name=\'AVC_LAYOUT_FORM\'>
    <input type=\'hidden\' id=\'AVC_LAYOUT_STATE_HISTORY\' name=\'AVC_LAYOUT_STATE_HISTORY\' value=\'\' />
    <input type=\'hidden\' id=\'AVC_LAYOUT_STATE_SCROLLTO\' name=\'AVC_LAYOUT_STATE_SCROLLTO\' value=\'\' />
    </form>
    ';