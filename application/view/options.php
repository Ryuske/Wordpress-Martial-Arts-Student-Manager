<?php
global $ma_accounts;
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
        <div id="sortable_trash" style="width: 120px;">
            <div style="float: left;">
                <div class="ma_accounts_ul_trash"><a href="plugins.php?page=ma_accounts&id=white&action=delete_belt#belts_programs"><span class="ui-icon ui-icon-trash"></span></a></div>
                <div class="ma_accounts_ul_trash"><a href="plugins.php?page=ma_accounts&id=green&action=delete_belt#belts_programs"><span class="ui-icon ui-icon-trash"></span></a></div>
                <div class="ma_accounts_ul_trash"><a href="plugins.php?page=ma_accounts&id=black&action=delete_belt#belts_programs"><span class="ui-icon ui-icon-trash"></span></a></div>
            </div>
            <div style="float: right;">
                <ul id="sortable" class="ma_accounts_ul">
                    <li id="white" class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s" style="float: left;"></span>White</li>
                    <li id="green" class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s" style="float: left;"></span>Green</li>
                    <li id="black" class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s" style="float: left;"></span>Black</li>
                <ul>
            </div>
        </div>
        <div style="clear: both;"></div>
        <br />
        <h3 style="display: inline;">VIP Programs</h3> <h5 style="display: inline; position: relative; bottom: 1px;"><a href="#belts_programs" onclick="jQuery('#add_program').dialog('open')"><span class="ui-icon ui-icon-plusthick" style="display: inline-block; vertical-align: top;"></span>Add</a></h5>
        <table>
            <tbody>
                <tr>
                    <td><a href="plugins.php?page=ma_accounts&id=swat&action=delete_program#belts_programs"><span class="ui-icon ui-icon-trash" style="padding: 2px 0;"></span></a></td>
                    <td>Swat</td>
                </tr>
                <tr>
                    <td><a href="plugins.php?page=ma_accounts&id=nlc&action=delete_program#belts_programs"><span class="ui-icon ui-icon-trash" style="padding: 2px 0;"></span></a></td>
                    <td>NLC</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!--Settings page-->
    <div id="settings" class="ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide">
        <h1>Settings</h1>
        <form id="update_settings" name="update_settings" action="options.php#settings" method="post">
            <?php
            settings_fields('ma_accounts_settings');
            $settings = get_option('ma_accounts_settings');
            wp_create_nonce('ma_accounts_nonce');
            ?>
            <label>Page to display once logged in</label> <br />
            <input name="ma_accounts_settings[login_page]" type="text" value="<?php echo $settings['login_page']; ?>" /> <br /><br />

            <label>Remove Roles (separate with a comma)</label> <br />
            <input style="width: 400px;" name="ma_accounts_settings[roles][remove]" type="text" value="<?php echo implode(', ', $settings['roles']['remove']); ?>" /> <br /><br />

            <label>Add Roles (separate with a comma, currently available: student, promoter)</label> <br />
            <input name="ma_accounts_settings[roles][add]" type="text" value="<?php echo implode(', ', $settings['roles']['add']); ?>" /> <br /><br />

            <label>Default Role</label> <br />
            <input name="ma_accounts_settings[roles][default]" type="text" value="<?php echo $settings['roles']['default']; ?>" /> <br /><br />

            <input class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" type="submit" name="Submit" value="<?php _e('Save Changes'); ?>" />
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
    <div id="add_belt" title="Add Belt">
        <form id="add_belt_form" action="" method="post">
            <input name="type" type="hidden" value="ma_accounts[add_belt]" />
            <label class="ma_accounts_label">Title</label> <br />
            <input name="belt" type="text" />
        </form>
    </div>

   <div id="add_program" title="Add Program">
        <form id="add_program_form" action="" method="post">
            <input name="type" type="hidden" value="ma_accounts[add_program]" />
            <label class="ma_accounts_label">Title</label> <br />
            <input name="program" type="text" />
        </form>
    </div>


    <?php
    if (/*is_numeric($_GET['id']) && */$_GET['action'] === 'delete_belt') {
        ?>
        <script type="text/javascript">jQuery(document).ready(function(){jQuery('#delete_belt').dialog('open')});</script>
        <?php
    }
    ?>
    <div id="delete_belt" title="Delete Belt" style="text-align: center;">
        Are you sure you want to delete the belt: <br />
        Black
        <form id="delete_question" action="plugins.php?page=quiz_manager" method="post">
            <input name="type" type="hidden" value="delete" />
            <input name="id" type="hidden" value="koala" />
        </form>
    </div>

    <?php
    if (/*is_numeric($_GET['id']) && */$_GET['action'] === 'delete_program') {
        ?>
        <script type="text/javascript">jQuery(document).ready(function(){jQuery('#delete_program').dialog('open')});</script>
        <?php
    }
    ?>
    <div id="delete_program" title="Delete Program" style="text-align: center;">
        Are you sure you want to delete the program: <br />
        Swat
        <form id="delete_question" action="plugins.php?page=quiz_manager" method="post">
            <input name="type" type="hidden" value="delete" />
            <input name="id" type="hidden" value="koala" />
        </form>
    </div>
</div>
