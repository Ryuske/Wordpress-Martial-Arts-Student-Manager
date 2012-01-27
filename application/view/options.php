<?php
$settings = get_option('ma_accounts_settings');
?>
<div id="option-tabs" style="clear:both; margin-right:20px;" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
    <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
        <li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active">
            <a href="#accounts">
                <span class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-power" style="float: left; margin-right: .3em;"></span></span>
                Accounts
            </a>
        </li>
        <li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active">
            <a href="#belts_programs">
                <span class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-script" style="float: left; margin-right: .3em;"></span></span>
                Belts &amp; VIP Programs
            </a>
        </li>
        <li class="ui-state-default ui-corner-top">
            <a href="#settings">
                <span class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-gear" style="float: left; margin-right: .3em;"></span></span>
                Settings
            </a>
        </li>
        <li class="ui-state-default ui-corner-top"><a href="#help">Help</a></li>
    </ul>
    <!--End Navigation-->

    <!--Accounts page-->
    <div id="accounts" class="ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide">
        <h1>Accounts</h1>
        <table class="ma_accounts_table">
            <tbody>
                <tr>
                    <td class="icon"><a href="#" onclick="jQuery('#update_account').dialog('open');"><span class="ui-icon ui-icon-pencil" style="position: relative; margin: 0 auto;"></span></a></td>
                    <td>Sensei Ryan</td>
                    <td>5th Degree Black</td>
                    <td>Swat, NLC</td>
                </td>
                <tr class="alt">
                    <td class="icon"><span class="ui-icon ui-icon-pencil" style="position: relative; margin: 0 auto;"></span></td>
                    <td>Kenyon Haliwell</td>
                    <td>2nd Brown</td>
                    <td>NLC</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!--Belts and Programs page-->
    <div id="belts_programs" class="ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide">
        <h1>Belts &amp; VIP Programs</h1>
        <h3 style="display: inline;">Belts</h3> <h5 style="display: inline; position: relative; bottom: 1px;"><a href="#belts_programs" onclick="jQuery('#add_belt').dialog('open')"><span class="ui-icon ui-icon-plusthick" style="display: inline-block; vertical-align: top;"></span>Add</a></h5>
        <?php
        if (empty($settings['belts'])) {
            echo '<div>You haven\'t added any belts yet! <a href="#belts_programs" onclick="jQuery(\'#add_belt\').dialog(\'open\')">Add</a> one now.</div>';
        } else {
            ?>
            <form id="update_belt_order" action="options.php#belts_programs" method="post">
                <?php settings_fields('ma_accounts_settings'); ?>
                <input id="new_order" name="ma_accounts_settings[new_order]" type="hidden" />
            </form>
            <div id="sortable_trash" style="width: 160px;">
                <div style="float: left;">
                    <?php
                    foreach ($settings['belts'] as $key => $value) {
                        echo '<div class="ma_accounts_ul_trash"><a href="plugins.php?page=ma_accounts&id=' . (int) $value['id'] . '&action=delete_belt#belts_programs"><span class="ui-icon ui-icon-trash"></span></a></div>';
                    }
                    ?>
                </div>
                <div style="float: right;">
                    <ul id="sortable" class="ma_accounts_ul">
                        <?php
                        foreach ($settings['belts'] as $key => $value) {
                            echo '<li id="' . (int) $value['id'] . '" class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s" style="float: left;"></span>' . esc_html($value['name']) . '</li>';
                        }
                        ?>
                    <ul>
                </div>
            </div>
            <div style="clear: both;"></div>
            <?php
        }
        ?>
        <br />
        <h3 style="display: inline;">VIP Programs</h3> <h5 style="display: inline; position: relative; bottom: 1px;"><a href="#belts_programs" onclick="jQuery('#add_program').dialog('open')"><span class="ui-icon ui-icon-plusthick" style="display: inline-block; vertical-align: top;"></span>Add</a></h5>
        <?php
        if (empty($settings['programs'])) {
            echo '<div>You haven\'t added any programs yet! <a href="#belts_programs" onclick="jQuery(\'#add_program\').dialog(\'open\')">Add</a> one now.</div>';
        } else {
            ?>
            <table>
                <tbody>
                    <?php
                    foreach ($settings['programs'] as $key => $value) {
                        ?>
                        <tr>
                            <td><a href="plugins.php?page=ma_accounts&id=<?php echo (int) $value['id']; ?>&action=delete_program#belts_programs"><span class="ui-icon ui-icon-trash" style="padding: 2px 0;"></span></a></td>
                            <td><?php esc_html_e($value['name']); ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
            <?php
        }
        ?>
    </div>

    <!--Settings page-->
    <div id="settings" class="ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide">
        <h1>Settings</h1>
        <form id="update_settings" name="update_settings" action="options.php#settings" method="post">
            <?php settings_fields('ma_accounts_settings'); ?>
            <label>Page to display once logged in</label> <br />
            <input name="ma_accounts_settings[login_page]" type="text" value="<?php esc_html_e($settings['login_page']); ?>" /> <br /><br />

            <label>Remove Roles (separate with a comma)</label> <br />
            <input style="width: 400px;" name="ma_accounts_settings[roles][remove]" type="text" value="<?php esc_html_e(implode(', ', $settings['roles']['remove'])); ?>" /> <br /><br />

            <label>Add Roles (separate with a comma, currently available: student, promoter)</label> <br />
            <input name="ma_accounts_settings[roles][add]" type="text" value="<?php esc_html_e(implode(', ', $settings['roles']['add'])); ?>" /> <br /><br />

            <label>Default Role</label> <br />
            <input name="ma_accounts_settings[roles][default]" type="text" value="<?php esc_html_e($settings['roles']['default']); ?>" /> <br /><br />

            <input class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" type="submit" name="Koala" value="Save Changes" />
        </form>
    </div>

    <!--Help page-->
    <div id="help" class="ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide">
        <h1>Help</h1>
        <p>Eventually, I'll add this.</p>
    </div>

    <!--Start dialog HTML-->
    <div id="update_account" title="Edit Account">
        <h2 style="text-align: center">Sensei Ryan</h2>
        <form id="edit_account" action="" method="post">
            <input name="type" type="hidden" value="ma_accounts[edit_student]" />
            <label class="ma_accounts_label">Belt</label>
            <span><select name="belt"><option value="white">White</option></select></span> <br /><br />
            <label class="ma_accounts_label">VIP Programs</label> <br />
            <table>
                <tbody>
                    <tr>
                        <td>SWAT</td>
                        <td><input type="checkbox" value="swat" /></td>
                    </tr>
                    <tr>
                        <td>NLC</td>
                        <td><input type="checkbox" value="nlc" /></td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>

    <!--Dialog HTML for Belts and Special Programs-->
    <!--Add Belt Dialog-->
    <div id="add_belt" title="Add Belt">
        <form id="add_belt_form" action="options.php#belts_programs" method="post">
            <?php settings_fields('ma_accounts_settings'); ?>
            <label class="ma_accounts_label">Title</label> <br />
            <input id="belt" name="ma_accounts_settings[belts]" type="text" />
        </form>
        <div id="add_belt_notification" class="ui-state-error ui-corner-all ma_accounts_notification" style="display: none; margin-top: 10px;"><span class="ui-icon ui-icon-info" style="float: left;"></span>&nbsp;Your forgot to add a belt!</div>
    </div>

    <!--Add Program Dialog-->
   <div id="add_program" title="Add Program">
        <form id="add_program_form" action="options.php#belts_programs" method="post">
            <?php settings_fields('ma_accounts_settings'); ?>
            <label class="ma_accounts_label">Title</label> <br />
            <input id="program" name="ma_accounts_settings[programs]" type="text" />
        </form>
        <div id="add_program_notification" class="ui-state-error ui-corner-all ma_accounts_notification" style="display: none; margin-top: 10px;"><span class="ui-icon ui-icon-info" style="float: left;"></span>&nbsp;Your forgot to add a program!</div>
    </div>


    <!--Delete Belt Dialog-->
    <?php
    if (is_numeric($_GET['id']) && $_GET['action'] === 'delete_belt') {
        $id = (int) $_GET['id'];
        ?>
        <script type="text/javascript">jQuery(document).ready(function(){jQuery('#delete_belt').dialog('open')});</script>
        <?php
    }
    ?>
    <div id="delete_belt" title="Delete Belt" style="text-align: center;">
        <?php
        if ($id <= count($settings['belts']) && $id > -1 && $_GET['action'] === 'delete_belt') {
            ?>
            Are you sure you want to delete the belt: <br />
            <?php esc_html_e($settings['belts'][$id]['name']); ?>
            <form id="delete_belt_form" action="options.php#belts_programs" method="post">
                <?php settings_fields('ma_accounts_settings'); ?>
                <input type="hidden" name="_wp_http_referer" value="/wp-admin/plugins.php?page=ma_accounts&amp;action=delete_belt">
                <input name="ma_accounts_settings[belt_id]" type="hidden" value="<?php echo $id; ?>" />
            </form>
            <?php
        }
        ?>
    </div>

    <!--Delete Program Dialog-->
    <?php
    if (is_numeric($_GET['id']) && $_GET['action'] === 'delete_program') {
        $id = (int) $_GET['id'];
        ?>
        <script type="text/javascript">jQuery(document).ready(function(){jQuery('#delete_program').dialog('open')});</script>
        <?php
    }
    ?>
    <div id="delete_program" title="Delete Program" style="text-align: center;">
        <?php
        if ($_GET['id'] <= count($settings['programs']) && $_GET['id'] > -1 && $_GET['action'] === 'delete_program') {
            ?>
            Are you sure you want to delete the program: <br />
            <?php esc_html_e($settings['programs'][$id]['name']); ?>
            <form id="delete_program_form" action="options.php#belts_programs" method="post">
                <?php settings_fields('ma_accounts_settings'); ?>
                <input type="hidden" name="_wp_http_referer" value="/wp-admin/plugins.php?page=ma_accounts&amp;action=delete_program">
                <input name="ma_accounts_settings[program_id]" type="hidden" value="<?php echo $id; ?>" />
            </form>
            <?php
        }
        ?>
    </div>
</div>
