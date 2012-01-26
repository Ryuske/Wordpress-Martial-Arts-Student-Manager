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
    <div id="accounts" class="ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide">
        <h1>Accounts</h1>
    </div>
    <div id="belts_programs" class="ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide">
        <h1>Belts &amp; VIP Programs</h1>
    </div>
    <div id="settings" class="ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide">
        <h1>Settings</h1>
        <form id="update_options" name="update_options" action="" method="post">
            Temp <input id='quiz_length' name="quiz_manager_settings[quiz_length]" type="text" value="<?php echo $settings['quiz_length']; ?>" /> <br />
            <input class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" type="submit" value="Save Changes" />
        </form>
    </div>
    <div id="help" class="ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide">
        <h1>Help</h1>
        <p>Eventually, I'll add this.</p>
    </div>
    <div id="add" title="Add Question" style="text-align: center;">
        <form id="add_question" action="" method="post">
            <input name="type" type="hidden" value="add" />
            <label>Question</label> <br />
            <input style="width: 265px;" name="question" type="text" maxlength="150" /> <br /><br />
            <table style="margin: 0 auto;">
                <tbody>
                    <tr>
                        <td>Answer A</td>
                        <td><input name="answer[a]" type="text" /></td>
                        <td><input name="answer[answer]" type="radio" value="a" /></td>
                    </tr>
                    <tr>
                        <td>Answer B</td>
                        <td><input name="answer[b]" type="text" /></td>
                        <td><input name="answer[answer]" type="radio" value="b" /></td>
                    </tr>
                        <td>Answer C</td>
                        <td><input name="answer[c]" type="text" /></td>
                        <td><input name="answer[answer]" type="radio" value="c" /></td>
                    <tr>
                        <td>Answer D</td>
                        <td><input name="answer[d]" type="text" /></td>
                        <td><input name="answer[answer]" type="radio" value="d" /></td>
                    </tr>
                </tbody>
            </table>
        </form>
        <div id="add_tips">All form fields are required!</div>
    </div>
    <?php
    if (is_numeric($_GET['id']) && $_GET['action'] === 'update') {
        ?>
        <script type="text/javascript">jQuery(document).ready(function(){jQuery('#edit').dialog('open');});</script>
        <?php
    }
    ?>
    <div id="update" title="Edit Question" style="text-align: center;">
        <?php
        if ($_GET['id'] <= count($quiz_manager->questions) && $_GET['id'] > 0 && $_GET['action'] === 'update') {
            $id = $_GET['id'];
            ?>
            <form id="update_question" action="plugins.php?page=quiz_manager" method="post">
                <input name="type" type="hidden" value="update" />
                <input name="id" type="hidden" value="<?php echo $id; ?>" />
                <label>Question</label> <br />
                <input style="width: 265px;" name="question" type="text" maxlength="150" value="<?php esc_html_e($quiz_manager->questions[$id]['question']); ?>" /> <br /><br />
                <table style="margin: 0 auto;">
                    <tbody>
                        <tr>
                            <td>Answer A</td>
                            <td><input name="answer[a]" type="text" value="<?php esc_html_e($quiz_manager->questions[$id]['answers']['a']); ?>" /></td>
                            <td><input name="answer[answer]" type="radio" value="a" <?php echo ($quiz_manager->questions[$id]['answers']['answer'] === 'a') ? 'checked="checked"' : ''; ?> /></td>
                        </tr>
                        <tr>
                            <td>Answer B</td>
                            <td><input name="answer[b]" type="text" value="<?php esc_html_e($quiz_manager->questions[$id]['answers']['b']); ?>" /></td>
                            <td><input name="answer[answer]" type="radio" value="b" <?php echo ($quiz_manager->questions[$id]['answers']['answer'] === 'b') ? 'checked="checked"' : ''; ?> /></td>
                        </tr>
                            <td>Answer C</td>
                            <td><input name="answer[c]" type="text" value="<?php esc_html_e($quiz_manager->questions[$id]['answers']['c']); ?>" /></td>
                            <td><input name="answer[answer]" type="radio" value="c" <?php echo ($quiz_manager->questions[$id]['answers']['answer'] === 'c') ? 'checked="checked"' : ''; ?> /></td>
                        <tr>
                            <td>Answer D</td>
                            <td><input name="answer[d]" type="text" value="<?php esc_html_e($quiz_manager->questions[$id]['answers']['d']); ?>" /></td>
                            <td><input name="answer[answer]" type="radio" value="d" <?php echo ($quiz_manager->questions[$id]['answers']['answer'] === 'd') ? 'checked="checked"' : ''; ?> /></td>
                        </tr>
                    </tbody>
                </table>
            </form>
            <div id="edit_tips">All form fields are required!</div>
            <?php
        } else {
            ?>
            <script type="text/javascript">jQuery(document).ready(function(){jQuery('#edit').dialog('close');});</script>
            <?php
        }
        ?>
    </div>
    <?php
    if (is_numeric($_GET['id']) && $_GET['action'] === 'delete') {
        ?>
        <script type="text/javascript">jQuery(document).ready(function(){jQuery('#delete').dialog('open')});</script>
        <?php
    }
    ?>
    <div id="delete" title="Delete Question" style="text-align: center;">
        <?php
        if ($_GET['id'] <= count($quiz_manager->questions) && $_GET['id'] > 0 && $_GET['action'] === 'delete') {
            $id = $_GET['id'];
            ?>
            Are you sure you want to delete the question: <br />
            <?php esc_html_e($quiz_manager->questions[$id]['question']); ?>
            <form id="delete_question" action="plugins.php?page=quiz_manager" method="post">
                <input name="type" type="hidden" value="delete" />
                <input name="id" type="hidden" value="<?php echo $quiz_manager->questions[$id]['id']; ?>" />
            </form>
            <?php
        }
        ?>
    </div>
</div>
