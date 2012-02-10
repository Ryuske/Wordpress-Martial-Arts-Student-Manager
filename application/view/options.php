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
                <?php
                $alt = 0;
                foreach (get_users('exclude=1&orderby=display_name') as $account) {
                    $account_info = get_userdata($account->ID);
                    $account_name = '';
                    if (isset($account_info->nickname)) {
                        $account_name = $account_info->nickname;
                        if (isset($account_info->last_name)) {
                            $account_name .= ' ' . $account_info->last_name;
                        }
                    } else if (isset($account_info->first_name) && isset($account_info->last_name)) {
                        $account_name = $account_info->first_name . ' ' . $account_info->last_name;
                    } else {
                        $account_name = $account->display_name;
                    }

                    $account_programs = '';
                    $account_programs_array = explode(',', get_user_meta($account->ID, 'ma_accounts_programs', true));
                    foreach ($account_programs_array as $program_key => $program_value) {
                        $account_programs .= $settings['programs'][$program_value]['name'] . ', ';
                    }
                    $account_programs = substr($account_programs, 0, -2);
                    echo (is_int($alt/2)) ? '<tr>' : '<tr class="alt">';
                    ?>
                        <td class="icon"><a href="plugins.php?page=ma_accounts&id=<?php echo $account->ID ?>&action=update_account#accounts"><span class="ui-icon ui-icon-pencil" style="position: relative; margin: 0 auto;"></span></a></td>
                        <td><?php echo $account_name; ?></td>
                        <td><?php echo (get_user_meta($account->ID, 'ma_accounts_belt', true) === '') ? 'No belt set' : esc_html($settings['belts'][get_user_meta($account->ID, 'ma_accounts_belt', true)]['name']); ?></td>
                        <td><?php echo (get_user_meta($account->ID, 'ma_accounts_programs', true) === '') ? 'Not enrolled in any programs' : esc_html($account_programs); ?></td>
                    </tr>
                    <?php
                    $alt++;
                }
                ?>
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
                    foreach ($settings['belts'] as $belt_key => $belt_value) {
                        echo '<div class="ma_accounts_ul_trash"><a href="plugins.php?page=ma_accounts&id=' . (int) $belt_value['id'] . '&action=delete_belt#belts_programs"><span class="ui-icon ui-icon-trash"></span></a></div>';
                    }
                    ?>
                </div>
                <div style="float: right;">
                    <ul id="sortable" class="ma_accounts_ul">
                        <?php
                        foreach ($settings['belts'] as $belt_key => $belt_value) {
                            echo '<li id="' . (int) $belt_value['id'] . '" class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s" style="float: left;"></span>' . esc_html($belt_value['name']) . '</li>';
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
                    foreach ($settings['programs'] as $program_key => $program_value) {
                        ?>
                        <tr>
                            <td><a href="plugins.php?page=ma_accounts&id=<?php echo (int) $program_value['id']; ?>&action=delete_program#belts_programs"><span class="ui-icon ui-icon-trash" style="padding: 2px 0;"></span></a></td>
                            <td><?php esc_html_e($program_value['name']); ?></td>
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

            <input class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" type="submit" value="Save Changes" />
        </form>
    </div>

    <!--Help page-->
    <div id="help" class="ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide">
        <h1>Help</h1>
        <p>Rank information can only be updated from the options page, though it may be viewed in profile pages as well</p>
        <p>Our options name is "ma_accounts_settings". If you're not sure what this mean, please ignore it.</p>
        <p>Check us out on GitHub to track the latest updates and releases: <a href="https://github.com/Ryuske/Wordpress-Martial-Arts-Student-Manager" target="_blank">https://github.com/Ryuske/Wordpress-Martial-Arts-Student-Manager</a>
    </div>

    <!--Start dialog HTML-->
    <?php
    if (is_numeric($_GET['id']) && $_GET['action'] === 'update_account') {
        $id = (int) $_GET['id'];
        ?>
        <script type="text/javascript">jQuery(document).ready(function(){jQuery('#update_account').dialog('open')});</script>
        <?php
    }
    ?>
    <div id="update_account" title="Edit Account">
        <?php
        $total_users = count_users();
        $total_users = $total_users['total_users'];

        if ($id <= $total_users && $id > 0) {
            $account = get_userdata($id);
            $name = '';
            if (isset($account->nickname)) {
                $name = $account->nickname;
                if (isset($account->last_name)) {
                    $name .= ' ' . $account->last_name;
                }
            } else if (isset($account->first_name) && isset($account->last_name)) {
                $name = $account->first_name . ' ' . $account->last_name;
            } else {
                $name = $account->display_name;
            }

            if (get_user_meta($id, 'ma_accounts_programs', true) !== '') {
                $programs_array = explode(',', get_user_meta($id, 'ma_accounts_programs', true));
                $temp = array();
                foreach ($programs_array as $program_key => $program_value) {
                    $temp[$program_value] = $program_value;
                }
            }
            $programs_array = $temp;
            unset($temp);
            ?>
            <h2 style="text-align: center"><?php esc_html_e($name); ?></h2>
            <form id="edit_account" action="options.php#accounts" method="post">
                <?php settings_fields('ma_accounts_settings'); ?>
                <input type="hidden" name="_wp_http_referer" value="/wp-admin/plugins.php?page=ma_accounts&amp;action=update_account">
                <input name="ma_accounts_settings[update_account]" type="hidden" value="<?php echo $id; ?>" />
                <label class="ma_accounts_label">Belt</label>
                <span>
                    <select name="ma_accounts_settings[belts]">
                        <?php
                        foreach ($settings['belts'] as $belt_key => $belt_value) {
                            echo ($belt_value['id'] == get_user_meta($id, 'ma_accounts_belt', true)) ? '<option value="' . esc_html($belt_value['id']) . '" selected="selected">' . esc_html($belt_value['name']) . '</option>' : '<option value="' . esc_html($belt_value['id']) . '">' . esc_html($belt_value['name']) . '</option>';
                        }
                        ?>
                    </select>
                </span> <br /><br />
                <label class="ma_accounts_label">VIP Programs</label> <br />
                <table>
                    <tbody>
                        <?php
                        foreach ($settings['programs'] as $program_key => $program_value) {
                            ?>
                            <tr>
                            <td><?php esc_html_e($program_value['name']); ?></td>
                            <td><?php echo (isset($programs_array[$program_value['id']])) ? '<input name="ma_accounts_settings[programs][' . esc_html($program_value['id']) . ']" type="checkbox" value="' . esc_html($program_value['id']) . '" checked="checked" />' : '<input name="ma_accounts_settings[programs][' . esc_html($program_value['id']) . ']" type="checkbox" value="' . esc_html($program_value['id']) . '" />'; ?></td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </form>
            <?php
        }
        ?>
    </div>

    <!--Dialog HTML for Belts and Special Programs-->
    <!--Add Belt Dialog-->
    <div id="add_belt" title="Add Belt">
        <form id="add_belt_form" action="options.php#belts_programs" method="post">
            <?php settings_fields('ma_accounts_settings'); ?>
            <input type="hidden" name="_wp_http_referer" value="/wp-admin/plugins.php?page=ma_accounts&amp;action=add_belt">
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
