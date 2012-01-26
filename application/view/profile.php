<?php
function ma_accounts_profile_html($user) {
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function() {
            jQuery('h3').eq(0).html('');
            jQuery('.form-table:eq(0) tr').html('');
            jQuery('.form-table:eq(1) tr').eq(0).html('');
            jQuery('.form-table:eq(1) tr').eq(4).html('');
            jQuery('.form-table:eq(2) tr').eq(1).html('');
            jQuery('.form-table:eq(2) tr').eq(2).html('');
            jQuery('.form-table:eq(2) tr').eq(3).html('');
            jQuery('.form-table:eq(2) tr').eq(4).html('');
            jQuery('.form-table:eq(3) tr').eq(0).html('');
            //Hide fields with this. Also need to make this an option in the admin section (i.e. turn on/off)
        });
    </script>
    <h3>Rank Information<h3>
    <table class="form-table">
        <tbody>
            <tr>
                <th><label for="belt">Belt</label></th>
                <td><span class="description"><?php echo esc_attr(get_the_author_meta('belt', $user->ID)); ?></span>
            </tr>
            <tr>
                <th><label for="vip">VIP Programs</label></th>
                <td><span class="description"><?php echo esc_attr(get_the_author_meta('vip', $user->ID)); ?></span>
            </tr>
        </tbody>
    </table>
    <?php
}

function ma_accounts_edit_profile_html($user) {
    if ('administrator' !== wp_get_current_user()->roles[0]) {
        ?>
        <script type="text/javascript">
        jQuery(document).ready(function() {
            jQuery('h3').eq(0).html('');
            jQuery('h3').eq(1).html('');
            jQuery('h3').eq(2).html('');
            jQuery('h3').eq(3).html('');
            jQuery('.form-table:eq(0)').html('');
            jQuery('.form-table:eq(1)').html('');
            jQuery('.form-table:eq(2)').html('');
            jQuery('.form-table:eq(3)').html('');
            jQuery('table').eq(5).html('');
        });
        </script>
        <?php
    }
    ?>
    <h3>Rank Information<h3>
    <table class="form-table">
        <tbody>
            <tr>
                <th><label for="belt">Belt</label></th>
                <td>
                    <select name="belt" id="belt">
                    <option value="current"><?php echo esc_attr(get_the_author_meta('belt', $user->ID)); ?></option>
                        <option disabled="disabled">-----------------------</option>
                        <option value="white">White</option>
                    </select> <br />
                    <span class="description">Students current belt</span>
                </td>
            </tr>
            <tr>
                <th><label for="vip">VIP Programs</label></th>
                <td>
                    Swat: <input style="vertical-align: top;" type="checkbox" name="swat" id="swat" /> <br />
                    <span class="description">VIP programs student is currently enrolled in<?php echo esc_attr(get_the_author_meta('vip', $user->ID)); ?></span>
                </td>
            </tr>
        </tbody>
    </table>
    <?php
}
?>
