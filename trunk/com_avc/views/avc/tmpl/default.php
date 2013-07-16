<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
?>    

<div>
    <h1 class="pageTitle"><?php echo JText::_("COM_AVC_HOME"); ?></h1>
    <form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="adminForm" id="adminForm" class="">

        <div class="AVC_tableView">
            <label><?php echo JText::_("COM_AVC_QUICK_ICONS"); ?></label>

            <fieldset id="filter-bar">


            <div class="fltlft">
                <?php
                    require_once("menu.php");
                ?>
            </div>

            <div class="fltrt">
            </div>

            </fieldset>

            <!-- QUICK ICONS -->
            <?php

if (!empty($this->views)) {

    $view_groups = array();
    foreach ($this->views as $key => $view) {
        $view_groups[ $view["group_alias"] ][ $key ] = $view;
    }

    foreach ($view_groups as $group_name => $group_items) {

        echo '<hr />';
        echo '<h2>'. JText::_( strtoupper( $group_name )) .'</h2>';
        foreach ($group_items as $key => $view) {
            if ($view["published"]) {
                // Is image exists
                if (!empty ($view["icon_path"])) {
                    $view_image = JURI::root() . $view["icon_path"];
                } else {
                    $view_image = JURI::root() . 'administrator/components/com_avc/assets/images/icon-64-avc.png';
                }

                $tooltip = '';
                if(!empty($view["description"])){
                    $tooltip = 'title="'. htmlentities($view["description"]) .'"';
                }

                echo '
                <div class="quickIcon">
                <a
                href="#"
                onclick="
                AVC_menu_exec('.$key.',\'table\');
                return false;
                "
                '. $tooltip .'
                >
                <img src="' . $view_image . '" alt="">
                <span>' . JText::_($view["name"]) . '</span>
                </a>
                </div>
                ';
            }
        }

        echo '<p> </p>';

    }

}
            
            ?>

            <!-- PERSONAL NOTES -->
            <?php
                require_once('personal_notes.php');
            ?>

        </div>

        <input type="hidden" name="option" value="com_avc" />
        <input type="hidden" name="controller" value="avc" />
        <input type="hidden" name="layout" id="layout" value="default" />
        <input type="hidden" name="task" id="task" value="task" />
        <input type="hidden" name="curr_view_id" id="curr_view_id" value="<?php echo $this->curr_view_id;?>" />

    </form>
</div>

<?php
    require_once("forcedStyles.php");
?>