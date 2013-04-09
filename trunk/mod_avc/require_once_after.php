    <?php

    /**
     * @package		Joomla.Site
     * @subpackage	mod_breadcrumbs
     * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
     * @license		GNU General Public License version 2 or later; see LICENSE.txt
     */
    // no direct access
    defined('_JEXEC') or die;


    // MAKE CONTENT BREADCRUMBS FOR CONTENT GROUP
    // if($AVC->group == "content"){  
    //     $AVC->contentBreadcrumbs();
    // }


    // MAKE BREADCRUMPS
    if(!empty($AVC->state_history["content"])){
        $app    = JFactory::getApplication();
        $items = $app->getPathway();
        $new_items = (array)$items;
        $new_items = reset($new_items);
        $last_item = end($new_items);
        $last_item->name = $last_item->name;
        $last_item->link = 'javascript:AVC_LAYOUT_GOTO(\'content\', 1);';
        array_pop($new_items);
        $new_items[] = $last_item;
        $historyCount = count($AVC->state_history["content"]);
        for ($i=1; $i < $historyCount; $i++) { 
            $opened = $AVC->state_history["content"][($i+1)]["opened"];
            $view_name = $AVC->state_history["content"][($i+1)]["modules"][$opened]["view_name"];
            $new_items[] = (object) array( 'name'=>$view_name, 'link'=>'javascript:AVC_LAYOUT_GOTO(\'content\', '.($i+1).');' );
        }
        $items->setPathWay(null);
        foreach ($new_items as $item) {
            $items->addItem($item->name, $item->link);
        }
    }