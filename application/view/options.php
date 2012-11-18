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
                $accounts = get_users('exclude=1&orderby=display_name');
                array_walk($accounts, function($account_value, $account_key) use($settings, &$alt) {
                    $account_info = get_userdata($account_value->ID);
                    $account_name = '';
                    if (!empty($account_info->first_name) && !empty($account_info->last_name)) {
                        $account_name = $account_info->first_name . ' ' . $account_info->last_name . ' (' . $account_info->nickname . ')';
                    } else {
                        $account_name = $account_info->nickname;
                    }

                    $account_programs = '';
                    $account_programs_array = explode(',', get_user_meta($account_value->ID, 'ma_accounts_programs', true));
                    foreach ($account_programs_array as $program_key => $program_value) {
                        $account_programs .= $settings['programs'][$program_value]['name'] . ', ';
                    }
                    $account_programs = substr($account_programs, 0, -2);
                    echo (is_int($alt/2)) ? '<tr>' : '<tr class="alt">';
                    ?>
                        <td class="icon"><a href="plugins.php?page=ma_accounts&id=<?php echo $account_value->ID ?>&action=update_account#accounts"><span class="ui-icon ui-icon-pencil" style="position: relative; margin: 0 auto;"></span></a></td>
                        <td><?php echo $account_name; ?></td>
                        <td><?php echo (get_user_meta($account_value->ID, 'ma_accounts_belt', true) === '') ? 'No belt set' : esc_html($settings['belts'][get_user_meta($account_value->ID, 'ma_accounts_belt', true)]['name']); ?></td>
                        <td><?php echo (get_user_meta($account_value->ID, 'ma_accounts_programs', true) === '') ? 'Not enrolled in any programs' : esc_html($account_programs); ?></td>
                    </tr>
                    <?php
                    $alt++;
                });
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
            <input name="ma_accounts_settings[login_page]" type="text" value="<?php if (!empty($settings['login'])) { esc_html_e($settings['login_page']); } ?>" /> <br /><br />

            <label>Remove Roles (separate with a comma)</label> <br />
            <input style="width: 400px;" name="ma_accounts_settings[roles][remove]" type="text" value="<?php if (!empty($settings['roles']['remove'])) { esc_html_e(implode(', ', $settings['roles']['remove'])); } ?>" /> <br /><br />

            <label>Add Roles (separate with a comma, currently available: student, promoter)</label> <br />
            <input name="ma_accounts_settings[roles][add]" type="text" value="<?php if (!empty($settings['roles']['add'])) { esc_html_e(implode(', ', $settings['roles']['add'])); } ?>" /> <br /><br />

            <label>Default Role</label> <br />
            <input name="ma_accounts_settings[roles][default]" type="text" value="<?php if (!empty($settings['roles']['default'])) { esc_html_e($settings['roles']['default']); } ?>" /> <br /><br />

            <input class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" type="submit" value="Save Changes" />
        </form>
    </div>

    <!--Help page-->
    <div id="help" class="ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide">
        <h1>Help</h1>
        <p>
            <h2>Updating account Belt Rank or Program Access</h2>
            Currently, rank information can only be updated from the options page. However, it can be viewed in Profile pages as well.
                <ol>
                    <li>Click the <i>Accounts</i> tab</li>
                    <li>Locate the account you wish to update</li>
                    <li>Click the <span class="ui-icon ui-icon-pencil" style="display: inline-block; position: relative; top: 3px;"></span> icon</li>
                    <li>Enter the desired information in the popup dialog</li>
                </ol>
        </p><br />
        <p>
            <h2>Adding/Removing Belts &amp; Programs</h2>
            <h3>Belt Information</h3>
                Belts are designed on a hierarchical system. Meaning, that someone with a given belt rank also has access to all the ranks below it. <br />
                <h5 style="line-height: 0;">Adding a Rank:</h5>
                    <ol>
                        <li>Go to the <i>Belts &amp; VIP Programs</i> tab</li>
                        <li>Click the <span class="ui-icon ui-icon-plusthick" style="display: inline-block; position: relative; top: 3px;"></span><b><u>Add</u></b> button next to <b>Belts</b></li>
                        <li>Fill out the popup dialog, and click <i>Add</i></li>
                    </ol>
                <h5 style="line-height: 0; margin-top: 25px;">Removing a Rank:</h5>
                    <ol>
                        <li>Go to the <i>Belts &amp; VIP Programs</i> tab</li>
                        <li>Find the rank you wish to delete</li>
                        <li>Click the <span class="ui-icon ui-icon-trash" style="display: inline-block; position: relative; top: 3px;"></span> icon</li>
                        <li>In the dialog that appears, click the <i>Delete</i> button</li>
                    </ol>
                <h5 style="line-height: 0; margin-top: 25px;">Re-ordering Ranks:</h5>
                    <ol>
                        <li>Go to the <i>Belts &amp; VIP Programs</i> tab</li>
                        <li>Click &amp; hold the belt rank you wish to re-order</li>
                        <li>Drag it to where ever you want it</li>
                        <li>Let go of the mouse</li>
                    </ol>
            <h3>Program Information</h3>
                <h5 style="line-height: 0;">Adding a Program:</h5>
                    <ol>
                        <li>Go to the <i>Belts &amp; VIP Programs</i> tab</li>
                        <li>Click the <span class="ui-icon ui-icon-plusthick" style="display: inline-block; position: relative; top: 3px;"></span><b><u>Add</u></b> button next to <b>VIP Programs</b></li>
                        <li>Fill out the popup dialog, and click <i>Add</i></li>
                    </ol>
                <h5 style="line-height: 0; margin-top: 25px;">Removing a Program:</h5>
                    <ol>
                        <li>Go to the <i>Belts &amp; VIP Programs</i> tab</li>
                        <li>Find the program you wish to delete</li>
                        <li>Click the <span class="ui-icon ui-icon-trash" style="display: inline-block; position: relative; top: 3px;"></span> icon</li>
                        <li>In the dialog that appears, click the <i>Delete</i> button</li>
                    </ol>
        </p><br />
        <p>
            <h2>Settings</h2>
                Under the <i>Settings</i> tab, there are multiple options. Here is a list of what they mean: <br />
                <table class="ma_accounts_help_table">
                    <tbody>
                        <tr>
                            <td>Page to display once logged in</td>
                            <td>This is the <a href="<?php echo admin_url(); ?>edit.php?post_type=page" target="_blank">Page</a> in WordPress that is displayed once someone has logged into WordPress</td>
                        </tr>
                        <tr>
                            <td>Remove Roles</td>
                            <td>List any existing WordPress roles you would like to remove. For a list of roles,<br />please visit <a href="<?php echo admin_url(); ?>user-new.php" target="_blank">Add User</a> and click the drop down next to <i>Roles</i></td>
                        </tr>
                        <tr>
                            <td>Add Roles</td>
                            <td>These are roles you would like to add to WordPress</td>
                        </tr>
                        <tr>
                            <td>Default Role</td>
                            <td>This is the default role a user will recieve when creating new accounts</td>
                        </tr>
                    <tbody>
                </table>
                If you would like more information about what a role is, please visit: <a href="http://codex.wordpress.org/Roles_and_Capabilities" target="_blank">Roles and Capabilities &laquo; WordPress Codex</a>
        </p>
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
        $account = get_userdata($id);

        if ($account) {
            $name = '';
            if (isset($account->first_name) && isset($account->last_name)) {
                $name = $account->first_name . ' ' . $account->last_name . ' (' . $account->nickname . ')';
            } else {
                $name = $account->nickname;
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
        } else {
            echo 'Invalid user ID.';
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
        if (!empty($settings['belts'][$id]) && $_GET['action'] === 'delete_belt') {
            ?>
            Are you sure you want to delete the belt: <br />
            <?php esc_html_e($settings['belts'][$id]['name']); ?>
            <form id="delete_belt_form" action="options.php#belts_programs" method="post">
                <?php settings_fields('ma_accounts_settings'); ?>
                <input type="hidden" name="_wp_http_referer" value="/wp-admin/plugins.php?page=ma_accounts&amp;action=delete_belt">
                <input name="ma_accounts_settings[belt_id]" type="hidden" value="<?php echo $id; ?>" />
            </form>
            <?php
        } else {
            echo 'Invalid belt ID.';
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
        if (!empty($settings['programs'][$id]) && $_GET['action'] === 'delete_program') {
            ?>
            Are you sure you want to delete the program: <br />
            <?php esc_html_e($settings['programs'][$id]['name']); ?>
            <form id="delete_program_form" action="options.php#belts_programs" method="post">
                <?php settings_fields('ma_accounts_settings'); ?>
                <input type="hidden" name="_wp_http_referer" value="/wp-admin/plugins.php?page=ma_accounts&amp;action=delete_program">
                <input name="ma_accounts_settings[program_id]" type="hidden" value="<?php echo $id; ?>" />
            </form>
            <?php
        } else {
            echo 'Invalid program ID.';
        }
        ?>
    </div>
</div>
